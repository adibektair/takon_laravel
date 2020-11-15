@extends('layouts.main')
@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Изменить конвертацию</h5>
        </div>
    </div>

    <div class="col-md-12 mt-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="{{ route('conversion.save') }}" method="post">
                    @csrf
                    <input type="text" hidden name="id" value="{{ $data->id }}">
                    <div class="col-md-6">
                        <label>Услуга 1</label>
                        <select required name="service1" id="1">
                            <option value="{{ $data->firstService->id }}">{{ $data->firstService->name }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Услуга 2</label>
                        <select required name="service2" id="2">
                            <option value="{{ $data->secondService->id }}">{{ $data->secondService->name }}</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <h4>Введите коэффициент.</h4>
                        <p class="info"> При конвертации пользователем, количество полученных таконов Услуги 2 будет равным количеству таконов Услуги 1 умноженным на коэффициент</p>
                        <p class="info">От 0.1 до 1000</p>
                        <input step="0.01" type="number" value="{{ $data->coefficient }}" class="form-control" name="coefficient" required>
                    </div>
                    <div class="col-md-12">
                        <h4>Доступно</h4>
                        <input type="checkbox" name="is_available" class="checkbox" <? if($data->is_available){?> checked <?}?>">
                        <br><br>
                    </div>


                    <div class="">
                        <br><br>
                        <button class="btn btn-info">
                            Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        table{
            width: 100% !important;
            margin: 0 auto !important;

        }
    </style>
    <script>
        $('#1').on('change', function() {
            var second = document.getElementById('2').value;
            if(this.value == second){
                alert('Выберите другое значение');
                this.value = "";
            }
        });
        $('#2').on('change', function() {
            var first = document.getElementById('1').value;
            if(this.value == first){
                alert('Выберите другое значение');
                this.value = "";
            }
        });

    </script>
@endsection
