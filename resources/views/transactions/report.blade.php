@extends('layouts.main')

@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Отчет</h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">

        <table class="table table-bordered" id="table">
            <thead>
            <tr>
                <th >#</th>
                <th >Отправитель</th>
                <th >Услуга/Товар</th>
                <th >Количество</th>
                <th >Получатель</th>
                <th >Дата</th>
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
                ajax: "{{ route('transactions.report1') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'sender', name: 'sender' },
                    { data: 'name', name: 'name'},
                    { data: 'amount', name: 'amount'},
                    { data: 'reciever', name: 'reciever'},
                    { data: 'created_at', name: 'created_at'},
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ],
            });
        });


    </script>


@endsection
