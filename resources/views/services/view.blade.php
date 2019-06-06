@extends('layouts.main')





@section('content')
    <div class="col-md-12 mt-2 mb-3 ">
        <div class="float-left">
            <h5>Товар/услугу</h5>
        </div>
    </div>

    <br>

    <?php
            $partner = \App\Partner::where('id', '=', $service->partner_id)->first();
    ?>

    <div class="align-content-center mt-2">
        <div class="form-group">
            <label>Название услуги</label>
            <input type="text" value="<?=$service->name?>" disabled class="form-control">
        </div>

        <div class="form-group">
            <label>Партнер</label>
            <input type="text" value="<?=$partner->name?>" disabled class="form-control">
        </div>

        <div class="form-group">
            <label>Описание</label>
            <input type="text" value="<?=$service->description?>" disabled class="form-control">
        </div>


        <div class="form-group">
            <label>Срок дейтсвия</label>

            <input type="text" value="{{ $service->deadline }}" class="form-control" disabled>
        </div>

        <div class="form-group">
            <label>Цена</label>
            <input type="text" value="<?=$service->price?>" disabled class="form-control">
        </div>

        <form action="{{ route('moderate.service') }}" method="post">

            @csrf
            <input type="text" name="id" hidden value="<?=$service->id?>">
            <input type="text" hidden name="confirm" value="3" id="confirm">

            <div class="form-group">
                <label>Подтвердить/отклонить товар или услугу</label>
            </div>
            <div class="btn-group btn-group-toggle form-group" data-toggle="buttons">
                <label onclick="removeAll()" class="btn btn-secondary active">
                    <input type="radio" name="options" id="option1" autocomplete="off" checked> Подтвердить
                </label>
                <label onclick="appendInput()" class="btn btn-secondary">
                    <input type="radio" name="options" id="option2" autocomplete="off"> Отклонить
                </label>

            </div>
            <div id="reason" class="form-group">

            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success btn-lg">Применить</button>
            </div>

        </form>

    </div>

    <script>
        function appendInput() {
            var div = document.getElementById('reason');
            div.innerHTML = '';
            var input = document.createElement('input');
            input.classList.toggle('form-control');
            input.placeholder = 'Причина отказа';
            input.required = true;
            input.name = 'reason';
            div.appendChild(input);

            var conf = document.getElementById('confirm');
            conf.value = 2;
        }

        function removeAll() {

            var div = document.getElementById('reason');
            div.innerHTML = '';

            var conf = document.getElementById('confirm');
            conf.value = 3;
        }
    </script>

@endsection
