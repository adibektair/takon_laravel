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

    <div id="a">

    </div>
    <br>
    <br>

    <div class="row justify-content-center">

            <div id="qrcode"></div>
    </div>

    <script>
        jQuery('#qrcode').qrcode({text: '<?=$user->hash?>', render: 'table'});

        function print() {
            var mywindow = window.open('', 'PRINT', 'height=400,width=600');
            mywindow.document.write('<html><head><title>' + document.title  + '</title>');
            mywindow.document.write('</head><body>');
            mywindow.document.write('<h1>' + document.title  + '</h1>');
            mywindow.document.write('<div style="background-color: red; width: 300px; height: 300px">'+ document.getElementById('qrcode').outerHTML +'</div>');
            mywindow.document.write('</body></html>');
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
            mywindow.print();
            mywindow.close();
        }

    </script>


@endsection
