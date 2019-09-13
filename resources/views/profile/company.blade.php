@extends('layouts.main')
@section('content')

    <div class="col-md-12 mt-2 mb-3 ">
        <div class="float-left">
            <h5>Редактировать профиль</h5>
        </div>
    </div>

    <br>

    <?php

    $partner = \App\Company::where('id', '=', auth()->user()->company_id)->first();

    ?>
    <div class="col-md-12">
        <form action="{{ route('edit.company') }}"  method="post">
            @csrf

            <input type="hidden" name="id" value="<?=$partner->id?>">
            <label class="text-semibold">Имя</label>
            <input type="text" name="name" value="<?=$partner->name?>" required placeholder="Имя"  class="form-control">

            <br>
            <label class="text-semibold">Телефон </label>
            <input type="text" name="phone" placeholder="Телефон" required value="<?=$partner->phone?>" class="form-control">

            <br>
            <label class="text-semibold">Адрес </label>
            <input type="text" name="address" placeholder="Адрес" required value="<?=$partner->address?>" class="form-control">



            <hr>
            <button type="submit" class="btn btn-success">Сохранить</button>
        </form>
    </div>



@endsection
