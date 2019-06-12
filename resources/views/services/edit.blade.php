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
            <label class="switch">
                <input name="active" <?php if($service->status == 3){ ?> checked <?php }?> type="checkbox" >
                <span class="slider round"></span>
            </label>
            Активно
            <br>
            <hr>


            <button type="submit" class="btn btn-success">Сохранить</button>
        </form>
    </div>

    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>


@endsection
