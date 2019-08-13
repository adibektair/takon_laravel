@extends('layouts.main')


@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <?php
                $group = \App\Group::where('id', $id)->first();
            ?>
            <h5>Добавить пльзователей в группу</h5>

            <br><br><br>
        </div>

    </div>

    <br><br>
    <div class="col-md-12 mt-2">

        <div class="col-md-6">
            <label class="text-black">Введите номер телефона для поиска пользователя в формате: 77075554797</label>
            <input id="phone" type="number" class="form-control" placeholder="77075554797">
            <br>
            <button onclick="search()" class="btn btn-info">ПОИСК</button>
        </div>
        <div class="col-md-6">
            <form action="{{ route('add.users.group') }}" method="post">
                @csrf
                <input type="text" hidden name="group_id" value="<?=$group->id?>">
                <label class="text-black">Пользователи:</label>
                <div id="users" class="form-group">
                </div>
                <button class="btn btn-success" type="submit">Добавить</button>
            </form>
        </div>

    </div>


    <script>
        function search() {
            var value = document.getElementById("phone").value;
            $.ajax({
                type: "POST",
                url: '/search-user',
                data: {
                    value: value,
                    _token: "{{ csrf_token() }}",
                    group_id: '<?=$group->id?>'
                },
                success: function (data) {
                    if (data.success == true){
                        swal("Успешно", "Пользователь добавлен!", "success");
                        document.getElementById('phone').value = "";
                        var index = Math.floor(Math.random() * 10000);
                        var div = document.createElement('div');
                        div.classList.toggle('form-group');

                        var input = document.createElement('input');
                        input.classList.toggle('form-group');
                        input.name = 'id[' + data.id +']';
                        input.value = data.phone;
                        var input2 = document.createElement('input');
                        input2.classList.toggle('form-group');
                        input2.name = 'name[' + data.id +']';
                        input2.placeholder = 'Введите имя';
                        input2.required = true;

                        div.appendChild(input);
                        div.appendChild(input2);
                        document.getElementById('users').appendChild(div);

                    }else{
                        swal("Ошибка", "Пользователь не найден", "error");
                    }
                }
            });
        }

    </script>

@endsection
