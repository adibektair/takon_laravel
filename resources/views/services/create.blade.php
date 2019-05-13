@extends('layouts.main')





@section('content')
    <div class="col-md-12 mt-2 mb-3 ">
        <div class="float-left">
            <h5>Добавить товар/услугу</h5>
        </div>
    </div>

    <br>


    <div class="align-content-center mt-2">

        <form action="{{ route('store.service') }}" method="post">

            @csrf
            <input type="hidden" name="id" >
            <label class="text-semibold">Название</label>
            <input type="text" required name="name" placeholder="Название"  class="form-control">
            <br>
            <label class="text-semibold">Цена за единицу(в тенге)</label>
            <input type="number" required name="price" placeholder="Цена" class="form-control">
            <br>
            <label class="text-semibold">Количество</label>
            <input required type="number" name="amount" placeholder="Количество" class="form-control">
            <br>
            <hr>


            <button type="submit" class="btn btn-success">Сохранить</button>
        </form>
    </div>



@endsection
