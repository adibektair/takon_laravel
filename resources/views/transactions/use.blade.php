@extends('layouts.main')

@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Использованные таконы</h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">
        <label>Товар/услуга</label>
        <select id="statusFilter">
            <option >Не выбрано</option>
        <?php

            $services = \App\Service::all();
            foreach ($services as $company){
                ?>
                <option value="<?=$company->id?>"><?=$company->name?></option>
<?php
            }
            ?>
        </select>

        <table class="table table-bordered" id="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Пользователь</th>
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


    <style>
        table{
            width: 100% !important;
            margin: 0 auto !important;

        }
    </style>

    <script>
        $(document).ready(function () {
            var dtListUsers =  $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('transactions.use.all') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'sender', name: 'sender' },
                    { data: 'service', name: 'service'},
                    { data: 'amount', name: 'amount'},
                    { data: 'reciever', name: 'reciever'},
                    { data: 'created_at', name: 'created_at'},
                ],
                dom: 'Bfrtip',
                buttons : [ {
                    extend : 'excel',
                    action: newExportAction

                } ]
            });
            $('#statusFilter').on('change', function(){
                var filter_value = $(this).val();
                var new_url = '/transactions/use/all?id='+filter_value;
                dtListUsers.ajax.url(new_url).load();
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
