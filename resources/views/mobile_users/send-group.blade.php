@extends('layouts.main')





@section('content')
    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Отправить таконы</h5>
            <?php
            $services = \App\CompaniesService::where('company_id', '=', auth()->user()->company_id)->get();
            ?>
            <br>
            <a href="/add-user?id=<?=$_GET['id']?>"><button id="fav" class="btn btn-outline-info float-right mb-2" >Добавить пользователя</button></a>

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
                                <th>Удалить пользователя из группы</th>
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
                        <?php
                         $group_user = \App\GroupsUser::where('mobile_user_id', $user->id)->where('group_id', $group_id)->first();
                        ?>
                        <th>{{ $user->phone . ' ' . $group_user->username }}</th>
                                                <th> <button class="btn-danger" id="{{ $user->id }}">Удалить</button> </th>
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
                            <input type="number" name="amount[<?=$rand?>]" value="0"  placeholder="Количество таконов" required class="form-control">
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

        $(document).ready(function () {
            $( ".btn-danger" ).click(function() {
                $.ajax({
                    type: "POST",
                    url: '/remove-user',
                    data: {
                        group_id: '<?=$_GET['id']?>',
                        id: this.id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        if(data.success == true){
                            swal("Успешно", "Пользователь удален из группы", "success");
                            location.reload();
                        }

                    }
                });

            });


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
