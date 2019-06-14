@extends('layouts.main')





@section('content')
    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Отправить таконы</h5>
            <?php
                $services = \App\CompaniesService::where('company_id', '=', auth()->user()->company_id)->get();
            ?>
            <br>
            <?php

            if($name != null){
                ?>
                <h5>{{ $name }}</h5>
<?php
            }else{
                ?>
            <button id="fav" class="btn btn-outline-info float-right mb-2" type="submit" onclick="saveGroup()">Добавить группу в избранное</button>
<?php
            }

            ?>


        </div>
    </div>

<?php
$array = explode(',', $ids);
$users = DB::table('mobile_users')->whereIn('id', $array)->get();


?>
    <br><br>
    <div class="col-md-12 mt-2">
        <?php
        if($cs_id == null){
            $cs = \App\CompaniesService::first();
        }else{
            $cs = \App\CompaniesService::where('id', $cs_id)->first();
        }

//dd($cs);
        ?>
        <table class="table table-bordered" id="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Пользователь</th>
                <th>Товар или услуга</th>
                <th>Количество таконов <input type="number" placeholder="По умолчанию" id="default"></th>
            </tr>
            </thead>
            <tbody>
            <form method="post" action="{{ route('send.takons') }}">


                @csrf
                @foreach($users as $user)
                    <?php

                    $rand = rand(1, 15000);

                    ?>

                    <tr>
                        <th>{{ $user->id }}</th>
                        <th>{{ $user->phone }}</th>
                        <th>
                            <select name="service_id[<?=$rand?>]" class="select">
                                <?php
                                if(count($services)< 1){
                                ?>
                                <option value="">У вас нет проибретенных услуг/товаров</option>
                                <?php
                                }
                                foreach ($services as $service){
                                $main_serv = \App\Service::where('id', '=', $service->service_id)->first();
                                ?>
                                <option value="<?=$service->id?>" <?php if($service->id == $cs->id) { ?> selected <?php } ?> > <?=$main_serv->name?> (кол-во: <?=$service->amount?>) </option>
                                <?php
                                }
                                ?>
                            </select>
                        </th>
                        <th>
                            <input type="text" value="<?=$ids?>" name="ids" hidden>
                            <input type="text" hidden name="id[<?=$rand?>]" value="<?=$user->id?>">
                            <input type="number" name="amount[<?=$rand?>]" placeholder="Количество таконов" required class="form-control">
                        </th>
                    </tr>
                @endforeach


                <button class="btn btn-success float-right mb-2" type="submit">Отправить</button>
            </form>

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

        function saveGroup(){

            swal({
                text: 'Введите имя для группы',
                content: "input",
                button: {
                    text: "Сохранить",
                    closeModal: false,
                },
            })
                .then(name => {
                    if (!name) throw null;


                    $.ajax({
                        type: "POST",
                        url: '/save-group',
                        data: {
                            name: name,
                            _token: "{{ csrf_token() }}",
                            ids : '<?=$ids?>'
                        },
                        success: function (data) {
                            if(data.success == true){
                                var button = document.getElementById('fav');
                                button.innerText = 'Сохранено';
                                $('#fav').prop('disabled', true);
                                swal("Успешно", "Группа пользователей сохранена в избранное", "success");

                            }

                        }
                    });


                });







        }

        $(document).ready(function () {

            $('#default').on('keyup paste',username_check);
            function username_check(){
                alert(this.value)
                setTimeout( function() {
                    document.getElementsByTagName('input').value = this.value;
                },100);
            }
        });
    </script>
@endsection
