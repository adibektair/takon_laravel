@extends('layouts.main')

@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Транзакции по возвратам</h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">

        <table class="table table-bordered" id="table">
            <thead>
            <tr>
                <th >#</th>
                <th >Юр. лицо</th>
                <th >Пользователь</th>
                <th >Услуга/Товар</th>
                <th >Количество</th>
                <th >Сумма</th>
                <th >Остаток</th>
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
                ajax: "{{ route('transactions.return.all') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'company', name: 'partner' },
                    { data: 'user', name: 'user' },
                    { data: 'service', name: 'service'},
                    { data: 'amount', name: 'amount'},
                    { data: '0', name: '0'},
                    { data: 'balance', name: 'balance'},
                    { data: 'created_at', name: 'created_at'}
                ],
            });
        });


    </script>


@endsection
