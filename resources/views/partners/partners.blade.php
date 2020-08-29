@extends('layouts.main')





@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Партнеры</h5>
        </div>
        <hr>
    </div>

    <div class="col-md-12 mt-2 mb-3">
        <div class="float-right">
            <a href="{{ route('add.partner') }}">
                <button class="btn btn-success">Добавить партнера</button>
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
                        <th class="table__title">Телефон</th>
                        <th class="table__title">email</th>
                        <th class="table__title">Адрес</th>
                        <th class="table__title">Создан</th>
                        <th class="table__title">Локации</th>
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
                buttons: {
                    buttons: [
                        { extend: 'copy', className: 'btn btn-warning' },
                        { extend: 'excel', className: 'btn btn-success' },
                        { extend: 'pdf', className: 'btn btn-primary' },
                    ]
                },
                dom: 'Bfrtip',

                processing: true,
                responsive: true,
                language: {
                    url: '{{asset('admin/bower_components/datatable/js/ru.locale.json')}}',
                },
                serverSide: true,
                ajax: "{{ route('all.partners') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'phone', name: 'phone'},
                    {data: 'email', name: 'email'},
                    {data: 'address', name: 'address'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'locations', name: 'locations'},


                ]
            });
        });


    </script>

@endsection
