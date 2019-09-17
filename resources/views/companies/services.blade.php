@extends('layouts.main')

@section('content')


    <div class="col-md-12 bg-transparent">
        <div class="float-left">
            <h5>Товары и услуги</h5>
        </div>
    </div>
    <div class="col-md-12" style="margin-top : 25px">
        <div class="float-right">
            <a href="{{ route('buy.service') }}"><button class="btn btn-success">Приобрести товар/услугу</button></a>
        </div>
    </div>
    <br><br>
    <div class="col-md-12" style="margin-top : 25px">

        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                    <tr>
                        <th >#</th>
                        <th >Название</th>
                        <th >Поставщик</th>
                        <th >Цена за единицу</th>
                        <th >Количество</th>
                        <th >Дата приобретения</th>
                        <th >Срок действия</th>

                        <th >Поделиться</th>

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
                serverSide: true,
                ajax: "{{ route('company.get.services') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'service', name: 'service' },
                    { data: 'partner', name: 'partner' },
                    { data: 'price', name: 'price'},
                    { data: 'amount', name: 'amount'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'return', name: 'return'},


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
