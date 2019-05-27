@extends('layouts.main')

@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Товары и услуги</h5>
        </div>
    </div>
    <div class="col-md-12 mt-2 mb-3">
        <div class="float-right">
            <a href="{{ route('buy.service') }}"><button class="btn btn-success">Приобрести товар/услугу</button></a>
        </div>
    </div>
    <br><br>
    <div class="col-md-12 mt-2">

        <table class="table" id="table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Название</th>
                    <th class="text-center">Поставщик</th>
                    <th class="text-center">Цена за единицу</th>
                    <th class="text-center">Количество</th>
                    <th class="text-center">Дата приобретения</th>
                    <th class="text-center">Поделиться</th>

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
                ajax: "{{ route('company.get.services') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'service', name: 'service' },
                    { data: 'partner', name: 'partner' },
                    { data: 'price', name: 'price'},
                    { data: 'amount', name: 'amount'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'checkbox', name: 'checkbox'},
                    // {
                    //     "mData": {},
                    //     "mRender": function (data, type, row) {
                    //
                    //         return '<a href="/share-services?id='+ data.id + '"><button class="btn btn-success">Поделиться</button></a>';
                    //     }
                    // },
                ],
            });
        });


    </script>


@endsection