@extends('layouts.main')
@section('content')
    <div class="col-md-12 mt-2 mb-3 ">
        <div class="float-left">
            <h5>Возврат таконов ({{ $service->name }}) пользователя {{ $user->phone }}</h5>
        </div>
    </div>

    <br>

    <div class="align-content-center mt-2">

        <form action="{{ route('finish.return') }}" method="post">
            @csrf
            <input type="text" hidden name="id" value="{{ $us->id }}">
            <div class="form-group">
                <label for="" class="text-secondary">Введите количество для возврата на счет компании (в наличии у пользователя : {{ $us->amount }})</label>
                <input type="number" name="amount" required class="form-control">
            </div>
            <button class="btn btn-success" type="submit">Отправить</button>
        </form>

    </div>



@endsection
