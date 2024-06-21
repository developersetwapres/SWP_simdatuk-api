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
            page-break-after: never;
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
        "Jabatan" => $toggleField['isPosition'],
        "Deskripsi Jabatan" => $toggleField['isPositionDescription'],
        "Eselon" => $toggleField['isEchelons'],
        "Grade" => $toggleField['isGrade'],
        "NIP" => $toggleField['isNip'],
        "Tempat, Tanggal Lahir" => $toggleField['isBirthPlaceDate'],
        "Usia" => $toggleField['isAge'],
        "Agama" => $toggleField['isReligion'],
        "Jenis Kelamin" => $toggleField['isGender'],
        "Status Perkawinan" => $toggleField['isMaritalStatus'],
        "Instansi" => $toggleField['isAgency'],
        "Organisasi" => $toggleField['isOrganization'],
        "Unit Kerja" => $toggleField['isWorkUnit'],
        "No. Pegawai" => $toggleField['isNoWorker'],
        "Lama Bekerja" => $toggleField['workDuration'],
        "Lama Grade" => $toggleField['isGradeDuration'],
        "NPWP" => $toggleField['isNPWP'],
        "Status Kepegawaian" => $toggleField['isEmployeeStatus'],
        "Alamat Sekarang" => $toggleField['isCurrentAddress'],
        "Perumahan / Kompleks" => $toggleField['isComplex'],
        "No. Rumah" => $toggleField['isHomeNumber'],
        "No. Telepon" => $toggleField['isPhoneNumber'],
        "Alamat Kantor" => $toggleField['isOfficeAddress'],
        "No. Kantor" => $toggleField['isOfficeNumber'],
        "Email" => $toggleField['isEmail'],
        "Maksimal Pensiun" => $toggleField['isPensionCap'],
        "Riwayat Jabatan" => $toggleField['isPositionHistory'],
        "Riwayat Golongan" => $toggleField['isGradeHistory'],
        "Riwayat Pelatihan Struktural" => $toggleField['isTrainingStructural'],
        "Riwayat Pelatihan Fungsional" => $toggleField['isTrainingFunctional'],
        "Riwayat Pelatihan Teknik" => $toggleField['isTrainingTechnique'],
        "Riwayat Penghargaan" => $toggleField['isRecognition'],
        "Riwayat SKP" => $toggleField['isSKP'],
        "Riwayat Edukasi" => $toggleField['isEducationHistory'],
        "Riwayat Hukuman" => $toggleField['isDisciplinary'],
        "Riwayat Keluarga" => $toggleField['isFamilyHistory'],
        "Riwayat Cuti" => $toggleField['isLeave'],
        "Hasil Assessment" => $toggleField['isAssessment'],
        "Hasil Uji Kompetensi" => $toggleField['isCompetency'],
        "Hasil Talent Pool" => $toggleField['isTalentPool'],
        "Catatan" => $toggleField['isNotes'],
        "Deskripsi Jabatan" => $toggleField['isPositionDescription'],
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
                            @case("Jabatan")
                                {{ $value['position_name'] }}
                                @break
                            @case("Deskripsi Jabatan")
                                {{ $value['position_description'] }}
                                @break
                            @case("Eselon")
                                {{ $value['echelons_name'] }}
                                @break
                            @case("Grade")
                                {{ $value['grade_name'] }}
                                @break
                            @case("NIP")
                                '{{ $value['employee_id_number'] }}
                                @break
                            @case("Tempat, Tanggal Lahir")
                                {{ $value['place_of_birth'] }}, {{ $value['date_of_birth'] }}
                                @break
                            @case("Usia")
                                {{ $value['age'] }}
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
                                {{ $value['gender'] === 1 ? 'Pria' : 'Wanita' }}
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
                            @case("Instansi")
                                {{ $value['institution_name'] }}
                                @break
                            @case("Organisasi")
                                {{ $value['organization_name'] }}
                                @break
                            @case("Unit Kerja")
                                {{ $value['work_unit'] }}
                                @break
                            @case("No. Pegawai")
                                {{ $value['employee_id_number'] }} / {{ $value['employee_registration_number'] }}
                                @break
                            @case("Lama Bekerja")
                                {{ $value['work_duration'] }}
                                @break
                            @case("Lama Grade")
                                {{ $value['grade_effective_date'] }}
                                @break
                            @case("NPWP")
                                {{ $value['id_tax'] }}
                                @break
                            @case("Status Kepegawaian")
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
                            @case("Alamat Sekarang")
                                {{ $value['current_address'] }}
                                @break
                            @case("Perumahan / Kompleks")
                                {{ $value['residence_name'] }}
                                @break
                            @case("No. Rumah")
                                {{ $value['home_phone_number'] }}
                                @break
                            @case("No. Telepon")
                                {{ $value['mobile_phone'] }}
                                @break
                            @case("Alamat Kantor")
                                {{ $value['office_address'] }}
                                @break
                            @case("No. Kantor")
                                {{ $value['office_phone_number'] }}
                                @break
                            @case("Email")
                                {{ $value['email'] }}
                                @break
                            @case("Maksimal Pensiun")
                                {{ $value['pension_cap'] }}
                                @break
                            @case("Riwayat Jabatan")
                                <ul>{!! $value['position_history'] !!}</ul>
                                @break
                            @case("Riwayat Golongan")
                                <ul>{!! $value['grade_history'] !!}</ul>
                                @break
                            @case("Riwayat Pelatihan Struktural")
                                <ul>{!! $value['structural_training_history'] !!}</ul>
                                @break
                            @case("Riwayat Pelatihan Fungsional")
                                <ul>{!! $value['functional_training_history'] !!}</ul>
                                @break
                            @case("Riwayat Pelatihan Teknik")
                                <ul>{!! $value['technique_training_history'] !!}</ul>
                                @break
                            @case("Riwayat Penghargaan")
                                <ul>{!! $value['recognition_history'] !!}</ul>
                                @break
                            @case("Riwayat SKP")
                                <ul>{!! $value['skp_history'] !!}</ul>
                                @break
                            @case("Riwayat Edukasi")
                                <ul>{!! $value['education_history'] !!}</ul>
                                @break
                            @case("Riwayat Hukuman")
                                <ul>{!! $value['disciplinary_history'] !!}</ul>
                                @break
                            @case("Riwayat Keluarga")
                                <ul>{!! $value['family_history'] !!}</ul>
                                @break
                            @case("Riwayat Cuti")
                                <ul>{!! $value['leave_history'] !!}</ul>
                                @break
                            @case("Hasil Assessment")
                                <ul>{!! $value['assessment_history'] !!}</ul>
                                @break
                            @case("Hasil Uji Kompetensi")
                                <ul>{!! $value['competency_history'] !!}</ul>
                                @break
                            @case("Hasil Talent Pool")
                                <ul>{!! $value['talent_pool_history'] !!}</ul>
                                @break
                            @case("Catatan")
                                <ul>{!! $value['notes'] !!}</ul>
                                @break
                            @default
                                -
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
