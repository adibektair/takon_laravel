@extends('layouts.main')





@section('content')
    {{--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">--}}
    <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

    <div class="col-md-12 mt-2 mb-3">
        <div class="float-right">
            <a href="{{ route('add.partner') }}"><button class="btn btn-success">Добавить партнера</button></a>
        </div>
    </div>


    <br><br>
    <div class="col-md-12 mt-2">

        <table class="table" id="table">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Имя</th>
                <th class="text-center">Телефон</th>
                <th class="text-center">Адрес</th>
                <th class="text-center">Создан</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
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
                ajax: "{{ route('all.partners') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'phone', name: 'phone' },
                    { data: 'address', name: 'address'},
                    { data: 'created_at', name: 'created_at'},


                ]
            });
        });


    </script>

@endsection
