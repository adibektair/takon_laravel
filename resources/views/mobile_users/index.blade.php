@extends('layouts.main')





@section('content')
    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Пользователи</h5>
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
                ajax: "{{ route('all.mobile_users') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'phone', name: 'phone' },
                    { data: 'created_at', name: 'created_at'},


                ]
            });
        });


    </script>

@endsection
