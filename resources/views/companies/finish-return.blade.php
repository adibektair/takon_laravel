@extends('layouts.main')
@section('content')
    <div class="col-md-12 mt-2 mb-3 ">
        <div class="float-left">
            <h5>Возврат таконов ({{ $service->name }}) пользователя {{ $user->phone }}</h5>
        </div>
    </div>

    <br>

    <div class="align-content-center mt-2">

        <form action="{{ route('finish.return') }}" method="post" id="yourFormId">
            @csrf
            <input type="text" hidden name="id" value="{{ $us->id }}">
            <div class="form-group">
                <label for="" class="text-secondary">Введите количество для возврата на счет компании (в наличии у пользователя : {{ $us->amount }})</label>
                <input type="number" step="any" name="amount" required class="form-control">
            </div>
            <button class="btn btn-success submitBtn" type="submit">Отправить</button>
        </form>

    </div>



@endsection


@section('scripts')
    <script>
        $("#yourFormId").submit(function () {
            $(".submitBtn").attr("disabled", true);
            return true;
        });
    </script>
@endsection