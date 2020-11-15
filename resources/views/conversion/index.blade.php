@extends('layouts.main')
@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Конвертация</h5>
        </div>


    </div>
    <div class="col-md-12">
        <div class="float-md-left">
            <a href="{{ route('conversion.create') }}"><button class="btn btn-info">Добавить новую конвертацию</button></a>
        </div>
    </div>

    <div class="col-md-12 mt-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-bordered statTable" id="table">
                    <thead>
                    <tr>
                        <th class="table__title"></th>
                        <th class="table__title">Услуга 1</th>
                        <th class="table__title">Услуга 2</th>
                        <th class="table__title">Коэф</th>
                        <th class="table__title">Создан</th>
                        <th class="table__title"></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <style>
        table{
            width: 100% !important;
            margin: 0 auto !important;

        }
    </style>
    <script>
        $(document).ready(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    url: '{{asset('admin/bower_components/datatable/js/ru.locale.json')}}',
                },
                responsive: true,
                ajax: "{{ route('conversion.all') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name1', name: 'name1'},
                    {data: 'name2', name: 'name2'},
                    {data: 'coefficient', name: 'coefficient'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'checkbox', name: 'checkbox'},
                ]
            });
        });


    </script>
@endsection
