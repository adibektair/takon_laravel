<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Takon</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{asset('css/popModal.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/popModal.css')}}" rel="stylesheet">
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('js/popModal.js')}}"></script>
    <script src="{{asset('js/popModal.min.js')}}"></script>

    <script src="{{asset('admin/bower_components/sweetalert/sweetalert.min.js')}}"></script>
    @toastr_css
    @toastr_js

    <!-- Custom styles for this template -->
    <link href="{{asset('css/simple-sidebar.css')}}" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    {{--<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">--}}

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <link rel="stylesheet" href="{{asset("admin/bower_components/bootstrap/dist/css/bootstrap.min.css")}}">
    <link rel="stylesheet" href="{{asset("admin/bower_components/font-awesome/css/font-awesome.min.css")}}">

    <link rel="stylesheet" href="{{asset("admin/dist/css/AdminLTE.min.css")}}">
    <link rel="stylesheet" href="{{asset("admin/dist/css/skins/_all-skins.min.css")}}">
    <link rel="stylesheet" href="{{asset("css/jquery.select.css")}}">
    <link href="{{ asset('css/toastr.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset("admin/bower_components/datatable/css/dataTables.bootstrap.min.css")}}">
    <link rel="stylesheet" href="{{asset("admin/bower_components/datatable/css/responsive.bootstrap.min.css")}}">
    <link rel="stylesheet" href="{{asset("admin/bower_components/datatable/css/scroller.bootstrap.min.css")}}">



    {{--    <link rel="stylesheet" type="text/css"--}}
    {{--          href="{{asset("admin/bower_components/datatable/css/dataTables.bootstrabootstrap.css-}}--}}

    <link rel="stylesheet" type="text/css" href="{{asset("admin/bower_components/daterangepicker/daterangepicker.css")}}"/>
    <link href="{{asset("admin/bower_components/select2/select2.css")}}" rel="stylesheet"/>

    <style>
        .panel {
            padding: 10px;
        }
    </style>
    @yield('styles')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <a class="logo">
            <span class="logo-mini"><b>T</b>ORG</span>
            <span class="logo-lg"><b>TAKON.</b>ORG</span>
        </a>

        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user-circle" style="color:white"></i>
                            <span class="hidden-xs">         {{ auth()->user()->name }}</span>

                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <i class="fa fa-home fa-3x" style="color:white"></i>
                                <p>
                                    <a class="dropdown-item" href="/logout"> Выйти </a>

                                </p>
                            </li>


                            <li class="user-footer">
                                {{--<div class="pull-left">--}}
                                    {{--<a href="{{route('account.index')}}"  class="btn btn-default btn-flat">Аккаунт</a>--}}
                                {{--</div>--}}
                                <div class="pull-right">
                                    <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Выход
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left text-white">
                    <i class="fa fa-user-circle fa-2x " style="color:white"></i>
                </div>
                <div class="pull-left info">
                    <p>{{Auth::user()->first_name. ' '. Auth::user()->last_name}}</p>
                </div>
            </div>
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">Главная</li>
                <li>
                    <a href="{{route('home')}}">
                        <i class="fa fa-home"></i> <span>Главная</span>
                    </a>
                </li>
                <?php

                // Find amount of new orders
                $amount = \Illuminate\Support\Facades\DB::table('orders')->where('status', '=', 1)->count();
                $servicesCount = \Illuminate\Support\Facades\DB::table('services')->where('status', '=', 1)->count();

                ?>

                <li class="header">Настройки</li>


                @if(auth()->user()->role_id == 1)
                    <li>
                        <a href="{{ route('partners_list') }}">
                            <i class="fa fa-handshake-o"></i>
                            <span>Партнеры</span>
                        </a>

                    </li>
                    <li>
                        <a href="/companies">
                            <i class="fa fa-suitcase"></i>
                            <span>
                                Юр. лица
                            </span>
                        </a>

                    </li>
                    <li>
                        <a href="/mobile_users">
                            <i class="fa fa-users"></i>
                            <span>
                                Пользователи
                            </span>
                        </a>

                    </li>
                <?php if(auth()->user()->id == 1){
                    ?>

                    <li>
                        <a href="/orders">
                            <i class="fa fa-envelope-square"></i>
                            <span>
                                Заявки (транзакции) <?php if($amount > 0){?> <span
                                        class="badge badge-primary rounded pb-1 pt-1 pr-1 pl-1"><?=$amount?></span> <?php } ?>
                            </span>
                        </a>

                    </li>

                    <?php

                    }
                    ?>

                    <li>
                        <a href="{{ route('services.moderation') }}">
                            <i class="fa fa-envelope-open"></i>
                            <span>
                                Заявки
                            (товары/услуги) <?php if($servicesCount > 0){?> <span
                                        class="badge badge-primary rounded pb-1 pt-1 pr-1 pl-1"><?=$servicesCount?></span> <?php } ?>
                            </span>
                        </a>

                    </li>
                    <li>
                        <a href="{{ route('transactions.admin') }}">
                            <i class="fa fa-exchange"></i>
                            <span>
                                Транзакции
                            </span>
                        </a>

                    </li>
                    <li>
                        <a href="{{ route('transactions.payments') }}">
                            <i class="fa fa-external-link"></i>
                            <span>
                                Транзакции по онлайн оплате
                            </span>
                        </a>

                    </li>

                    <li>
                        <a href="{{ route('transactions.use') }}">
                            <i class="fa fa-hand-grab-o"></i>
                            <span>
                                Использование таконов
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.search') }}">
                            <i class="fa fa-search"></i>
                            <span>
                                Поиск по транзакциям
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.return') }}">
                            <i class="fa fa-arrow-circle-left"></i>
                            <span>
                                Транзакции по возвратам
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('report.by.company') }}">
                            <i class="fa fa-file-text"></i>
                            <span>
                                Отчеты тест
                            </span>
                        </a>
                    </li>


                @elseif(auth()->user()->role_id == 4)
                    <li>
                        <a href="{{ route('partners_list') }}">
                            <i class="fa fa-handshake-o"></i>
                            <span>Партнеры</span>
                        </a>

                    </li>
                    <li>
                        <a href="/companies">
                            <i class="fa fa-suitcase"></i>
                            <span>
                                Юр. лица
                            </span>
                        </a>

                    </li>
                    <li>
                        <a href="/mobile_users">
                            <i class="fa fa-users"></i>
                            <span>
                                Пользователи
                            </span>
                        </a>

                    </li>
                    <li>
                        <a href="{{ route('transactions.admin') }}">
                            <i class="fa fa-exchange"></i>
                            <span>
                                Транзакции
                            </span>
                        </a>

                    </li>
                    <li>
                        <a href="{{ route('transactions.payments') }}">
                            <i class="fa fa-external-link"></i>
                            <span>
                                Транзакции по онлайн оплате
                            </span>
                        </a>

                    </li>

                    <li>
                        <a href="{{ route('transactions.use') }}">
                            <i class="fa fa-hand-grab-o"></i>
                            <span>
                                Использование таконов
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.search') }}">
                            <i class="fa fa-search"></i>
                            <span>
                                Поиск по транзакциям
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.return') }}">
                            <i class="fa fa-arrow-circle-left"></i>
                            <span>
                                Транзакции по возвратам
                            </span>
                        </a>
                    </li>


                @elseif(auth()->user()->role_id == 2)
                    <li>
                        <a href="/profile">
                            <i class="fa fa-user"></i>
                            <span>Профиль</span>
                        </a>

                    </li>
                    <li>
                        <a href="/employees">
                            <i class="fa fa-users"></i>
                            <span>Сотрудники</span>
                        </a>

                    </li>
                    <li>
                        <a href="/services">
                            <i class="fa fa-book"></i>
                            <span>Товары и услуги</span>
                        </a>

                    </li>
                    <li>
                        <a href="{{ route('transactions.partner') }}">
                            <i class="fa fa-exchange"></i>
                            <span>
                                Транзакции
                            </span>
                        </a>

                    </li>

                @elseif(auth()->user()->role_id == 3)
                    <li>
                        <a href="{{ route('company.services') }}">
                            <i class="fa fa-book"></i>
                            <span>Товары и услуги</span>
                        </a>

                    </li>
                    <li>
                        <a href="/groups">
                            <i class="fa fa-group"></i>
                            <span>Пользователи</span>
                        </a>

                    </li>
                    <li>
                        <a href="/return">
                            <i class="fa fa-dollar"></i>
                            <span>
                                Возврат таконов
                            </span>
                        </a>

                    </li>
                    <li>
                        <a href="{{ route('transactions.company') }}">
                            <i class="fa fa-exchange"></i>
                            <span>Транзакции</span>
                        </a>

                    </li>
                    <li>
                        <a href="{{ route('transactions.return') }}">
                            <i class="fa fa-arrow-circle-left"></i>
                            <span>
                                Транзакции по возвратам
                            </span>
                        </a>

                    </li>

                    <li>
                        <a href="{{ route('report') }}">
                            <i class="fa fa-list">

                            </i>
                            <span>
                                Отчет
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reportTest') }}">
                            <i class="fa fa-list">

                            </i>
                            <span>
                                Отчет тест
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('report.by.company') }}">
                            <i class="fa fa-file-text"></i>
                            <span>
                                Отчет комапнии
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('transactions.use') }}">
                            <i class="fa fa-hand-grab-o"></i>
                            <span>
                                Использование таконов
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('transactions.search') }}">
                            <i class="fa fa-search"></i>
                            <span>
                                Поиск по транзакциям
                            </span>
                        </a>
                    </li>


                @elseif(auth()->user()->role_id == 4)

                    <li>
                        <a href="{{ route('transactions.cashier.history') }}">
                            <i class="fa fa-search"></i>
                            <span>
                                Архив
                            </span>
                        </a>
                    </li>



                @endif

            </ul>
        </section>
    </aside>
    <div class="content-wrapper">
        <section class="content">

            <div class="container-fluid">

                @toastr_render

                @yield('content')

            </div>
        </section>
    </div>
    <footer class="main-footer">
        All rights
        reserved {{date('Y')}}. TAKON.ORG
    </footer>
