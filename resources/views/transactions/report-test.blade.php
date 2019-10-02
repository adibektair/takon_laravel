@extends('layouts.main')

@section('content')




    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Отчет</h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <form>
                    <div class="row" style="margin: 10px">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>C какого числа:</label>
                                <input class="form-control" name="min" id="min" type="date">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>По какое число:</label>
                                <input class="form-control" name="max" id="max" type="date">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>Услуга:</label>

                            <select class="form-control" name="service" id="service">
                                <option value="">Не выбрано</option>
                                @foreach($services as $s)
                                    <option value="{{$s->id}}">{{$s->name}}</option>
                                @endforeach
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-6">

                            <label>Наименования:</label>

                            <select class="form-control" name="mobileUser" id="mobileUser">
                                <option value="">Не выбрано</option>
                                @foreach($mobileUsers as $mu)
                                    <option value="{{$mu->id}}">{{$mu->phone}} {{$mu->name}}</option>
                                @endforeach
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </form>


                <table class="table table-bordered" id="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Услуга/Товар</th>
                        <th>Наименование</th>
                        <th>Имя</th>
                        <th>Получено</th>
                        <th>Отправлено</th>
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
                language: {
                    url: '{{asset('admin/bower_components/datatable/js/ru.locale.json')}}',
                },
                serverSide: true,
                ajax: {
                    url: "{{ route('transactions.testReport') }}",
                    "data": function (d) {
                        d.minDate = $('#min').val();
                        d.maxDate = $('#max').val();
                        d.serviceId = $('#service').val();
                        d.mobileUserId = $('#mobileUser').val();
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'service_name', name: 'service_name'},
                    {data: 'sender', name: 'sender'},
                    {data: 'sender_name', name: 'sender_name'},
                    {data: 'sent', name: 'sent'},
                    {data: 'received', name: 'received'},
                    {data: 'created_at', name: 'created_at'},
                ],
                dom: 'Bfrltip',
                buttons: {
                    buttons: [
                        {extend: 'excel', className: 'btn btn-success', action: newExportAction}
                    ]
                },
            });


            $('#min, #max, #service, #mobileUser').change(function () {
                table.draw();
            });

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
