@extends('layouts.main')


@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="panel"  style="padding: 10px">
                    <div class="panel-header">
                        <h2>Изменить</h2>
                        {{--<a  class="btn btn-primary btn-sm" href="{{route('user.index')}}">Назад</a>--}}
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <form
                                        {{--action="{{Route::currentRouteName() == 'self.user.edit' ? route('self.user.update') : route('user.update' ,['id'=>$user->id])}}" --}}
                                        method="post">
                                    <div class="form-group">
                                        <label for="name">Имя</label>
                                        <input type="text" value="{{$user->name}}" name="first_name" class="form-control" placeholder="Имя" required>
                                    </div>
                                    {{csrf_field()}}
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary btn-block" value="Изменить">
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-6">
                                <form
                                        {{--action="{{Route::currentRouteName() == 'self.user.updatePassword' ? route('self.user.updatePassword') : route('user.updatePassword' ,['id'=>$user->id])}}" --}}
                                        method="post">
                                    <div class="form-group">
                                        <label for="name">Пароль</label>
                                        <input type="password" name="password" class="form-control" placeholder="Пароль" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Повторите пароль</label>
                                        <input type="password" name="repassword" class="form-control" placeholder="Повторите пароль" required>
                                    </div>
                                    {{csrf_field()}}
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary btn-block" value="Изменить">
                                    </div>
                                </form>
                            </div>
                        </div>
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