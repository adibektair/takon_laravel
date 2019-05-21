@extends('layouts.main')
@section('content')
    <div class="col-md-12 mt-2 mb-3 ">
        <div class="float-left">
            <h5>Поделиться с юр. лицами (товар/услуга: <?=$service->name?>)</h5>
        </div>
    </div>

    <br>

    <?php
        $companies = \App\Company::where('id', '<>', auth()->user()->company_id)->get();
    ?>
    <div class="align-content-center mt-2">

        <form action="{{ route('share') }}" method="post">
            @csrf
            <input type="text" hidden name="id" value="{{ $com_ser->id }}">
            <div class="form-group">
                <label for="" class="text-secondary">Выберите юр. лицо</label>
                <select name="company_id" class="custom-select" required>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="" class="text-secondary">Введите количество для перевода (в наличии: {{ $com_ser->amount }})</label>
                <input type="number" name="amount" required class="form-control">
            </div>
            <button class="btn btn-success" type="submit">Отправить</button>
        </form>

    </div>



@endsection
