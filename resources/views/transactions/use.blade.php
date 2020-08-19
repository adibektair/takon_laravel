@extends('layouts.main')

@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Использованные таконы</h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">



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
                        <?php
                        $services = \App\Service::all();
                        ?>
                        <td>Услуга:</td>
                        <td>
                            <select class="form-control" name="service" id="service">
                                <option value="">Не выбрано</option>

                                <?php
                                foreach ($services as $s){
                                ?>
                                <option value="<?=$s->id?>"><?=$s->name?></option>
                                <?php
                                }
                                ?>
                                <option value=""></option>
                            </select>
                        </td>
                    </tr>
                    <tr>

                        <td>Тип транзакции:</td>
                        <td>
                            <select class="form-control" name="type" id="type">
                                <option value="">Не выбрано</option>
                                <option value="3">Использование таконов</option>
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
                        <th>Имя</th>
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
                ajax: {
                    url: "{{ route('transactions.use.all') }}",
                    "data": function (d) {
                        d.minDate = $('#min').val();
                        d.maxDate = $('#max').val();
                        d.service = $('#service').val();
                        d.type = $('#type').val();
                    },
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'sender', name: 'sender'},
                    {data: 'username', name: 'username'},
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
                initComplete: function () {
                    this.api().columns().every( function () {
                        var column = this;
                        var select = $('<select class="form-control"><option value=""></option></select>')
                            .appendTo( $(column.footer()).empty() )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );

                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );
                }
            });

            $('#min, #max, #service, #type').change(function () {
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
