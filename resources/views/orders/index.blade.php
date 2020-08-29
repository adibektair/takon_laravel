@extends('layouts.main')

@section('content')
    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Заявки</h5>
        </div>
        <hr>
    </div>

    <div class="col-md-12 mt-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-bordered statTable" id="table">
                    <thead>
                    <tr>
                        <th class="table__title">#</th>
                        <th class="table__title">Покупатель</th>
                        <th class="table__title">Партнер(продавец)</th>
                        <th class="table__title">Товар/Услуга</th>
                        <th class="table__title">Количество и сумма</th>
                        <th class="table__title">Дата создания</th>
                        <th class="table__title">Cтатус</th>
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
                language: {
                    url: '{{asset('admin/bower_components/datatable/js/ru.locale.json')}}',
                },
                responsive: true,
                serverSide: true,
                ajax: "{{ route('all.orders') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'username', name: 'username'},
                    {data: 'partner', name: 'partner'},
                    {data: 'service', name: 'service'},
                    {data: 'summ', name: 'summ'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'status', name: 'status'},
                    // {"mData": {},
                    //     "mRender": function (data, type, row) {
                    //         return '<label class="text-semibold">'+ data.username + ' ('+ data.userphone +')</label>';
                    //     }
                    // },
                    // {"mData": {},
                    //     "mRender": function (data, type, row) {
                    //         return '<label class="text-semibold">'+ data.partner + ' ('+ data.partner_phone +')</label>';
                    //     }
                    // },
                    //
                    // { data: 'service', name: 'service'},
                    // {"mData": {},
                    //     "mRender": function (data, type, row) {
                    //         return '<label class="text-semibold">'+ data.amount + ' на сумму '+ data.cost +' тенге</label>';
                    //     }
                    // },
                    // { data: 'created_at', name: 'created_at'},
                    // {"mData": {},
                    //     "mRender": function (data, type, row) {
                    //     if(data.status == 1){
                    //         return '<a href="/orders/view?id='+ data.id +'"><button     class="btn btn-success">Управлять</button></a>';
                    //     }else if(data.status == 2){
                    //         return '<label class="text-semibold">Отклонено</label>';
                    //     }else{
                    //         return '<label class="text-semibold">Подтверждено</label>';
                    //     }
                    //
                    //     }
                    // },
                ]
            });
        });

    </script>




@endsection
