@extends('layouts.main')





@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Ваши таконы сейчас в наличии у следующих пользователей </h5>
        </div>
    </div>


    <br><br>
    <div class="col-md-12 mt-2">

        <div class="panel panel-default">
            <div class="panel-body">
        <table class="table table-bordered" id="table">
            <thead>
            <tr>
                <th >#</th>
                <th >Имя</th>
                <th >Телефон</th>
                <th >Услуга</th>
                <th >Количество</th>
                <th >Вернуть</th>
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
                responsive : true,
                language: {
                    url: '{{asset('admin/bower_components/datatable/js/ru.locale.json')}}',
                },
                serverSide: true,
                ajax: "{{ route('get.return') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'm_name', name: 'm_name' },
                    { data: 'phone', name: 'phone' },
                    { data: 'service', name: 'service' },
                    { data: 'amount', name: 'amount'},
                    { data: 'return', name: 'return'},


                ]
            });
        });


    </script>

@endsection
