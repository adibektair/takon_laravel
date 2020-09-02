@extends('layouts.main')





@section('content')
    <div class="col-md-12 mt-2 mb-3 ">
        <div class="float-left">
            <h5>Добавить сотрудника</h5>
        </div>
    </div>

    <br>


    <div class="align-content-center mt-2">

        <form action="{{ route('store.employee') }}" method="post">

            @csrf
            <input type="hidden" name="id" >
            <label class="text-semibold">Имя</label>
            <input type="text" required name="name" placeholder="Имя"  class="form-control">
            <br>
            <label class="text-semibold">Email</label>
            <input type="email" required name="email" placeholder="Email" class="form-control">
            <br>
            <label class="text-semibold">Пароль</label>
            <input required type="text" name="password" placeholder="Пароль" class="form-control">
            <br>
            <label class="text-semibold">Адрес</label>
            <input type="text" required name="address" placeholder="Адрес" class="form-control">
            <br>
            <hr>


            <button type="submit" class="btn btn-success">Сохранить</button>
        </form>
    </div>



@endsection
