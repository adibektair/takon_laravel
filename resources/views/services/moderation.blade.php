@extends('layouts.main')

@section('content')
    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Заявки на создание товаров/услуг</h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">

        <table class="table table-bordered" id="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Партнер</th>
                <th>Товар/Услуга</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Срок в днях</th>
                <th>Управлять</th>

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
                ajax: "{{ route('moderation.services') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'partner', name: 'partner' },
                    { data: 'name', name: 'name' },
                    { data: 'amount', name: 'amount' },
                    { data: 'price', name: 'price' },
                    { data: 'deadline', name: 'deadline'},
                    { data: 'moderate', name: 'moderate' },

                ]
            });
        });

    </script>




@endsection