</div>
<script data-require="jqueryui@*" data-semver="1.10.0" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.0/jquery-ui.js"></script>

{{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>--}}
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>--}}
{{--<script defer src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>--}}


<script type="text/javascript" src="{{asset('admin/bower_components/datatable/js/jquery.datatables.min.js')}}"defer></script>
<script type="text/javascript" src="{{asset('admin/bower_components/datatable/js/dataTables.bootstrap.min.js')}}"defer></script>
<script type="text/javascript" src="{{asset('admin/bower_components/datatable/js/dataTables.fixed-header.min.js')}}"defer></script>
<script type="text/javascript" src="{{asset('admin/bower_components/datatable/js/dataTables.responsive.min.js')}}"defer></script>
<script type="text/javascript" src="{{asset('admin/bower_components/datatable/js/dataTables.scroller.min.js')}}"defer></script>
<script type="text/javascript" src="{{asset('admin/bower_components/datatable/js/responsive.bootstrap.min.js')}}"defer></script>

<script type="text/javascript" src="{{asset('admin/bower_components/datatable/js/dataTables.buttons.js')}}" defer></script>
<script type="text/javascript" src="{{asset('admin/bower_components/datatable/js/buttons.colVis.js')}}" defer></script>
<script type="text/javascript" src="{{asset('admin/bower_components/datatable/js/buttons.flash.js')}}" defer></script>
<script type="text/javascript" src="{{asset('admin/bower_components/datatable/js/jszip.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/bower_components/datatable/js/buttons.html5.js')}}" defer></script>
<script type="text/javascript" src="{{asset('admin/bower_components/datatable/js/buttons.print.js')}}" defer></script>

<script src="{{asset("admin/bower_components/jquery/dist/jquery.min.js")}}"></script>
<script src="{{asset("admin/bower_components/bootstrap/dist/js/bootstrap.min.js")}}"></script>
<script src="{{asset("js/jquery.select.js")}}"></script>
<script src="{{asset("js/number.divider.js")}}"></script>
<script src="{{asset("admin/dist/js/adminlte.min.js")}}"></script>
<script src="{{asset('js/toastr.js')}}"></script>
<script src="{{asset('js/bootbox.all.min.js')}}"></script>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

@yield('scripts')

<script>
    var master = $('[data-name="master"]'),
        side = $('[data-name="side"]');

    $('.toggle', master).on('click', function() {
        master.toggleClass('slide');
        side.toggleClass('pop');
    });
</script>
<script>

</script>
</body>
</html>
