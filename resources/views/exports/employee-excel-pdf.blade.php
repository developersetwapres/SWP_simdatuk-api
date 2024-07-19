<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
            page-break-after: auto;
        }

        .logo {
            width: 200px;
        }

        .page_break {
            page-break-after: always;
        }

        .profile-image {
            width: 120px;
            height: 160px;
        }

        .title {
            font-size: 15px;
            font-weight: 700;
        }

        .name {
            font-size: 14px;
            font-weight: 700;
        }

        .grade {
            font-size: 11px;
            font-weight: 600;
            margin-top: 8px;
        }

        .table-section-1-title {
            font-size: 10px;
            font-weight: 400;
            word-wrap: break-word;
        }

        .table-section-1-body {
            font-size: 10px;
            font-weight: 600;
            word-wrap: break-word;
        }

        .title-profile {
            font-size: 12px;
            font-weight: 700;
            margin-top: 12px;
            text-align: center;
        }

        .table-section-2-title {
            min-width: 200px;
            font-size: 10px;
            font-weight: 400;
            width: 0.1%;
            white-space: nowrap;
            padding-bottom: 4px;
            padding-top: 6px;
            word-wrap: break-word;
        }

        .table-section-2-body {
            font-size: 10px;
            font-weight: 500;
            text-align: left;
            padding-bottom: 4px;
            padding-top: 6px;
            word-wrap: break-word;
        }

        .table-section-3 {
            table-layout: fixed;
            width: 100%;
            margin-top: 8px;
        }

        .table-section-3-title {
            font-size: 10px;
            font-weight: 700;
            padding: 4px;
            color: white;
            word-wrap: break-word;
        }

        .table-section-3-title-row {
            background-color: #394346;
        }

        .table-section-3-body {
            font-size: 10px;
            font-weight: 400;
            text-align: left;
            padding-bottom: 4px;
            padding-top: 4px;
            padding-left: 4px;
            word-wrap: break-word;
        }

        ol {
            padding-left: 20px;
        }

        th,
        td {
            vertical-align: top;
        }
    </style>
</head>

