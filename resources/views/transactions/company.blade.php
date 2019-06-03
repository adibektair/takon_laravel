@extends('layouts.main')

@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Товары и услуги</h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">

        <table class="table" id="table">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Продавец</th>
                <th class="text-center">Услуга/Товар</th>
                <th class="text-center">Количество</th>
                <th class="text-center">Сумма</th>
                <th class="text-center">Остаток</th>
                <th class="text-center">Дата</th>
                <th class="text-center">Подробнее</th>

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
                ajax: "{{ route('transactions.company.all') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'partner', name: 'partner' },
                    { data: 'service', name: 'service'},
                    { data: 'amount', name: 'amount'},
                    { data: '1', name: '1'},
                    { data: '2', name: '2'},
                    { data: 'created_at', name: 'created_at'},
                    { data: '0', name: '0'},

                ],
            });
        });


    </script>


@endsection
