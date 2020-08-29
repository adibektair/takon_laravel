@extends('layouts.main')





@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Cотрудники </h5>
        </div>
        <hr>
    </div>

    <div class="col-md-12 mt-2 mb-3">
        <div class="float-right">
            <a href="{{ route('create.employee') }}">
                <button class="btn btn-success">Добавить сотрудника</button>
            </a>
        </div>
        <br>
    </div>

    <div class="col-md-12 mt-2">

        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-bordered statTable" id="table">
                    <thead>
                    <tr>
                        <th class="table__title">#</th>
                        <th class="table__title">Имя</th>
                        <th class="table__title">email</th>
                        <th class="table__title">Создан</th>
                        <th class="table__title"></th>
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
        table {
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
                ajax: "{{ route('all.employees') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'edit', name: 'edit'},
                    {data: 'qr', name: 'qr'},
                ]
            });
        });


    </script>

@endsection