<body>

    <header>
        <img src='img/setneg-logo.png' class="logo" />
    </header>

    <div class="title-profile">Export Data Pegawai</div>

    @php
    $columns = [
    "No" => true,
    "Nama" => $toggleField['isName'],
    "NIP/NRP" => $toggleField['isNip'],
    "Tempat, Tanggal Lahir" => $toggleField['isBirthPlaceDate'],
    "Usia" => $toggleField['isAge'],
    "Agama" => $toggleField['isReligion'],
    "Jenis Kelamin" => $toggleField['isGender'],
    "Status Perkawinan" => $toggleField['isMaritalStatus'],
    "Jenis Pegawai" => $toggleField['isEmployeeType'],
    "Jenis Perbantuan" => $toggleField['isAssistanceType'],
    "Jenis Outsourcing" => $toggleField['isOutsourcingType'],
    "TMT CPNS" => $toggleField['isDateCPNS'],
    "Tanggal Mulai Bekerja" => $toggleField['isStartDate'],
    "Tanggal Terakhir Bekerja" => $toggleField['isEndDate'],
    "Masa Kerja Keseluruhan" => $toggleField['isWorkDuration'],
    "Masa Kerja Golongan" => $toggleField['isGradeDuration'],
    "Eselon I" => ($toggleField['isPosition'] || $toggleField['isEchelons']),
    "Eselon II" =>($toggleField['isPosition'] || $toggleField['isEchelons']),
    "Eselon III" =>($toggleField['isPosition'] || $toggleField['isEchelons']),
    "Eselon IV" =>($toggleField['isPosition'] || $toggleField['isEchelons']),
    "Jabatan" => $toggleField['isPosition'],
    "Tanggal Mulai Menjabat" => $toggleField['isDatePosition'],
    "Eselon" => $toggleField['isEchelons'],
    "TMT Eselon" => $toggleField['isEchelonDate'],
    "Golongan" => $toggleField['isGrade'],
    "TMT Golongan" => $toggleField['isGradeDate'],
    "Instansi" => $toggleField['isAgency'],
    "No. Karpeg" => $toggleField['isNoWorker'],
    "No Karisu" => $toggleField['isKarisu'],
    "NPWP" => $toggleField['isNPWP'],
    "Status Pegawai" => $toggleField['isEmployeeStatus'],
    "No KK" => $toggleField['isNoFamily'],
    "No NIK" => $toggleField['isNIK'],
    "Alamat Tempat Tinggal Saat Ini" => $toggleField['isCurrentAddress'],
    "Nama Kompleks" => $toggleField['isComplex'],
    "No. Telp Rumah" => $toggleField['isHomeNumber'],
    "No. HP" => $toggleField['isPhoneNumber'],
    "Alamat Kantor" => $toggleField['isOfficeAddress'],
    "No. Telp Kantor" => $toggleField['isOfficeNumber'],
    "Email" => $toggleField['isEmail'],
    "Email Dinas" => $toggleField['isOfficeEmail'],
    "Kontak Darurat" => $toggleField['isEmergencyContact'],
    "Batas Usia Pensiun" => $toggleField['isPensionCap'],
    "Riwayat Jabatan" => $toggleField['isPositionHistory'],
    "Riwayat Golongan" => $toggleField['isGradeHistory'],
    "Riwayat Pelatihan Struktural" => $toggleField['isTrainingStructural'],
    "Riwayat Pelatihan Fungsional" => $toggleField['isTrainingFunctional'],
    "Riwayat Pelatihan Teknik" => $toggleField['isTrainingTechnique'],
    "Riwayat SKP" => $toggleField['isSKP'],
    "Riwayat Penghargaan" => $toggleField['isRecognition'],
    "Catatan" => $toggleField['isNotes'],
    "Riwayat Pendidikan" => $toggleField['isEducationHistory'],
    "Riwayat Hukuman" => $toggleField['isDisciplinary'],
    "Riwayat Keluarga" => $toggleField['isFamilyHistory'],
    "Riwayat Cuti" => $toggleField['isLeave'],
    "Hasil Assessment" => $toggleField['isAssessment'],
    "Hasil Uji Kompetensi" => $toggleField['isCompetency'],
    "Hasil Talent Pool" => $toggleField['isTalentPool'],
    "Keterangan" => $toggleField['isPositionDescription'],
    ];

    $filteredColumns = array_filter($columns);
    $chunks = array_chunk($filteredColumns, 7, true);
    $totalColumns = count($filteredColumns);
    @endphp

    @foreach($chunks as $chunkIndex => $chunkColumns)
    @if($chunkIndex > 0)
    <div class="page_break"></div>
    @endif

    <table class="table-section-3">
        <thead class="table-section-3-title-row">
            <tr>
                @foreach($chunkColumns as $column => $isEnabled)
                <th class="table-section-3-title">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $indexData = 1; @endphp
            @foreach($userData as $value)
            <tr>
                @foreach($chunkColumns as $column => $isEnabled)
                <td class="table-section-3-body">
                    @switch($column)
                    @case("No")
                    {{ $indexData++ }}
                    @break
                    @case("Nama")
                    {{ $value['name'] }}
                    @break
                    @case("NIP/NRP")
                    {{ $value['employee_id_number'] . "/". $value['employee_registration_number'] }}
                    @break
                    @case("Tempat, Tanggal Lahir")
                    {{ $value['place_of_birth'] }}, {{ $value['date_of_birth'] }}
                    @break
                    @case("Usia")
                    {{ $value['age'] . " Tahun"}}
                    @break
                    @case("Agama")
                    @switch($value['religion'])
                    @case(1)
                    Islam
                    @break
                    @case(2)
                    Kristen
                    @break
                    @case(3)
                    Katolik
                    @break
                    @case(4)
                    Hindu
                    @break
                    @case(5)
                    Buddha
                    @break
                    @case(6)
                    Konghucu
                    @break
                    @default
                    -
                    @endswitch
                    @break
                    @case("Jenis Kelamin")
                    {{ $value['gender'] === 1 ? 'Laki-laki' : 'Perempuan' }}
                    @break
                    @case("Status Perkawinan")
                    @switch($value['marital_status'])
                    @case(1)
                    Belum Menikah
                    @break
                    @case(2)
                    Menikah
                    @break
                    @case(3)
                    Cerai Hidup
                    @break
                    @case(4)
                    Cerai Mati
                    @break
                    @default
                    -
                    @endswitch
                    @break
                    @case("Jenis Pegawai")
                    {!! $value['employee_type'] !!}
                    @break
                    @case("Jenis Perbantuan")
                    {!! $value['assistance_type'] !!}
                    @break
                    @case("Jenis Outsourcing")
                    {!! $value['outsource_type'] !!}
                    @break
                    @case("TMT CPNS")
                    {{ $value['cpns_effective_date'] }}
                    @break
                    @case("Tanggal Mulai Bekerja")
                    {{ $value['pns_effective_date'] }}
                    @break
                    @case("Tanggal Terakhir Bekerja")
                    {{ $value['retirement_effective_date'] }}
                    @break
                    @case("Masa Kerja Keseluruhan")
                    {{ $value['work_duration'] }}
                    @break
                    @case("Masa Kerja Golongan")
                    {{ $value['grade_duration'] }}
                    @break
                    @case("Eselon I")
                    {{ $value['echelon_1'] ?? '-' }}
                    @break
                    @case("Eselon II")
                    {{ $value['echelon_2'] ?? '-' }}
                    @break
                    @case("Eselon III")
                    {{ $value['echelon_3'] ?? '-' }}
                    @break
                    @case("Eselon IV")
                    {{ $value['echelon_4'] ?? '-' }}
                    @break
                    @case("Jabatan")
                    {{ $value['position_name'] }}
                    @break
                    @case("Tanggal Mulai Menjabat")
                    {{ $value['position_effective_date'] }}
                    @break
                    @case("Eselon")
                    {{ $value['echelons_name'] }}
                    @break
                    @case("TMT Eselon")
                    {{ $value['echelon_effective_date'] }}
                    @break
                    @case("Golongan")
                    {{ $value['grade_name'] }}
                    @break
                    @case("TMT Golongan")
                    {{ $value['grade_effective_date'] }}
                    @break
                    @case("Instansi")
                    {{ $value['institution_name'] }}
                    @break
                    @case("No. Karpeg")
                    {{ $value['employee_id_card_number'] }}
                    @break
                    @case("No Karisu")
                    {{ $value['karisu_number'] }}
                    @break
                    @case("NPWP")
                    {{ $value['id_tax'] }}
                    @break
                    @case("Status Pegawai")
                    @switch($value['employment_status'])
                    @case(1)
                    Aktif
                    @break
                    @case(2)
                    Pensiun
                    @break
                    @case(3)
                    Berhenti
                    @break
                    @case(4)
                    Meninggal
                    @break
                    @case(5)
                    Alih Status
                    @break
                    @case(6)
                    Aktif PS
                    @break
                    @case(7)
                    CLTN
                    @break
                    @case(8)
                    TBL
                    @break
                    @case(9)
                    Non Aktif
                    @break
                    @default
                    -
                    @endswitch
                    @break
                    @case("No KK")
                    {{ $value['family_registration_number'] }}
                    @break
                    @case("No NIK")
                    {{ $value['id_number'] }}
                    @break
                    @case("Nama Kompleks")
                    {{ $value['residence_name'] }}
                    @break
                    @case("Alamat Tempat Tinggal Saat Ini")
                    {{ $value['current_address'] }}
                    @break
                    @case("No. Telp Rumah")
                    {{ $value['home_phone_number'] }}
                    @break
                    @case("No. HP")
                    {{ $value['mobile_phone'] }}
                    @break
                    @case("Alamat Kantor")
                    {{ $value['office_address'] }}
                    @break
                    @case("No. Telp Kantor")
                    {{ $value['office_phone_number'] }}
                    @break
                    @case("Email")
                    {{ $value['email'] }}
                    @break
                    @case("Email Dinas")
                    {{ $value['office_email'] }}
                    @break
                    @case("Keterangan")
                    {{ $value['description'] }}
                    @break
                    @case("Kontak Darurat")
                    {{ $value['emergency_contact'] }}
                    @break
                    @case("Batas Usia Pensiun")
                    {{ $value['pension_cap'] }}
                    @break
                    @case("Riwayat Pendidikan")
                    <ol>{!! $value['education_history'] !!}</ol>
                    @break
                    @case("Riwayat Jabatan")
                    <ol>{!! $value['position_history'] !!}</ol>
                    @break
                    @case("Riwayat Golongan")
                    <ol>{!! $value['grade_history'] !!}</ol>
                    @break
                    @case("Riwayat Pelatihan Struktural")
                    <ol>{!! $value['structural_training_history'] !!}</ol>
                    @break
                    @case("Riwayat Pelatihan Fungsional")
                    <ol>{!! $value['functional_training_history'] !!}</ol>
                    @break
                    @case("Riwayat Pelatihan Teknik")
                    <ol>{!! $value['technique_training_history'] !!}</ol>
                    @break
                    @case("Riwayat SKP")
                    <ol>{!! $value['skp_history'] !!}</ol>
                    @break
                    @case("Riwayat Penghargaan")
                    <ol>{!! $value['recognition_history'] !!}</ol>
                    @break
                    @case("Catatan")
                    <ol>{!! $value['notes'] !!}</ol>
                    @break
                    @case("Riwayat Hukuman")
                    <ol>{!! $value['disciplinary_history'] !!}</ol>
                    @break
                    @case("Riwayat Keluarga")
                    <ol>{!! $value['family_history'] !!}</ol>
                    @break
                    @case("Riwayat Cuti")
                    <ol>{!! $value['leave_history'] !!}</ol>
                    @break
                    @case("Hasil Assessment")
                    <ol>{!! $value['assessment_history'] !!}</ol>
                    @break
                    @case("Hasil Uji Kompetensi")
                    <ol>{!! $value['competency_history'] !!}</ol>
                    @break
                    @case("Hasil Talent Pool")
                    <ol>{!! $value['talent_pool_history'] !!}</ol>
                    @break
                    @default
                    N/A
                    @endswitch
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach

</body>

</html>