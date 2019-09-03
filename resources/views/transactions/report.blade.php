@extends('layouts.main')

@section('content')




    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Отчет</h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">
        <table border="0" cellspacing="5" cellpadding="5">
            <tbody>
            <tr>
                <td>C какого числа:</td>
                <td><input name="min" id="min" type="date"></td>
            </tr>
            <tr>
                <td>По какое число:</td>
                <td><input name="max" id="max" type="date"></td>
            </tr>
            <tr>
                <?php
                $services = \App\Service::all();
                ?>
                <td>Услуга:</td>
                <td>
                    <select name="service" id="service">
                        <?php
                        foreach ($services as $s){
                            ?>
                            <option value="<?=$s->id?>"><?=$s->name?></option>
                        <?php
                        }
                        ?>
                        <option value=""></option>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
        <table class="table table-bordered" id="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Отправитель</th>
                <th>Услуга/Товар</th>
                <th>Количество</th>
                <th>Получатель</th>
                <th>Дата</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>


    <style>
        table {
            width: 100% !important;
            margin: 0 auto !important;

        }
    </style>

    <script>
        $(document).ready(function () {


            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('transactions.report1') }}",
                    "data": function ( d ) {
                        d.minDate = $('#min').val();
                        d.maxDate = $('#max').val();
                        d.service = $('#service').val();

                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'sender', name: 'sender'},
                    {data: 'name', name: 'name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'reciever', name: 'reciever'},
                    {data: 'created_at', name: 'created_at'},
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ],
            });



            $('#min, #max, #service').change(function () {
                table.draw();
            });

        });


    </script>


@endsection
