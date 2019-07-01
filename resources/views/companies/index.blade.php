@extends('layouts.main')




@section('content')


    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Юр. лица </h5>
        </div>
    </div>

    <div class="col-md-12 mt-2 mb-3">
        <div class="float-right">
            <a href="{{ route('create.company') }}"><button class="btn btn-success">Добавить юр. лицо</button></a>
        </div>
    </div>


    <br><br>
    <div class="col-md-12 mt-2">

        <table class="table table-bordered" id="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>email</th>

                <th>Адрес</th>
                <th>Создан</th>
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



        $(document).ready(function () {
            $('#table').DataTable({

                processing: true,
                serverSide: true,
                ajax: "{{ route('all.companies') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'phone', name: 'phone' },
                    { data: 'email', name: 'email' },

                    { data: 'address', name: 'address'},
                    { data: 'created_at', name: 'created_at'},


                ],
                dom: 'Bfrtip',
                buttons: [
                   'excel'
                ],
            });
        });


    </script>

{{--    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>--}}

@endsection
