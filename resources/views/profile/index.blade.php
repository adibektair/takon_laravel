@extends('layouts.main')
@section('content')

    <div class="col-md-12 mt-2 mb-3 ">
        <div class="float-left">
            <h5>Профиль</h5>
        </div>
        <hr>
    </div>

    <br>

    <?php

    $partner = \App\Partner::where('id', '=', auth()->user()->partner_id)->first();

    ?>
    <div class="col-md-12">
        <form action="{{ route('edit.partner') }}" enctype="multipart/form-data" method="post">
            @csrf

            <div class="col-md-6">
                @if($partner->image_path)
                    <img src="/public/avatars/<?=$partner->image_path?>" style="width: 300px; height: 300px;" class="rounded">
                @else
                    <img src="/public/avatars/Logo-placeholder.png"  class="rounded-circle" >
                @endif
                <br><br>
                <label class="text-info">Загрузить лого</label>
                <input type="file" class="form-control" name="avatar" style="max-width: 300px">
                <br>
            </div>

            <div class="col-md-6">
                <input type="hidden" name="id" value="<?=$partner->id?>">
                <label class="text-semibold">Имя</label>
                <input type="text" name="name" value="<?=$partner->name?>" required placeholder="Имя"  class="form-control">

                <br>
                <label class="text-semibold">Телефон </label>
                <input type="text" name="phone" placeholder="Телефон" required value="<?=$partner->phone?>" class="form-control">

                <br>
                <label class="text-semibold">Описание</label>
                <input type="text" name="desc" placeholder="Описание" required value="<?=$partner->description?>" class="form-control">

                <br>
                <label class="text-semibold">Адрес </label>
                <input type="text" name="address" placeholder="Адрес" required value="<?=$partner->address?>" class="form-control">


                <br>
                <button type="submit" class="btn btn-success profile__button">Сохранить</button>
            </div>
        </form>
    </div>



@endsection
