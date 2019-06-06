@extends('layouts.main')

@section('content')
    <div class="col-md-12 mt-2 mb-3 ">
        <div class="float-left">
            <h5>Редактировать {{ $service->name }}</h5>
        </div>
    </div>

    <br>


    <div class="align-content-center mt-2">

        <form action="{{ route('edit.service.save') }}" method="post">

            @csrf
            <input type="hidden" value="{{ $service->id }}" name="id" >
            <label class="text-semibold">Название</label>
            <input type="text" required name="name" placeholder="Название" value="{{ $service->name }}" class="form-control">
            <br>
            <label class="text-semibold">Цена за единицу(в тенге)</label>
            <input type="number" required name="price" placeholder="Цена" value="{{ $service->price }}" class="form-control">

            <br>
            <label class="text-semibold">Описание</label>
            <input required type="text" name="desc" placeholder="Описание" class="form-control" value="{{ $service->description }}">

            <br>
            <label class="text-semibold">Cрок действия (в днях)</label>
            <input required type="number" name="deadline" value="{{ $service->deadline }}" placeholder="Количество" class="form-control">

            <br>
            <hr>


            <button type="submit" class="btn btn-success">Сохранить</button>
        </form>
    </div>



@endsection
