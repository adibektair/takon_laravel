@extends('layouts.main')
@section('content')
    <div class="container mt-4">
        <div class="col-md-12 mt-2 mb-3 ">
            <div class="float-left">
                <h5>Профиль</h5>
            </div>
            <hr>
        </div>

        <?php

        $partner = \App\Company::where('id', '=', auth()->user()->company_id)->first();

        ?>

        <div class="col-md-6">
            <div class="company-profile">
                <form action="{{ route('edit.company') }}"  method="post">
                    @csrf

                    <input type="hidden" name="id" value="<?=$partner->id?>">
                    <label class="text-semibold">Имя</label>
                    <input type="text" name="name" value="<?=$partner->name?>" required placeholder="Имя" class="form-control">

                    <br>
                    <label class="text-semibold">Телефон</label>
                    <input type="text" name="phone" placeholder="Телефон" required value="<?=$partner->phone?>" class="form-control">

                    <br>
                    <label class="text-semibold">Адрес</label>
                    <input type="text" name="address" placeholder="Адрес" required value="<?=$partner->address?>" class="form-control">

{{--                    <br>--}}
{{--                    <label class="text-semibold">Email</label>--}}
{{--                    <input type="text" name="email" placeholder="Email" required value="" class="form-control">--}}

                    <br>
                    <button type="submit" class="btn btn-success profile__button">Сохранить</button>
                </form>
            </div>
        </div>

{{--        <div class="col-md-6">--}}
{{--            <div class="company-profile">--}}
{{--                @if(count($errors) > 0)--}}
{{--                    <ul class="list-group">--}}
{{--                        @foreach($errors->all() as $error)--}}
{{--                            <li class="list-group-item text-danger danger">--}}
{{--                                {{ $error }}--}}
{{--                            </li>--}}
{{--                        @endforeach--}}
{{--                    </ul>--}}
{{--                    <br>--}}
{{--                @endif--}}
{{--                <form id="form-change-password" role="form" method="POST" action="{{ url('/user/credentials') }}" novalidate class="form-horizontal">--}}
{{--                    <label for="current-password" class="text-semibold">Текущий пароль</label>--}}
{{--                    <input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
{{--                    <input type="password" class="form-control" id="current-password" name="current-password" placeholder="Пароль">--}}

{{--                    <br>--}}
{{--                    <label for="password" class="text-semibold">Новый пароль</label>--}}
{{--                    <input type="password" class="form-control" id="password" name="password" placeholder="Пароль">--}}

{{--                    <br>--}}
{{--                    <label for="password_confirmation" class="text-semibold">Повторите пароль</label>--}}
{{--                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Повторите пароль">--}}

{{--                    <br>--}}
{{--                    <button type="submit" class="btn btn-primary profile__button">Изменить пароль</button>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>

@endsection
