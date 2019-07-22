@extends('layouts.main')
@section('content')
    <div class="col-md-12 mt-2 mb-3 ">
        <div class="float-left">
            <h5>Редактировать сотрудника</h5>
        </div>
    </div>

    <br>

    <div class="align-content-center mt-2">

        <form action="{{ route('store.user') }}" method="post">

            @csrf
            <input type="hidden" name="id" value="{{ $user->id }}">
            <label class="text-semibold">Имя</label>
            <input type="text" name="name" placeholder="Имя" value="{{ $user->name }}" class="form-control">
            <br>
            <label class="text-semibold">email</label>
            <input type="email" name="email" placeholder="email" value="{{ $user->email }}" class="form-control">
            <br>
            <label class="text-semibold">Пароль</label>
            <input type="text" name="password" placeholder="пароль" class="form-control">
            <br>
            <label class="text-semibold">Повторите пароль</label>
            <input type="text" name="password1" placeholder="пароль" class="form-control">
            <hr>
            <button type="submit" class="btn btn-success">Сохранить</button>
        </form>
    </div>



@endsection
