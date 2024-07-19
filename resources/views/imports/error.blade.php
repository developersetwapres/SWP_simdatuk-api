<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <style type="text/css">
        html * {
            font-family: Inter !important;
            color: #394346;
        }


        @page {
            margin: 72px 32px;
        }

        .logo {
            width: 200px;
        }

        .title {
            font-size: 15px;
            font-weight: 700;
        }

        .sub-title {
            font-size: 12px;
            font-weight: 500;
        }

        .list-title {
            font-size: 12px;
            font-weight: 700;
            padding: 4px;
            word-wrap: break-word;
        }

        .list-body {
            font-size: 12px;
            font-weight: 400;
            text-align: left;
            padding-bottom: 4px;
            padding-top: 4px;
            padding-left: 4px;
            word-wrap: break-word;
        }

        ul {
            padding-left: 20px;
        }
    </style>
</head>

<body>
    <header>
        <img src='img/setneg-logo.png' class="logo" />
    </header>

    <center>
        <div class="title">
            Hasil Error Tambah Massal Data Pegawai
        </div>
        <div class="sub-title">
            Per Tanggal : {{$tanggal}}
        </div>
    </center>

    <ol>
        @foreach($errors as $sheet => $value)
        <li class="list-title">
            <b>{{$sheet}}</b>
            <ul>
                @foreach($value as $error)
                <li class="list-body">{{$error}}</li>
                @endforeach
            </ul>
        </li>
        @endforeach
    </ol>
</body>

</html>