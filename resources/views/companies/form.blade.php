@extends('layouts.main')





@section('content')
    <div class="col-md-12 mt-2 mb-3 ">
        <div class="float-left">
            <h5>Приобрести услугу: <?=$service->name?></h5>
        </div>
    </div>

    <br>


    <div class="align-content-center mt-2">

        <form action="{{ route('buy') }}" method="post">

            @csrf
            <label class="text-semibold">Цена за единицу: <?=$service->price?></label>
            <br>
            <label class="text-semibold">В наличии: <?=$service->amount?></label>
            <br>
            <input type="hidden" value="<?=$service->id?>" name="id" >
            <label class="text-semibold">Введите количество</label>
            <input type="number" id="amount" name="amount" placeholder="Количество" required class="form-control">
            <br>
            <label class="text-semibold">Сумма в тенге</label>
            <input id="cost" type="text"  placeholder="Сумма в тг" disabled class="form-control">
            <br>
            <hr>


            <button type="submit" class="btn btn-success">Приобрести</button>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            var price = '<?=$service->price?>';
            $('#amount').on('keyup paste',username_check);
            function username_check(){
                setTimeout( function() {
                    var amount = $('#amount').val();
                    var cost = price * amount;
                    document.getElementById('cost').value = cost;
                },100);
            }
        });
    </script>

@endsection
