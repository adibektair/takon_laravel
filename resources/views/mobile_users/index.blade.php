@extends('layouts.main')




@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Пользователи</h5>
            <h6>Выберите пользователей, которым хотите  отправить таконы</h6>

            <a href="{{ route('groups') }}">
                <button class="btn btn-outline-info">Избранное</button>
            </a>
            <br><br><br>
        </div>

    </div>
    <div class="float-right mb-4">
            <div id="addings" >

            </div>

            <form action="{{ route('send') }}" method="post">
                @csrf
                <input type="text" hidden name="ids" id="ids">
                <input type="text" hidden name="cs_id" value="{{ $id }}">

                <button hidden id="bt" class="btn btn-success ">Выбрать</button>
            </form>
    </div>




    <br><br>
    <div class="col-md-12 mt-2">

        <table class="table table-bordered" id="table">
            <thead>
            <tr>
                <th></th>
                <th >Имя</th>
                <th >Телефон</th>
                <th >Создан</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>



    <style>
        table{
            width: 100% !important;
            margin: 0 auto !important;

        }
    </style>
    <script>


        $(function() {
            var array = [];
            var names = [];
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('all.mobile_users') }}",
                stateSave: true,

                columns: [
                    { data: 'checkbox', name: 'checkbox' },
                    { data: 'name', name: 'name' },
                    { data: 'phone', name: 'phone' },
                    { data: 'created_at', name: 'created_at'},

                ],

            });

            //$('#table tbody').on('change', 'input[type="checkbox"]', function(){
            $('#table tbody').on('click', 'button', function(){

                array.push(this.id);
                this.hidden = true
                document.getElementById('bt').hidden = false
                var uniq = array.reduce(function(a,b){
                    if (a.indexOf(b) < 0 ) a.push(b);
                    return a;
                },[]);
                var name = $(this).data("name");
                names.push(name);
                var names1 = names.reduce(function(a,b){
                    if (a.indexOf(b) < 0 ) a.push(b);
                    return a;
                },[]);
                var label = document.createElement('label');
                label.innerText = names;
                document.getElementById('addings').innerHTML = '';
                document.getElementById('addings').appendChild(label);
                $('input:hidden[name=ids]').val(uniq);

            });



        });



        setTimeout(function(){
            $('.name').change(function() {
                $.ajax({
                    type: "POST",
                    url: '/set-name',
                    data: {
                        name: this.value,
                        id: this.id,
                        _token: "{{ csrf_token() }}",

                    },
                    success: function (data) {

                    }
                });

            });
        }, 3000);


    </script>

@endsection
