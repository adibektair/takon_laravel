@extends('layouts.main')





@section('content')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <?php
    $service = \App\Service::where('id', '=', $order->service_id)->first();
    $user = \App\User::where('id', '=', $order->user_id)->first();
    $company = \App\Company::where('id', '=', $user->company_id)->first();
    $partner = \App\Partner::where('id', '=', $service->partner_id)->first();
    ?>
    <div class="col-md-12 mt-2 mb-3 ">
        <div class="float-left">
            <h5>Заявка на покупку <?=$service->name?></h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12">
            <div class="col-md-12">
                <h5>Заявка от юр. лица: <?=$company->name?></h5>
                <br>
                <label class="text-semibold">Телефон: <?=$company->phone?></label>
            </div>
        <br><br>
            <div class="col-md-12">
                <h5>Заявка партнеру: <?=$partner->name?></h5>
                <br>
                <label class="text-semibold">Телефон: <?=$partner->phone?></label>
            </div>
        <br>
            <div class="col-md-12">
                <h5>Товар или услуга</h5>
                <label class="text-semibold">Наименование: <?=$service->name?></label>
                <br>
                <label class="text-semibold">В количестве: <?=$order->amount?></label>
                <br>
                <label class="text-semibold">На сумму: <?=$order->cost?></label>
            </div>
        <hr>
        <br>
        <form action="{{ route('save.order') }}" method="post" >
            @csrf
            <input type="text" hidden value="<?=$order->id?>" name="id">
            <input type="text" hidden name="confirm" value="3" id="confirm">
            <div class="col-md-12">

                <div class="form-group">
                    <label>Подтвердить/отклонить тразакцию</label>
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
                    <button id="accept_btn" onclick="disableBtn()" type="submit" class="btn btn-success btn-lg">Применить</button>
                </div>

            </div>

        </form>
    </div>
    <br>

    <script>
        function disableBtn(){
            document.getElementById('accept_btn').disabled = 'disabled';
        }

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
