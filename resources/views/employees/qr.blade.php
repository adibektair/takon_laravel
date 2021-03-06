@extends('layouts.main')





@section('content')
    <script src="{{asset('js/query.qrcode.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>

    <div class="col-md-12 mt-2 mb-3 bg-transparent">
        <div class="float-left">
            <h5>{{ $user->name }}</h5>
        </div>
    </div>

    <div id="cont" class="col-md-12 mt-2 mb-3">
        <div class="float-right">
            <button onclick="print()" class="btn btn-success">Печать</button>
        </div>

    </div>

    <div id="qr">

        <?php
        use chillerlan\QRCode\QRCode;
        $qr = new QRCode();

        echo '<img width="400" height="400" src="'.$qr->render($user->hash).'" />';

        ?>

    </div>
    <br>
    <br>

    <div class="row justify-content-center">

            <div id="qrcode"></div>
    </div>

    <script>

        function print() {
            var mywindow = window.open('', 'PRINT', 'height=400,width=600');
            mywindow.document.write('<h1>' + document.title  + '</h1>');
            mywindow.document.write('<div style=" width: 400px; height:400px">'+ document.getElementById('qr').outerHTML +'</div>');
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
            mywindow.print();
            mywindow.close();
        }

    </script>


@endsection
