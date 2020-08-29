@extends('layouts.main')

@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Транзакции по возвратам</h5>
        </div>
        <hr>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-bordered statTable" id="table">
                    <thead>
                    <tr>
                        <th class="table__title">#</th>
                        <th class="table__title">Юр. лицо</th>
                        <th class="table__title">Пользователь</th>
                        <th class="table__title">Услуга/Товар</th>
                        <th class="table__title">Количество</th>
                        <th class="table__title">Сумма</th>
                        <th class="table__title">Остаток</th>
                        <th class="table__title">Дата</th>
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
                processing: true,
                responsive: true,
                language: {
                    url: '{{asset('admin/bower_components/datatable/js/ru.locale.json')}}',
                },
                serverSide: true,
                ajax: "{{ route('transactions.return.all') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'company', name: 'partner'},
                    {data: 'user', name: 'user'},
                    {data: 'service', name: 'service'},
                    {data: 'amount', name: 'amount'},
                    {data: '0', name: '0'},
                    {data: 'balance', name: 'balance'},
                    {data: 'created_at', name: 'created_at'}
                ],
                dom: 'Bfrltip',
                buttons: {
                    buttons: [
                        { extend: 'copy', className: 'btn btn-warning' },
                        { extend: 'excel', className: 'btn btn-success' }
                    ]
                },
            });
        });


    </script>


@endsection
