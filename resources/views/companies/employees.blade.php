@extends('layouts.main')

@section('content')

    <div class="container mt-4">
        <div class="col-md-12 mt-2 mb-3 bg-transparent">
            <div class="float-left">
                <h5>Cотрудники</h5>
            </div>
            <hr>
        </div>

        <div class="col-md-12 mt-2 mb-3">
            <div class="float-right">
                <a href="{{ route('company.employees.create') }}">
                    <button class="btn employee__button">Добавить сотрудника</button>
                </a>
            </div>
            <br>
        </div>

        <div class="col-md-12 mt-2">

            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-bordered statTable" id="table">
                        <thead>
                        <tr>
                            <th class="table__title">#</th>
                            <th class="table__title">Имя</th>
                            <th class="table__title">Ник</th>
                            <th class="table__title">Пароль</th>
                            <th class="table__title">Создан</th>
                            <th class="table__title"></th>

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

    </div>


@endsection
