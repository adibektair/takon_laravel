@extends('layouts.main')





@section('content')

    <div class="col-md-12 mt-2 mb-3 ">

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3>
                    <span>Локации партнера</span>
                </h3>
                <a class="btn btn-primary" href="{{route('partners.location.create', ['id'=> $partner->id])}}">
                    Создать локацию
                </a>
            </div>
            <div class="panel-body">
                <div class="row">

                    @foreach($partnersLocations as $partnersLocation)
                        <div class="col-sm-12">
                            <div class="panel panel-primary">
                                <div class="panel-header">
                                    <h3><label for="">Адрес:</label> {{$partnersLocation->address}}</h3>
                                    <a href="{{route('partners.location.edit' ,['id'=>$partnersLocation->id ])}}" class="btn-xs btn btn-primary">Изменить</a>

                                    <a onclick="deleteBtn({{$partnersLocation->id}})" class="btn-xs btn btn-danger">Удалить</a>
                                    <form method="post" id="form{{$partnersLocation->id}}" action="{{route('partners.location.delete', ['id'=> $partnersLocation->id])}}">
                                        {{csrf_field()}}
                                    </form>
                                </div>
                                <div class="panel-body">
                                    <div id="map{{$partnersLocation->id}}" style="width: 100%; height: 400px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </div>


    </div>



@endsection


@section('scripts')
    <script src="https://api-maps.yandex.ru/2.1/?apikey=b644cebb-e397-49c0-9e0e-f304bd3e04c2&lang=ru_RU"
            type="text/javascript"></script>


    <script type="text/javascript">
        @foreach($partnersLocations as $vehicle )
        ymaps.ready(init{{$vehicle->id}});

        function init{{$vehicle->id}}() {
            var myPlacemark,
                myMap = new ymaps.Map('map{{$vehicle->id}}', {
                    center: [48.005284, 66.9045434],
                    zoom: 5
                }, {
                    searchControlProvider: 'yandex#search'
                });

            function putMark{{$vehicle->id}}(coords) {

                if (myPlacemark) {
                    myPlacemark.geometry.setCoordinates(coords);
                }
// Если нет – создаем.
                else {
                    myPlacemark = createPlacemark{{$vehicle->id}}(coords);
                    myMap.geoObjects.add(myPlacemark);
// Слушаем событие окончания перетаскивания на метке.
                    myPlacemark.events.add('dragend', function () {
                        getAddress{{$vehicle->id}}(myPlacemark.geometry.getCoordinates());
                    });
                }
                getAddress{{$vehicle->id}}(coords);
            }

// Создание метки.
            function createPlacemark{{$vehicle->id}}(coords) {
                return new ymaps.Placemark(coords, {
                    iconCaption: 'поиск...'
                }, {
                    preset: 'islands#violetDotIconWithCaption',
                    draggable: true
                });
            }

// Определяем адрес по координатам (обратное геокодирование).
            function getAddress{{$vehicle->id}}(coords) {
                myPlacemark.properties.set('iconCaption', 'поиск...');
                ymaps.geocode(coords).then(function (res) {
                    var firstGeoObject = res.geoObjects.get(0);

                    myPlacemark.properties
                        .set({
// Формируем строку с данными об объекте.
                            iconCaption: 'Локация тут: ' + [
// Название населенного пункта или вышестоящее административно-территориальное образование.
                                firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
// Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
                                firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                            ].filter(Boolean).join(', '),
// В качестве контента балуна задаем строку с адресом объекта.
                            balloonContent: firstGeoObject.getAddressLine()
                        });
                });
            }

            $(document).ready(function () {
                putMark{{$vehicle->id}}([{{$vehicle->latitude}}, {{$vehicle->longitude}}]);
            });

        }
        @endforeach


        function deleteBtn(id){
            bootbox.confirm({
                title: "Вы точно хотите удалить?",
                message: "После удаления данные не вернуть",
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Отмена'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Подтвердить'
                    }
                },
                callback: function (result) {
                    if(result){
                        let form = document.getElementById(`form${id}`);
                        form.submit();
                    }
                }
            });
        }
    </script>
@endsection