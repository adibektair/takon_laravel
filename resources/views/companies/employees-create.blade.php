@extends('layouts.main')

@section('content')
    <div class="container mb-4">
        <div class="col-md-12 mt-2 mb-3">
            <h5>Добавить сотрудника</h5>
            <hr>
        </div>
        <div class="col-md-12">
            <div class="align-content-center mt-2">

                <form action="" method="post">

                    @csrf
                    <input type="hidden" name="id" >
                    <label class="text-semibold">Имя</label>
                    <input type="text" required name="name" placeholder="Имя"  class="form-control">
                    <br>
                    <label class="text-semibold">Ник</label>
                    <input type="name" required name="name" placeholder="Ник" class="form-control">
                    <br>
                    <label class="text-semibold">Пароль</label>
                    <input required type="text" name="password" placeholder="Пароль" class="form-control">
                    <br>
                    <label class="text-semibold">Повторите пароль</label>
                    <input required type="text" name="passwordConfirmation" placeholder="Повторите пароль" class="form-control">
                    <br>
                    <button type="submit" class="btn btn-success">Сохранить</button>
                </form>
            </div>
        </div>

    </div>

@endsection
