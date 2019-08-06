@extends('layouts.main')

@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Поиск по транзакциям</h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">
        <form method="post" action="{{ route('transactions.search.go') }}">
            <label>Введите номер телефона в формате 77015554797</label>
            <br>
            <input type="text" class="form-control" name="phone" required placeholder="77005554797">
            <br> <br>
            @csrf
            <button class="btn btn-success">
                ПОИСК
            </button>
        </form>
    </div>


@endsection
