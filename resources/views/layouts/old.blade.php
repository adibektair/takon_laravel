

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Takon</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    @toastr_css
    @toastr_js

    <!-- Custom styles for this template -->
    <link href="{{asset('css/simple-sidebar.css')}}" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
</head>

<body>

<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="float-right">
            @auth
            @else
                <a class="page-link" href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="d-flex" id="wrapper">


            <?php

                // Find amount of new orders
                $amount = \Illuminate\Support\Facades\DB::table('orders')->where('status', '=', 1)->count();
                $servicesCount = \Illuminate\Support\Facades\DB::table('services')->where('status', '=', 1)->count();

            ?>
            <!-- Sidebar -->
            <div class="bg-dark text-light border-right" id="sidebar-wrapper">
                <div class="sidebar-heading">Админ-панель</div>

                <div class="list-group list-group-flush" >
                    @if(auth()->user()->role_id == 1)
                        <a href="{{ route('partners_list') }}" class="list-group-item list-group-item-action bg-dark text-light">Партнеры</a>
                        <a href="/companies" class="list-group-item list-group-item-action bg-dark text-light">Юр. лица</a>
                        <a href="/mobile_users" class="list-group-item list-group-item-action bg-dark text-light">Пользователи</a>
                        <a href="/orders" class="list-group-item list-group-item-action bg-dark text-light">Заявки (транзакции) <?php if($amount > 0){?> <span class="badge-primary rounded pb-1 pt-1 pr-1 pl-1"><?=$amount?></span> <?php } ?>  </a>
                        <a href="{{ route('services.moderation') }}" class="list-group-item list-group-item-action bg-dark text-light">Заявки (товары/услуги) <?php if($servicesCount > 0){?> <span class="badge-primary rounded pb-1 pt-1 pr-1 pl-1"><?=$servicesCount?></span> <?php } ?>  </a>
                        <a href="{{ route('transactions.admin') }}" class="list-group-item list-group-item-action bg-dark text-light">Транзакции  </a>
                        <a href="{{ route('transactions.return') }}" class="list-group-item list-group-item-action bg-dark text-light">Транзакции по возвратам</a>

                    @elseif(auth()->user()->role_id == 2)

                        <a href="/profile" class="list-group-item list-group-item-action bg-dark text-light">Профиль</a>
                        <a href="/employees" class="list-group-item list-group-item-action bg-dark text-light">Сотрудники</a>
                        <a href="/services" class="list-group-item list-group-item-action bg-dark text-light">Товары и услуги</a>
                        <a href="{{ route('transactions.partner') }}" class="list-group-item list-group-item-action bg-dark text-light">Транзакции  </a>

                    @elseif(auth()->user()->role_id == 3)
                        <a href="{{ route('company.services') }}" class="list-group-item list-group-item-action bg-dark text-light">Товары и услуги</a>
                        <a href="/mobile_users" class="list-group-item list-group-item-action bg-dark text-light">Пользователи</a>
                        <a href="/return" class="list-group-item list-group-item-action bg-dark text-light">Возврат таконов</a>
                        <a href="{{ route('transactions.company') }}" class="list-group-item list-group-item-action bg-dark text-light">Транзакции  </a>
                        <a href="{{ route('transactions.return') }}" class="list-group-item list-group-item-action bg-dark text-light">Транзакции по возвратам</a>

                    @endif
                </div>
            </div>
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id="page-content-wrapper">

                <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                    <button class="navbar-toggler-icon" id="menu-toggle"></button>


                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>



                    <?php

                        $count = 0;
                    if(auth()->user()->role_id == 2){
                        $count = \Illuminate\Support\Facades\DB::table('notifications')
                            ->where('reciever_partner_id', '=', auth()->user()->partner_id)
                            ->where('read', '=', 0)->count();

                        $nots = \Illuminate\Support\Facades\DB::table('notifications')
                            ->where('reciever_partner_id', '=', auth()->user()->partner_id)
                            ->where('read', '=', 0)->orderBy('id', 'desc')->get();

                    }
                    else if(auth()->user()->role_id == 1){
                        $count = \Illuminate\Support\Facades\DB::table('notifications')
                            ->where('main', '=', true)
                            ->where('read', '=', 0)->count();

                        $nots = \Illuminate\Support\Facades\DB::table('notifications')
                            ->where('main', '=', true)
                            ->where('read', '=', 0)->orderBy('id', 'desc')->get();

                    }
                    else{
                        $count = \Illuminate\Support\Facades\DB::table('notifications')
                            ->where('reciever_company_id', '=', auth()->user()->company_id)
                            ->where('read', '=', 0)->count();

                        $nots =  \Illuminate\Support\Facades\DB::table('notifications')
                            ->where('reciever_company_id', '=', auth()->user()->company_id)
                            ->where('read', '=', 0)->orderBy('id', 'desc')->get();
                    }

                    ?>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">


                        <ul class="navbar-nav ml-auto mt-2 mt-lg-1 pr-4">

                            @if(auth()->user()->role_id == 2 || auth()->user()->role_id == 3 || auth()->user()->role_id == 1)
                                <div class="btn-group pr-4">
                                    <button class="btn  btn-info">Уведомления</button>
                                    <button class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown">
                                        <span class="badge-light rounded-circle pb-1 pt-1 pr-1 pl-1"><?=$count ?></span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">

                                        <?php
                                        foreach ($nots as $not){
                                            ?>
                                            <li>
                                                <div class="p-2" style="width: 350px">
                                                    <label class="text-<?=$not->status?>"><?=$not->title?></label>
                                                    <br>
                                                    <label  class="text-secondary"><?=$not->message?></label>

                                                </div>
                                                <hr>
                                            </li>
                                        <?php
                                        }
                                        ?>

                                    </ul>
                                </div>
                            @endif

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ auth()->user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/logout"> Выйти </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>

                <div class="container-fluid">

                    @toastr_render

                    @yield('content')
                </div>
            </div>
            <!-- /#page-content-wrapper -->

        </div>

        <script>
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            });
        </script>



    </div>


</div>


</body>
</html>
