<!DOCTYPE html>
<html>

<head>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <style type="text/css">
        html * {
            font-family: Inter !important;
            /* color: #394346; */
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

        .profile-image {
            width: 45px;
            height: 60px;
        }

        .user-name {
            font-size: 8px;
            font-weight: 700;
        }

        .user-registration-number {
            font-size: 7px;
            font-weight: 600;
        }

        .page_break {
            page-break-after: always;
        }

        .title {
            font-size: 13px;
            font-weight: 700;
        }

        td {
            padding-top: 0px;
        }

        .text-black {
            color: #394346;
        }

        .subtitle {
            font-size: 8px;
            font-weight: 700;
            margin-top: 12px;
        }

        .card {
            border-radius: 6px;
            border: 1px solid #F0F0F0;
            padding: 8px;
            margin-top: 6px;
        }

        .card-title {
            font-size: 8px;
            font-weight: 700;
            color: #895700;
        }

        .card-item-title {
            font-size: 7px;
            font-weight: 400;
            color: #394346;
            width: 30%;
        }

        .card-item-subtitle {
            font-size: 7px;
            font-weight: 400;
            color: #394346;
        }

        .card-item-separator {
            background-color: #F0F0F0;
            width: 100%;
            height: 1px;
        }

        .note-owner {
            font-size: 8px;
            font-weight: 700;
        }

        .note-item {
            font-size: 7px;
            font-weight: 400;
            color: #394346;
        }

        .vertical-top {
            vertical-align: top;
        }
    </style>
</head>

<body>

    <!-- start of page 1 -->
    <header>
        <img src='img/setneg-logo.png' class="logo" />
    </header>

    <center>
        <div class="title text-black">
            Bandingkan Pegawai
        </div>
    </center>

    <table style="margin-top:10px; width: 100%;">
        <tr>
            @php $loopCount=5 @endphp
            @for ($i = 0; $i < $loopCount; $i++)
                <td>
                    <table style="margin-left: auto; margin-right: auto;">
                        <tr>
                            <td>
                                <img src='img/profile.jpg' class="profile-image" />
                            </td>
                            <td class="vertical-top">
                                <table style="margin-left: 8px; " cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="user-name text-black">
                                            Wibowo aji utomo, S.E.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="user-registration-number text-black">
                                            123123123123 /<br>123123123
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            @endfor
        </tr>
    </table>

    <!-- graph section -->
    <div class="subtitle text-black">
        Grafik
    </div>

    <div class="card">
        <div class="card-title">Umum</div>
        <table style="width:100%; table-layout:fixed; border-spacing: 10px 5px;">
            @for ($section = 0; $section < 5; $section++)
                @if ($section % 2 == 0)
                    <tr>
                @endif

                <td class="card-item-title">
                    Eselon
                </td>
                <td style="width: 70%">
                    <table style="width:100%;">
                        @for ($i = 0; $i < $loopCount; $i++)
                            <tr>
                                <td colspan="2">
                                    <div style="width: {{ ($i + 1) * 20 }}%; background-color: red; height:6px;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="card-item-subtitle">
                                    Eselon 1
                                </td>
                                <td class="card-item-subtitle" style="width:1%;">
                                    80
                                </td>
                            </tr>
                        @endfor
                    </table>
                </td>

                <!-- SEPARATOR -->
                @if ($section % 2 == 1 || $section == 5 - 1)
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="card-item-separator" />
                        </td>
                        @if ($section < 5 - 1)
                            <td colspan="2">
                                <div class="card-item-separator" />
                            </td>
                        @endif
                    </tr>
                @endif
            @endfor
        </table>
    </div>
    <!-- end of graph section -->

    <!-- notes section -->
    <div class="subtitle text-black">
        Catatan
    </div>

    <table style="width:100%; table-layout:fixed; margin-top: 8px; border-spacing: 10px 0;">
        <tr>
            @for ($i = 0; $i < $loopCount; $i++)
                <td class="note-owner">
                    Wibowo Aji Utomo, S.E.
                </td>
            @endfor
        </tr>

        @for ($i = 0; $i < $loopCount; $i++)
            <tr>
                @for ($a = 0; $a < $loopCount; $a++)
                    <td class="note-item">
                        {{ $i + 1 }}. Sangat rajin, tepat waktu, rajin menabung, selalu beribadah dan selalu bisa
                        diandalkan!
                    </td>
                @endfor
            </tr>
        @endfor
    </table>
    <!-- end of notes section -->
</body>

</html>
