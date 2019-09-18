@extends('layouts.main')

@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Транзакции по возвратам</h5>
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
                        <th>Юр. лицо</th>
                        <th>Пользователь</th>
                        <th>Услуга/Товар</th>
                        <th>Количество</th>
                        <th>Сумма</th>
                        <th>Остаток</th>
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
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ],
            });
        });


    </script>


@endsection
