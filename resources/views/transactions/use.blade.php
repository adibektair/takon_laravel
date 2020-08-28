@extends('layouts.main')

@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Использованные таконы</h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">

         <!-- <label>Товар/услуга</label>
          <select id="statusFilter">
            <option>Не выбрано</option>
            @foreach ($services as $service)
            <option value="{{$service->id}}">{{$service->name}}</option>
           @endforeach
          </select> -->

        <div class="panel panel-default">
            <div class="panel-body">

                <table border="0" cellspacing="5" cellpadding="5">
                    <tbody>
                    <tr>
                        <td>C какого числа:</td>
                        <td><input class="form-control" name="min" id="min" type="date"></td>
                    </tr>
                    <tr>
                        <td>По какое число:</td>
                        <td><input class="form-control" name="max" id="max" type="date"></td>
                    </tr>
                    <tr>
                        <td>Услуга:</td>
                        <td>
                            <select class="form-control" name="service_id" id="service_id">
                                <option value="">Не выбрано</option>
                                @foreach ($services as $service)
                                    <option value="{{$service->id}}">{{$service->name}}</option>
                                @endforeach
                                <option value=""></option>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <table class="table table-bordered" id="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Телефон</th>
                        {{--<th>Имя</th>--}}
                        <?php
                        if(auth()->user()->role_id == 1){
                        ?>
                        <th>Компания</th>
                        <?php
                        }
                        ?>
                        <th>Услуга/Товар</th>
                        <th>Использовано</th>
                        <th>Принял</th>
                        <th>Дата</th>

                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <style>
        table {
            width: 100% !important;
            margin: 0 auto !important;

        }
    </style>

    <script>
        $(document).ready(function () {
            var table = $('#table').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                language: {
                    url: '{{asset('admin/bower_components/datatable/js/ru.locale.json')}}',
                },
                ajax: "{{ route('transactions.use.all') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'sender', name: 'sender'},
                    // {data: 'username', name: 'username'},
                        <?php
                        if(auth()->user()->role_id == 1){
                        ?>
                    {
                        data: 'company', name: 'company'
                    },

                        <?php
                        }
                        ?>
                    {
                        data: 'service', name: 'service'
                    },
                    {data: 'amount', name: 'amount'},
                    {data: 'reciever', name: 'reciever'},
                    {data: 'created_at', name: 'created_at'},
                ],
                dom: 'Bfrltip',
                buttons: {
                    buttons: [
                        {extend: 'copy', className: 'btn btn-warning'},
                        {extend: 'excel', className: 'btn btn-success', action: newExportAction}
                    ]
                },
            });

            var new_url = '/transactions/use/all?';
            var min_value, max_value, service_value;

            $('#min').on('change', function () {
                if(new_url.includes('minDate'))
                {
                    new_url = new_url.replace('minDate=' + min_value + '&', '');
                }
                var filter_value = $(this).val();
                min_value = filter_value;
                new_url = new_url + 'minDate=' + filter_value + '&';
                table.ajax.url(new_url).load();
            });

            $('#max').on('change', function () {
                if(new_url.includes('maxDate'))
                {
                    new_url = new_url.replace('maxDate=' + max_value + '&', '');
                }
                var filter_value = $(this).val();
                max_value = filter_value;
                new_url = new_url + 'maxDate=' + filter_value + '&';
                table.ajax.url(new_url).load();
            });

            $('#service_id').on('change', function () {
                if(new_url.includes('service_id'))
                {
                    new_url = new_url.replace('service_id=' + service_value + '&', '');
                }
                var filter_value = $(this).val();
                service_value = filter_value;
                new_url = new_url + 'service_id=' + filter_value + '&';
                table.ajax.url(new_url).load();
            });

             /* $('#statusFilter').on('change', function () {
                var filter_value = $(this).val();
                var new_url = '/transactions/use/all?id=' + filter_value;
                dtListUsers.ajax.url(new_url).load();
            }); */
        });


        var newExportAction = function (e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;

            dt.one('preXhr', function (e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;

                dt.one('preDraw', function (e, settings) {
                    // Call the original action function
                    oldExportAction(self, e, dt, button, config);

                    dt.one('preXhr', function (e, s, data) {
                        // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                        // Set the property to what it was before exporting.
                        settings._iDisplayStart = oldStart;
                        data.start = oldStart;
                    });

                    // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                    setTimeout(dt.ajax.reload, 0);

                    // Prevent rendering of the full data to the DOM
                    return false;
                });
            });

            // Requery the server with the new one-time export settings
            dt.ajax.reload();
        };
        var oldExportAction = function (self, e, dt, button, config) {
            if (button[0].className.indexOf('buttons-excel') >= 0) {
                if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
                }
                else {
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                }
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
        };

    </script>


@endsection
