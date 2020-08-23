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
