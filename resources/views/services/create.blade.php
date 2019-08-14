@extends('layouts.main')



<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
<!-- Bootstrap core CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<!-- Material Design Bootstrap -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.7/css/mdb.min.css" rel="stylesheet">

<!-- JQuery -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.7/js/mdb.min.js"></script>

@section('content')
    <div class="col-md-12 mt-2 mb-3 ">
        <div class="float-left">
            <h5>Добавить товар/услугу</h5>
        </div>
    </div>

    <br>


    <div class="align-content-center mt-2">

        <form action="{{ route('store.service') }}" method="post">

            @csrf
            <input type="hidden" name="id" >
            <label class="text-semibold">Название</label>
            <input type="text" required name="name" placeholder="Название"  class="form-control">
            <br>
            <label class="text-semibold">Цена за единицу(в тенге)</label>
            <input type="number" required name="price" placeholder="Цена" class="form-control">

            <br>
            <label class="text-semibold">Описание</label>
            <input required type="text" name="desc" placeholder="Описание" class="form-control">

            <br>
            <label class="text-semibold">Cрок действия (в днях)</label>
            <input required type="number" name="deadline" placeholder="Количество" class="form-control">

            <br>
            <label class="text-semibold">Cрок действия (в днях)</label>
            <input required type="number" name="deadline" placeholder="Количество" class="form-control">
            <br>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" name="payment" id="customSwitch1" checked>
                <label class="custom-control-label" for="customSwitch1">Онлайн покупка </label>
            </div>
            <br>

            <label class="text-semibold">Цена за единицу в тенге для онлайн покупки</label>
            <input required type="number" name="payment_price" placeholder="Цена" value="0" class="form-control">
            <br>

            <label class="text-semibold">Cрок действия (в днях) для онлайн оплаты</label>
            <input required type="number" name="payment_deadline" placeholder="10"  class="form-control" >

            <br>
            <hr>


            <button type="submit" class="btn btn-success">Сохранить</button>
        </form>
    </div>

    <script>

    </script>

@endsection
