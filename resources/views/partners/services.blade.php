@extends('layouts.main')

@section('content')
    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>Товары и услуги  <?=$name?></h5>
        </div>
    </div>

    <br><br>
    <div class="col-md-12 mt-2">

        <table class="table table-bordered" id="table">
            <thead>
                <tr>
                    <th >#</th>
                    <th >Название</th>
                    <th >Цена за единицу</th>
                    <th >Срок в днях</th>
                    <th>Приобрести</th>
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
                ajax: "/get-partners-services?id=<?=$id?>",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'price', name: 'price' },
                    { data: 'deadline', name: 'deadline' },
                    { data: 'service', name: 'service' },


                ]
            });
        });
    </script>

@endsection
