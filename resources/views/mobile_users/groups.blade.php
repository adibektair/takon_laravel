@extends('layouts.main')




@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Сохраненные группы пользователей</h5>
            <br>
            <a href="{{ route('groups') }}">
                <button class="btn btn-outline-info">Избранное</button>
            </a>
            <br><br><br>
        </div>

    </div>



    <br><br>
    <div class="col-md-12 mt-2">

        <table class="table table-bordered" id="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Группа</th>
                <th>Создан</th>
                <th></th>
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


        $(function() {

            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get.groups') }}",
                stateSave: true,

                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'created_at', name: 'created_at'},
                    { data: 'checkbox', name: 'checkbox' },
                ],

            });


        });



    </script>

@endsection
