@extends('layouts.main')





@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Ваши таконы сейчас в наличии у следующих пользователей </h5>
        </div>
    </div>


    <br><br>
    <div class="col-md-12 mt-2">

        <table class="table" id="table">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Телефон</th>
                <th class="text-center">Услуга</th>
                <th class="text-center">Количество</th>
                <th class="text-center">Вернуть</th>
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
                ajax: "{{ route('get.return') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'phone', name: 'namephone' },
                    { data: 'service', name: 'service' },
                    { data: 'amount', name: 'amount'},
                    { data: 'return', name: 'return'},


                ]
            });
        });


    </script>

@endsection
