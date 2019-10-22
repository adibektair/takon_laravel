@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-header">
                        <h2>Добавить локацию</h2>
                        <a class="btn btn-primary btn-sm" href="{{route('partners.location', ['id' => $partner->id])}}">Назад</a>
                    </div>
                    <div class="panel-body">
                        <form action="{{route('partners.location.store', ['id' => $partner->id])}}" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Адрес</label>
                                        <textarea class="form-control" name="address" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="name">Выберите с карты местоположение?</label>
                                        <div id="map" style="width: 100%; height: 400px">
                                        </div>
                                        <div class="form-group">
                                            <label for="longitude">Долгота</label>
                                            <input type="text" class="form-control" readonly id="longitude"
                                                   name="longitude" placeholder="Longitude" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="latitude">Широта</label>
                                            <input type="text" name="latitude" readonly class="form-control"
                                                   id="latitude" placeholder="Latitude" required>
                                        </div>

                                    </div>
                                </div>
                                {{csrf_field()}}
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success btn-block" value="Добавить">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="panel-footer">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://api-maps.yandex.ru/2.1/?apikey=b644cebb-e397-49c0-9e0e-f304bd3e04c2&lang=ru_RU"
            type="text/javascript"></script>


    <script type="text/javascript">
        ymaps.ready(init);

        function init() {
            var myPlacemark,
                myMap = new ymaps.Map('map', {
                    center: [48.005284, 66.9045434],
                    zoom: 4
                }, {
                    searchControlProvider: 'yandex#search'
                });

// Слушаем клик на карте.
            myMap.events.add('click', function (e) {
                var coords = e.get('coords');
// Если метка уже создана – просто передвигаем ее.
                if (myPlacemark) {
                    myPlacemark.geometry.setCoordinates(coords);
                }
// Если нет – создаем.
                else {
                    myPlacemark = createPlacemark(coords);
                    myMap.geoObjects.add(myPlacemark);
// Слушаем событие окончания перетаскивания на метке.
                    myPlacemark.events.add('dragend', function () {
                        getAddress(myPlacemark.geometry.getCoordinates());
                    });
                }
                document.getElementById("latitude").value = coords[0];
                document.getElementById("longitude").value = coords[1];
                getAddress(coords);
            });

// Создание метки.
            function createPlacemark(coords) {
                return new ymaps.Placemark(coords, {
                    iconCaption: 'поиск...'
                }, {
                    preset: 'islands#violetDotIconWithCaption',
                    draggable: true
                });
            }

// Определяем адрес по координатам (обратное геокодирование).
            function getAddress(coords) {
                myPlacemark.properties.set('iconCaption', 'поиск...');
                ymaps.geocode(coords).then(function (res) {
                    var firstGeoObject = res.geoObjects.get(0);

                    myPlacemark.properties
                        .set({
// Формируем строку с данными об объекте.
                            iconCaption: [
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
        }
    </script>
@endsection
