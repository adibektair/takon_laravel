@extends('layouts.main')




@section('content')
    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = 1;
    }
    ?>

    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h3>Сохраненные группы пользователей</h3>
        </div>
        <div class="col-md-12 mt-2 mb-3">
            <form id="form" action="{{ route('send.to.user') }}" method="post">
                <div class="form-group">
                    <label>Выберите услугу</label>
                    <?php
                    $services = \App\CompaniesService::where('company_id', '=', auth()->user()->company_id)->get();
                    ?>
                    <select name="cs_id">
                        <?php
                        foreach ($services as $service){
                        $s = \App\Service::where('id', $service->service_id)->first();
                        ?>
                        <option value="<?=$service->id?>"
                                <?php if($id == $service->id) { ?>selected<?php } ?>><?=$s->name . " количество(" . $service->amount . ")"?></option>
                        <?php
                        }
                        ?>
                    </select>


                </div>
                @csrf
                <div class="form-group">
                    <label>Введите номер телефона в формате 77015554797</label>
                    <input type="number" required class="form-control" name="phone">
                </div>

                <div class="form-group">
                    <label>Введите количество таконов</label>
                    <input type="number" required class="form-control" name="amount">
                </div>

                <button id="bbb" type="submit" class="btn btn-success">Отправить</button>
            </form>

            <div class="float-right">
                <a href="{{ route('create.group') }}">
                    <button class="btn btn-info">Добавить группу</button>
                </a>
            </div>
        </div>
    </div>



    <br><br>
    <div class="col-md-12 mt-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Группа</th>
                        <th>Создан</th>
                        <th></th>
                        <th></th>

                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <style>
        table {
            width: 100% !important;
            margin: 0 auto !important;
        }
    </style>


    <script>

        $(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    url: '{{asset('admin/bower_components/datatable/js/ru.locale.json')}}',
                },
                responsive: true,
                ajax: "/get-groups?id=<?=$id?>",
                stateSave: true,
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'checkbox', name: 'checkbox'},
                    {data: 'remove', name: 'remove'},
                ],
            });
        });
        setTimeout(function () {
            $(".btn-danger").click(function () {
                $.ajax({
                    type: "POST",
                    url: '/remove-group',
                    data: {
                        id: this.id,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function (data) {
                        if (data.success == true) {
                            swal("Успешно", "Группа пользователей удалена", "success");
                            location.reload();
                        }

                    }
                });

            });
        }, 1500);

        $(document).ready(function () {
            $("#form").submit(function () {
                $("#bbb").attr("disabled", true);
                return true;
            });
        });
    </script>

@endsection
