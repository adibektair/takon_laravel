@extends('layouts.main')





@section('content')
    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Товарыи услуги</h5>
        </div>
    </div>

    <div class="col-md-12 mt-2 mb-3">
        <div class="float-right">
            <a href="{{ route('create.service') }}"><button class="btn btn-success">Добавить товар/услугу</button></a>
        </div>
    </div>


    <br><br>
    <div class="col-md-12 mt-2">

        <table class="table table-bordered" id="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Название</th>
                <th>Цена за единицу</th>
                <th>Количество</th>
                <th>Поделиться</th>
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
                ajax: "{{ route('all.my_services') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'price', name: 'price' },
                    { data: 'amount', name: 'amount' },
                    { data: 'checkbox', name: 'checkbox' },

                    // {"data": {},
                    //     "mRender": function (data, type, row) {
                    //         return '<a href="/partner-share-services?id='+ data.id + '"><button class="btn btn-success">Поделиться</button></a>';
                    //     }
                    // }

                ]
            });
        });


    </script>

@endsection
