@extends('layouts.main')





@section('content')


    <div class="align-content-center mt-2">

        <form action="{{ route('store.partner') }}" method="post">

            @csrf
            <input type="hidden" name="id" >
            <label class="text-semibold">Имя партнера</label>
            <input type="text" name="name" placeholder="Имя"  class="form-control">
            <br>
            <label class="text-semibold">Телефон партнера</label>
            <input type="text" name="phone" placeholder="Телефон" class="form-control">
            <br>
            <label class="text-semibold">Адрес партнера</label>
            <input type="text" name="address" placeholder="Адрес" class="form-control">
            <hr>

            <label class="text-semibold">Логин для входа администратора</label>
            <input type="text" name="login" placeholder="login" class="form-control">
            <br>
            <label class="text-semibold">Пароль</label>
            <input type="text" name="password" placeholder="пароль" class="form-control">

            <hr>


            <button type="submit" class="btn btn-success">Сохранить</button>
        </form>
    </div>



@endsection
