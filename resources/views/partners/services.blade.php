@extends('layouts.main')

@section('content')
    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Товары и услуги  <?=$name?></h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">

        <table class="table" id="table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Название</th>
                    <th class="text-center">Цена за единицу</th>
                    <th class="text-center">Количество в наличии</th>
                    <th>Приобрести</th>
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
                ajax: "/get-partners-services?id=<?=$id?>",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'price', name: 'price' },
                    { data: 'amount', name: 'amount' },
                    {"mData": {},
                        "mRender": function (data, type, row) {

                            return '<a href="/buy-current-service?id='+ data.id + '"><button class="btn btn-success">Приобрести</button></a>';
                        }
                    },

                ]
            });
        });
    </script>

@endsection
