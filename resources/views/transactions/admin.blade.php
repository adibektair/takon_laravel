@extends('layouts.main')

@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Транзакции</h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Отправитель</th>
                        <th>Получатель</th>
                        <th>Услуга/Товар</th>
                        <th>Количество</th>
                        <th>Сумма</th>
                        <th>Остаток</th>
                        <th>Дата</th>
                        <th>Подробнее</th>

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
            $('#table').DataTable({
                dom: 'Bfrtip',
                buttons: {
                    buttons: [
                        { extend: 'copy', className: 'btn btn-warning' },
                        { extend: 'excel', className: 'btn btn-success' }
                    ]
                },
                processing: true,
                responsive: true,
                language: {
                    url: '{{asset('admin/bower_components/datatable/js/ru.locale.json')}}',
                },
                serverSide: true,
                ajax: "{{ route('transactions.admin.all') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'partner', name: 'partner'},
                    {data: 'company', name: 'company'},
                    {data: 'service', name: 'service'},
                    {data: 'amount', name: 'amount'},
                    {data: '1', name: '1'},
                    {data: '2', name: '2'},

                    {data: 'created_at', name: 'created_at'},
                    {data: '0', name: '0'},

                ],
            });
        });


    </script>


@endsection
