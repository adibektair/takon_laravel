@extends('layouts.main')


@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <?php
                $group = \App\Group::where('id', $id)->first();
            ?>
            <h5>Пользователи</h5>
            <h6>Выберите пользователей, которых хотите  добавить в группу <?=$group->id?></h6>

            <br><br><br>
        </div>

    </div>

    <br><br>
    <div class="col-md-12 mt-2">

        <table class="table table-bordered" id="table">
            <thead>
            <tr>

                <th > Имя</th>
                <th > Телефон</th>
                <th > Создан</th>
                <th > Добавить</th>
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


        $(function() {
            var array = [];
            var names = [];
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/add-user-group?id=<?=$id?>",
                stateSave: true,

                columns: [

                    { data: 'name', name: 'name' },
                    { data: 'phone', name: 'phone' },
                    { data: 'created_at', name: 'created_at'},
                    { data: 'add', name: 'add'},

                ],

            });

            //$('#table tbody').on('change', 'input[type="checkbox"]', function(){
            $('#table tbody').on('click', 'button', function(){
                $.ajax({
                    type: "POST",
                    url: '/add-user-finish',
                    data: {
                        id: this.id,
                        group_id: '<?=$id?>',
                        _token: "{{ csrf_token() }}",
                    },
                    success: function (data) {
                        swal("Успешно", "Пользователь добавлен", "success");
                        location.reload();

                    }
                });

            });



        });



        setTimeout(function(){
            $('input').change(function() {

                $.ajax({
                    type: "POST",
                    url: '/set-name',
                    data: {
                        name: this.value,
                        id: this.id,
                        _token: "{{ csrf_token() }}",

                    },
                    success: function (data) {

                    }
                });

            });
        }, 3000);


    </script>

@endsection
