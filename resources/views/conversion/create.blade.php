@extends('layouts.main')
@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Добавить новую конвертацию</h5>
        </div>
    </div>

    <div class="col-md-12 mt-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="{{ route('conversion.store') }}" method="post">
                    @csrf
                    <div class="col-md-6">
                        <label>Услуга 1</label>
                        <select required name="service1" id="1">
                            <option value="">Не выбрано</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}"> {{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Услуга 2</label>
                        <select required name="service2" id="2">
                            <option value="">Не выбрано</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}"> {{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <h4>Введите коэффициент.</h4>
                        <p class="info"> При конвертации пользователем, количество полученных таконов Услуги 2 будет равным количеству таконов Услуги 1 умноженным на коэффициент</p>
                        <p class="info">От 0.1 до 1000</p>
                        <input type="number" step="0.01" class="form-control" name="coefficient" required>
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
