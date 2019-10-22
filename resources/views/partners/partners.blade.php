@extends('layouts.main')





@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Партнеры</h5>
        </div>
    </div>

    <div class="col-md-12 mt-2 mb-3">
        <div class="float-right">
            <a href="{{ route('add.partner') }}">
                <button class="btn btn-success">Добавить партнера</button>
            </a>
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
                        <th>Имя</th>
                        <th>Телефон</th>
                        <th>email</th>
                        <th>Адрес</th>
                        <th>Создан</th>
                        <th>Локации</th>
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
