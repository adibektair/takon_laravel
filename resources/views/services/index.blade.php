@extends('layouts.main')





@section('content')
    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Товары и услуги</h5>
        </div>
        <hr>
    </div>

    <div class="col-md-12 mt-2 mb-3">
        <div class="float-right">
            <a href="{{ route('create.service') }}">
                <button class="btn btn-success">Добавить товар/услугу</button>
            </a>
        </div>
        <br>
    </div>



    <div class="col-md-12 mt-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-bordered statTable" id="table">
                    <thead>
                    <tr>
                        <th class="table__title">#</th>
                        <th class="table__title">Название</th>
                        <th class="table__title">Цена за единицу</th>
                        <th class="table__title">Срок действия в днях</th>
                        <th class="table__title">Редактировать</th>
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
        table {
            width: 100% !important;
            margin: 0 auto !important;

        }
    </style>
    <script>
        $(document).ready(function () {
            $('#table').DataTable({
                processing: true,
                responsive: true,
                language: {
                    url: '{{asset('admin/bower_components/datatable/js/ru.locale.json')}}',
                },
                serverSide: true,
                ajax: "{{ route('all.my_services') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'price', name: 'price'},
                    {data: 'deadline', name: 'deadline'},
                    {data: 'edit', name: 'edit'},

                    {data: 'checkbox', name: 'checkbox'},

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
