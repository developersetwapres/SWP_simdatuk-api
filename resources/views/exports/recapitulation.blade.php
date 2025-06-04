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

        header {
            position: fixed;
            top: -42px;
            left: 0px;
            right: 0px;
        }

        p {
            page-break-after: always;
        }

        p:last-child {
            page-break-after: never;
        }

        .logo {
            width: 200px;
        }

        .page_break {
            page-break-after: always;
        }

        .title {
            font-size: 15px;
            font-weight: 700;
        }

        .sub-title {
            font-size: 11px;
            font-weight: 600;
            margin-top: 2px;
        }

        .table-section-row-1 {}

        .table-section-title-1 {
            font-weight: 700;
            font-size: 9px;
            padding: 4px 0px 0px 0px;
            margin: 0;
        }

        .table-section-body-1 {
            font-weight: 700;
            font-size: 9px;
            text-align: right;
            padding: 4px 0px 0px 0px;
            margin: 0;
        }

        .table-section-row-2 {}

        .table-section-title-2 {
            font-weight: 400;
            font-size: 9px;
            padding: 0;
            margin: 0;
        }

        .table-section-body-2 {
            font-weight: 400;
            font-size: 9px;
            text-align: right;
            padding: 0;
            margin: 0;
        }

        .table-section-row-3 {
            background-color: #394346;
            border-top: 4px solid transparent;
            border-bottom: 4px solid transparent;
        }

        .table-section-title-3 {
            font-weight: 600;
            font-size: 9px;
            color: white;
            padding: 4px;
            margin: 0;
        }

        .table-section-body-3 {
            font-weight: 600;
            font-size: 9px;
            color: white;
            text-align: right;
            padding: 4px;
            margin: 0;
        }
    </style>
</head>

<body>

    <!-- start of page 1 -->
    <header>
        <img src='img/setneg-logo.png' class="logo" />
    </header>

    <center>
        <div class="title">
            {{$title}}
        </div>
        <div class="sub-title">
            Per Tanggal : {{$date}}
        </div>
    </center>

    <table style="width: 100%; margin-top: 8px; border-collapse: collapse;">
        @foreach($data as $value)
        <tr class="table-section-row-{{$value['type']}}">
            <td class="table-section-title-{{$value['type']}}">{{ $value['title'] }}</td>
            <td class="table-section-body-{{$value['type']}}">{{ $value['body'] }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
