@extends('layouts.main')

@section('content')
    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Заявки на создание товаров/услуг</h5>
        </div>
        <hr>
    </div>

    <div class="col-md-12 mt-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-bordered statTable" id="table">
                    <thead>
                    <tr>
                        <th class="table__title">#</th>
                        <th class="table__title">Партнер</th>
                        <th class="table__title">Товар/Услуга</th>
                        <th class="table__title">Количество</th>
                        <th class="table__title">Цена</th>
                        <th class="table__title">Срок в днях</th>
                        <th class="table__title">Управлять</th>

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
                ajax: "{{ route('moderation.services') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'partner', name: 'partner'},
                    {data: 'name', name: 'name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'price', name: 'price'},
                    {data: 'deadline', name: 'deadline'},
                    {data: 'moderate', name: 'moderate'},

                ]
            });
        });

    </script>




@endsection
