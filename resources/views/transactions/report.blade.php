@extends('layouts.main')

@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Отчет</h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">
        <table border="0" cellspacing="5" cellpadding="5">
            <tbody>
            <tr>
                <td>Minimum Date:</td>
                <td><input name="min" id="min" type="date"></td>
            </tr>
            <tr>
                <td>Maximum Date:</td>
                <td><input name="max" id="max" type="date"></td>
            </tr>
            </tbody>
        </table>
        <table class="table table-bordered" id="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Отправитель</th>
                <th>Услуга/Товар</th>
                <th>Количество</th>
                <th>Получатель</th>
                <th>Дата</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>


    <style>
        table {
            width: 100% !important;
            margin: 0 auto !important;

        }
    </style>

    <script>
        $(document).ready(function () {

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var min = $('#min').datepicker('getDate');
                    var max = $('#max').datepicker('getDate');
                    var startDate = new Date(data[4]);
                    if (min == null && max == null) return true;
                    if (min == null && startDate <= max) return true;
                    if (max == null && startDate >= min) return true;
                    if (startDate <= max && startDate >= min) return true;
                    return false;
                }
            );

            $('#min').datepicker({
                onSelect: function () {
                    table.draw();
                }, changeMonth: true, changeYear: true
            });
            $('#max').datepicker({
                onSelect: function () {
                    table.draw();
                }, changeMonth: true, changeYear: true
            });

            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('transactions.report1') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'sender', name: 'sender'},
                    {data: 'name', name: 'name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'reciever', name: 'reciever'},
                    {data: 'created_at', name: 'created_at'},
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ],
            });

            $('#min, #max').change(function () {
                table.draw();
            });
        });


    </script>


@endsection
