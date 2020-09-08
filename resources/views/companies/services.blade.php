@extends('layouts.main')

@section('content')


    <div class="col-md-12 bg-transparent">
        <div class="float-left">
            <h5>Товары и услуги</h5>
        </div>
        <hr>
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
                <table class="table table-bordered statTable" id="table">
                    <thead>
                    <tr>
                        <th class="table__title">#</th>
                        <th class="table__title">Название</th>
                        <th class="table__title">Поставщик</th>
                        <th class="table__title">Цена за единицу</th>
                        <th class="table__title">Количество</th>
                        <th class="table__title">Дата приобретения</th>
                        <th class="table__title">Срок действия</th>

                        <th class="table__title">Поделиться</th>

                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <style>
    </style>

    <script>
        $(document).ready(function () {
            $('#table').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                language: {
                    url: '{{asset('admin/bower_components/datatable/js/ru.locale.json')}}',
                },
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
                order: [[ 4, "desc" ]]
            });
        });


    </script>


@endsection
