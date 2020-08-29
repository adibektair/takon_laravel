@extends('layouts.main')

@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Поиск (номер телефона: {{ $phone }})</h5>
        </div>
        <hr>
    </div>

    <div class="col-md-12 mt-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-bordered statTable" id="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Отправитель</th>
                        <th>Получатель</th>
                        <th>Услуга/Товар</th>
                        <th>Количество</th>
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
                ajax: "/transactions-search-make?phone={{$phone}}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'sender', name: 'sender'},
                    {data: 'reciever', name: 'reciever'},
                    {data: 'service', name: 'service'},
                    {data: 'amount', name: 'amount'},
                    {data: 'created_at', name: 'created_at'}
                ],
            });
        });


    </script>


@endsection
