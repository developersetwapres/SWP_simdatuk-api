    <style>
        html * {
            font-family: Inter !important;
            color: #394346;
        }
        .logo {
            width: 200px;
        }
        header {
            position: fixed !important;
            top: -42px;
            left: 0px;
            right: 0px;
        }
        @page {
            margin: 72px 32px;
        }
        body {
            /*margin: 20px;*/
        }
        .container {
            /*max-width: 90%;*/
            /*margin: auto;*/
        }
        .title {
            text-align: center;
            font-size: 15px;
            font-weight: 700;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
            font-weight: 400;
        }
        th, td {
            /*padding: 8px;*/
            text-align: left;
            font-size: 10px;
            font-weight: 400;
        }
        th {
            font-size: 10px;
            font-weight: 400;
        }
        .right {
            text-align: right;
        }
        .section-table td {
            /*padding: 4px 8px;*/
        }
        .section-table {
            margin-bottom: 10px;
        }
        .section-header-color{
            padding: 8px;
            background-color: #394346;
            color: white;
            word-wrap: break-word;
            font-size: 10px;
            font-weight: 400;
        }
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
            /*page-break-after: never;*/
        }

        .page-break {
            page-break-after: always;
        }

        .logo {
            width: 200px;
        }

        .title {
            font-size: 15px;
            font-weight: 700;
        }
    </style>

    <table class="section-table" style="border: none">
        <thead>
            <tr>
                <th colspan="11"></th>
            </tr>
            <tr bgcolor="#394346" style="color: #394346">
                <th class="section-header-color">No</th>
                <th class="section-header-color">Nama</th>
                <th class="section-header-color">Jabatan</th>
                <th class="section-header-color">Eselon</th>
                <th class="section-header-color">Golongan</th>
                <th class="section-header-color">NIP/NRP</th>
                <th class="section-header-color">Tempat,Tanggal Lahir</th>
                <th class="section-header-color">Umur</th>
                <th class="section-header-color">Riwayat Pendidikan</th>
                <th class="section-header-color">Riwayat Jabatan</th>
                <th class="section-header-color">Riwayat Golongan</th>
                @if($isNote)
                    <th class="section-header-color">Notes</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php $indexData=1 @endphp
            @foreach($userData as $value)
            <tr>
                <td>{{ $indexData++ }}</td>
                <td>{{ isset($value['name']) ? $value['name'] : 'N/A' }}</td>
                <td>Lorem</td>
                <td>Lorem </td>
                <td>Lorem </td>
                <td>{{ $value['employee_id_number'] }} / {{ $value['employee_registration_number'] }}</td>
                <td>{{ $value['place_of_birth']}}, {{$value['date_of_birth']}} </td>
                <td>{{ $value['age'] }} </td>
                <td>{{ $value['school'] }}</td>
                <td>Lorem </td>
                <td>Lorem </td>
                @if($isNote)
                    <td>{{ isset($value['notes']) ? $value['notes'] : 'N/A' }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
