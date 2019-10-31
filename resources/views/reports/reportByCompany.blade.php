@extends('layouts.main')

@section('styles')
    <style>
        #overlay {
            background: #ffffff;
            color: #666666;
            position: fixed;
            height: 100%;
            width: 100%;
            z-index: 5000;
            top: 0;
            left: 0;
            float: left;
            text-align: center;
            padding-top: 25%;
            opacity: .80;
        }

        .spinner {
            margin: 0 auto;
            height: 64px;
            width: 64px;
            animation: rotate 0.8s infinite linear;
            border: 5px solid firebrick;
            border-right-color: transparent;
            border-radius: 50%;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@section('content')




    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Отчет</h5>
        </div>
        <div id="overlay" style="display:none;">
            <div class="spinner"></div>
            <br/>
            Загрузка...
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <form>
                    <div class="row" style="margin: 10px">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>C какого числа:</label>
                                <input class="form-control" name="min" id="min" type="date">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>По какое число:</label>
                                <input class="form-control" name="max" id="max" type="date">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            @if(Auth::user()->role_id == 1)
                                <label>Компания:</label>

                                <select class="form-control" id="company">
                                    <option value="">Не выбрано</option>
                                    @foreach($companies as $c)
                                        <option value="{{$c->id}}">{{$c->name}}</option>
                                    @endforeach
                                    <option value=""></option>
                                </select>

                                <div class="col-sm-6">
                                    @elseif(Auth::user()->role_id == 3)
                                        <input type="hidden" id="company" class="form-control" name="company"
                                               value="{{Auth::user()->company_id}}">
                                    @endif
                                </div>

                                <label>Получить данные </label>
                                <br>
                                <a class="btn btn-danger m-1" onclick="fetchDataForReport()">Отчет<span
                                            class="fa fa-file-o"></span></a>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-sm-12">

                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#home">Главная</a></li>
                            <li><a data-toggle="tab" href="#menu1">Отчет</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="home" class="tab-pane fade in active">
                                <h3>Главная</h3>

                                <table class="table table-bordered" id="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Отправитель</th>
                                        <th>Имя отправителя</th>
                                        <th>Услуга/Товар</th>
                                        <th>Количество</th>
                                        <th>Получатель</th>
                                        <th>Имя получателя</th>
                                        <th>Дата</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Отправитель</th>
                                        <th>Имя отправителя</th>
                                        <th>Услуга/Товар</th>
                                        <th>Количество</th>
                                        <th>Получатель</th>
                                        <th>Имя получателя</th>
                                        <th>Дата</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div id="menu1" class="tab-pane fade">
                                <h3>Отчет</h3>
                                <div class="table-responsive">
                                    <table id="report" class="table">
                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>

        var table;
        $(document).ready(function () {
            table = $('#table').DataTable({
                processing: true,
                responsive: true,
                language: {
                    url: '{{asset('admin/bower_components/datatable/js/ru.locale.json')}}',
                },
                bPaginate: false,
                serverSide: true,
                ajax: {
                    url: "{{ route('ajax.report.by.company') }}",
                    data: function (d) {
                        d.minDate = $('#min').val();
                        d.maxDate = $('#max').val();
                        d.company = $('#company').val();
                    },
                    dataSrc: function (json) {
                        hideOverlay();
                        return json.data;
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        hideOverlay();
                        bootbox.alert("Ошибка! Пожалуйста, обратитесь к администратору!");
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'sender', name: 'sender'},
                    {data: 'sender_name', name: 'sender_name'},
                    {data: 'name', name: 'name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'reciever', name: 'reciever'},
                    {data: 'reciever_name', name: 'reciever_name'},
                    {data: 'created_at', name: 'created_at'},
                ],
                dom: 'Bfrltip',
                buttons: {
                    buttons: [
                        {extend: 'excel', className: 'btn btn-success', action: newExportAction}
                    ]
                },
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var select = $('<select class="form-control"><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                }
            });
            table.on('preXhr.dt', function (e, settings, data) {
                showOverlay();
            });


            $('#min, #max, #company').change(function () {
                if ($('#min').val() && $('#max').val() && $('#max').val() !== '' && $('#min').val() !== '' && $('#company').val()) {
                    table.draw();
                }
            });
        });


        var newExportAction = function (e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;

            dt.one('preXhr', function (e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;

                dt.one('preDraw', function (e, settings) {
                    // Call the original action function
                    oldExportAction(self, e, dt, button, config);

                    dt.one('preXhr', function (e, s, data) {
                        // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                        // Set the property to what it was before exporting.
                        settings._iDisplayStart = oldStart;
                        data.start = oldStart;
                    });

                    // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                    setTimeout(dt.ajax.reload, 0);

                    // Prevent rendering of the full data to the DOM
                    return false;
                });
            });

            // Requery the server with the new one-time export settings
            dt.ajax.reload();
        };
        var oldExportAction = function (self, e, dt, button, config) {
            if (button[0].className.indexOf('buttons-excel') >= 0) {
                if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
                }
                else {
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                }
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
        };

        class FirstReport {
            constructor(id, itemId, itemName) {
                this.id = id;
                this.itemId = itemId;
                this.itemName = itemName;
                this.startBalance = 0;
                this.givenBalance = 0;
                this.sent = 0;
                this.returned = 0;
                this.lastBalance = 0;
                this.lastId = 0;
                this.startId = 0;
            }
        }

        class SecondReport {
            constructor(number) {
                this.number = number;
                this.sender = '';
                this.senderName = '';
                this.item = '';
                this.itemId = '';
                this.received = '';
                this.sent = '';
                this.created_at = ''
            }
        }

        function fetchFirst() {
            var names = [];
            var data = table.rows().data();
            var map = [];
            for (var i = 0; i < data.length; i++) {
                var cell = data[i];
                if (cell.service_id) {
                    map[cell.service_id] = 0;
                    names[cell.service_id] = cell.name;
                }
            }
            var index = 1;
            for (var key in map) {
                map[key] = new FirstReport(index++, key);
            }

            for (var key in map) {
                map[key].itemName = names[key];
            }

            for (var i = 0; i < data.length; i++) {
                var cell = data[i];

                if (cell.type == 2) {
                    if (cell.p_s_id && map[cell.service_id]) {
                        map[cell.service_id].givenBalance += parseInt(cell.amount, 10)
                    }
                }

                if (cell.type == 1) {
                    if (cell.c_s_id && map[cell.service_id]) {
                        map[cell.service_id].sent += parseInt(cell.amount, 10)
                    }
                }

                if (cell.type == 5) {
                    if (cell.u_s_id && cell.c_r_id && map[cell.service_id]) {
                        map[cell.service_id].returned += parseInt(cell.amount, 10)
                    }
                }

                if (cell.c_s_id) {
                    if (map[cell.service_id] && (map[cell.service_id].lastId > cell.id || map[cell.service_id].startId == 0)) {
                        map[cell.service_id].lastId = cell.id
                        map[cell.service_id].lastBalance = cell.balance
                    }

                    if (map[cell.service_id] && (map[cell.service_id].startId < cell.id)) {
                        map[cell.service_id].startId = cell.id
                        map[cell.service_id].startBalance = cell.balance
                    }
                }

            }

            data = [];
            for (var key in map) {
                data.push(map[key]);
            }

            return data;
        }

        function fetchSecond() {
            var names = [];
            var data = table.rows().data();
            var map = [];

            var index = 0;
            info = [];

            for (var i = 0; i < data.length; i++) {
                var cell = data[i];

                if (cell.c_s_id == $('#company').val()) {
                    var secondResponse = new SecondReport(++index);
                    secondResponse.sender = cell.receiver ? cell.receiver : '';
                    secondResponse.senderName = cell.reciever_name ? cell.reciever_name : '';
                    secondResponse.itemId = cell.service_id;
                    secondResponse.item = cell.name;
                    secondResponse.sent = cell.amount;
                    secondResponse.received = 0;
                    secondResponse.created_at = cell.created_at;
                    info.push(secondResponse);
                }

                if (cell.c_r_id == $('#company').val()) {
                    var secondResponse = new SecondReport(++index);
                    secondResponse.sender = cell.sender ? cell.sender : '';
                    secondResponse.senderName = cell.sender_name ? cell.sender_name : '';
                    secondResponse.itemId = cell.service_id;
                    secondResponse.item = cell.name;
                    secondResponse.sent = 0;
                    secondResponse.received = cell.amount;
                    secondResponse.created_at = cell.created_at;
                    info.push(secondResponse);
                }
            }

            return info;
        }

        function fetchDataForReport() {

            showOverlay();
            var reportTable = document.getElementById("report");

            var header = [
                '#',
                'Топливо',
                'Баланс на начало',
                'Пополнено',
                'Отправлено',
                'Возврат',
                'Баланс на конец'
            ];
            var report = fetchFirst();
            var rows = [];
            for (var i = 0; i < report.length; i++) {
                rows.push({
                    '#': report[i].id,
                    'Топливо': report[i].itemName,
                    'Баланс на начало': report[i].startBalance,
                    'Пополнено': report[i].givenBalance,
                    'Отправлено': report[i].sent,
                    'Возврат': report[i].returned,
                    'Баланс на конец': report[i].lastBalance,
                })
            }
            var div = createTable(header, report);
            reportTable.innerText = '';
            reportTable.appendChild(div);

            var header1 = [
                '#',
                'Отправитель',
                'Имя отправителя',
                'Услуга/Товар',
                'Получено',
                'Отправлено',
                'Дата'
            ];

            var report1 = fetchSecond();
            var rows1 = [];
            for (var i = 0; i < report1.length; i++) {
                rows1.push({
                    '#': report1[i].number,
                    'Отправитель': report1[i].sender,
                    'Имя отправителя': report1[i].senderName,
                    'Услуга/Товар': report1[i].item,
                    'Получено': report1[i].received,
                    'Отправлено': report1[i].sent,
                    'Дата': report1[i].created_at,
                })
            }
            div = createTable2(header1, report1);
            reportTable.appendChild(div);
            var text = `Отчет по '${$('#company option:selected').text()}' с ${$('#min').val()} по ${$('#max').val()}`;
            excel([[text], header, header1,], [[], rows, rows1]);


            hideOverlay();
        }

        function createTable(header, body) {
            var div = document.createElement("div");
            var thead = document.createElement("thead");
            var trh = document.createElement("tr");
            thead.appendChild(trh);
            for (var i = 0; i < header.length; i++) {
                var th = document.createElement("th");
                th.innerText = header[i];
                trh.appendChild(th);
            }

            var tbody = document.createElement("tbody");
            for (var i = 0; i < body.length; i++) {
                var tr = document.createElement("tr");
                var td1 = document.createElement("td");
                td1.innerText = body[i].id;
                tr.appendChild(td1);

                var td2 = document.createElement("td");
                td2.innerText = body[i].itemName;
                tr.appendChild(td2);

                var td3 = document.createElement("td");
                td3.innerText = body[i].startBalance;
                tr.appendChild(td3);

                var td4 = document.createElement("td");
                td4.innerText = body[i].givenBalance;
                tr.appendChild(td4);

                var td5 = document.createElement("td");
                td5.innerText = body[i].sent;
                tr.appendChild(td5);

                var td6 = document.createElement("td");
                td6.innerText = body[i].returned;
                tr.appendChild(td6);

                var td7 = document.createElement("td");
                td7.innerText = body[i].lastBalance;
                tr.appendChild(td7);

                tbody.appendChild(tr);
            }

            div.appendChild(thead);
            div.appendChild(tbody);

            return div;
        }

        function createTable2(header, body) {
            var div = document.createElement("div");
            var thead = document.createElement("thead");
            var trh = document.createElement("tr");
            thead.appendChild(trh);
            for (var i = 0; i < header.length; i++) {
                var th = document.createElement("th");
                th.innerText = header[i];
                trh.appendChild(th);
            }
            var tbody = document.createElement("tbody");
            for (var i = 0; i < body.length; i++) {
                var tr = document.createElement("tr");
                var td1 = document.createElement("td");
                td1.innerText = body[i].number;
                tr.appendChild(td1);

                var td2 = document.createElement("td");
                td2.innerText = body[i].sender;
                tr.appendChild(td2);

                var td3 = document.createElement("td");
                td3.innerText = body[i].senderName;
                tr.appendChild(td3);

                var td4 = document.createElement("td");
                td4.innerText = body[i].item;
                tr.appendChild(td4);

                var td5 = document.createElement("td");
                td5.innerText = body[i].received;
                tr.appendChild(td5);

                var td6 = document.createElement("td");
                td6.innerText = body[i].sent;
                tr.appendChild(td6);

                var td7 = document.createElement("td");
                td7.innerText = body[i].created_at;
                tr.appendChild(td7);

                tbody.appendChild(tr);
            }

            div.appendChild(thead);
            div.appendChild(tbody);

            return div;
        }


        function showOverlay() {
            $('#overlay').fadeIn();
        }

        function hideOverlay() {
            $('#overlay').fadeOut();
        }

        function excel(xlsHeaders, xlsRows) {
            var createXLSLFormatObj = [];


            if (xlsHeaders.length == xlsRows.length) {

                for (var i = 0; i < xlsHeaders.length; i++) {

                    createXLSLFormatObj.push(xlsHeaders[i]);
                    $.each(xlsRows[i], function (index, value) {
                        var innerRowData = [];
                        $.each(value, function (ind, val) {

                            innerRowData.push(val);
                        });
                        createXLSLFormatObj.push(innerRowData);
                    });
                    createXLSLFormatObj.push([]);
                    createXLSLFormatObj.push([]);

                }

            } else {
                throw Error('Rows and headers not suffice');
            }

            /* File Name */
            var filename = "Отчет.xlsx";

            /* Sheet Name */
            var ws_name = "Отчет";

            var wb = XLSX.utils.book_new(),
                ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);
            XLSX.utils.book_append_sheet(wb, ws, ws_name);
            XLSX.writeFile(wb, filename);
        }
    </script>

@endsection