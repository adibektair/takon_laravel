<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Takon</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{asset('admin/bower_components/bootstrap/dist/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('css/popModal.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/popModal.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styleAuth.css') }}">

    <!-- Custom styles for this template -->
    <link href="{{asset('css/simple-sidebar.css')}}" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Fav icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png?v=9B0a385Eeb">
    <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-touch-icon-57x57.png?v=9B0a385Eeb">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-touch-icon-72x72.png?v=9B0a385Eeb">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-touch-icon-114x114.png?v=9B0a385Eeb">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-touch-icon-144x144.png?v=9B0a385Eeb">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png?v=9B0a385Eeb">
    <link rel="icon" type="image/png" sizes="192x192" href="/favicon/android-chrome-192x192.png?v=9B0a385Eeb">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png?v=9B0a385Eeb">
    <link rel="manifest" href="/favicon/site.webmanifest?v=9B0a385Eeb">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg?v=9B0a385Eeb" color="#091316">
    <link rel="shortcut icon" href="/favicon/favicon.ico?v=9B0a385Eeb">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="msapplication-TileImage" content="/favicon/mstile-144x144.png?v=9B0a385Eeb">
    <meta name="theme-color" content="#ffffff">

    <style>

    </style>
</head>
<body id="app-layout">
<nav class="navbar navbar-dark bg-dark mb-4 ">
    <span class="navbar-brand mb-0 h1">
        <a class="navbar-brand formatted__text" href="{{ url('/') }}">
                TAKON
            </a>
    </span>
</nav>

@yield('content')

<!-- Bootstrap core JavaScript -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>

{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
