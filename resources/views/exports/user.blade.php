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
            margin: 72px 25px;
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
            font-size: 14px;
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

    <!-- start of page 1 -->
    <header>
        <img src='img/setneg-logo.png' class="logo" />
    </header>

    <center>
        <div class="title">
            Daftar Riwayat Hidup
        </div>
    </center>

    <table>
        <tr>
            <td>
                <img src='img/dummy-export/profile.png' class="profile-image" />
            </td>
            <td>
                <table style="margin-left: 12px;">
                    <tr>
                        <td class="name">
                            Ica Marisa
                        </td>
                    </tr>
                    <tr>
                        <td class="grade">
                            Kepala Subbagian, Bagian Protokol, dan Kerumahtanggaan, Deputi Bidang Administrasi
                        </td>
                    </tr>
                    <tr>
                        <table style="width: 100%; margin-top: 4px;">
                            <tr>
                                <td class="table-section-1-title">
                                    Eselon
                                </td>
                                <td class="table-section-1-title">
                                    Golongan
                                </td>
                                <td class="table-section-1-title">
                                    NIP/NRP
                                </td>
                            </tr>
                            <tr>
                                <td class="table-section-1-body">
                                    Eselon IV, 02-05-2023
                                </td>
                                <td class="table-section-1-body">
                                    Penata Tingkat I (III/d), 01-10-2019
                                </td>
                                <td class="table-section-1-body">
                                    197901032005012001
                                </td>
                            </tr>
                        </table>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="title-profile">Data Pribadi</div>

    <table style="width: 100%; margin-top: 8px;">
        @foreach($userProfile as $key => $value)
        <tr style="border-bottom: 1px solid #F0F0F0;">
            <td class="table-section-2-title">{{ $key }}:</td>
            <td class="table-section-2-body">{{ $value }}</td>
        </tr>
        @endforeach
    </table>

    <div class="page_break"></div>

    <!-- end of page 1 -->

    <div class="title-profile">Riwayat Pendidikan</div>

    <table class="table-section-3">
        <thead class="table-section-3-title-row">
            <td class="table-section-3-title">No</td>
            <td class="table-section-3-title">Tingkat</td>
            <td class="table-section-3-title">Nama Sekolah</td>
            <td class="table-section-3-title">Fakultas</td>
            <td class="table-section-3-title">Jurusan</td>
            <td class="table-section-3-title">Status</td>
            <td class="table-section-3-title">Tahun Lulus</td>
            <td class="table-section-3-title">Keterangan Sekolah</td>
        </thead>

        <tbody>
            @php $indexCollege=1 @endphp
            @foreach($userCollege as $value)
            <tr style="border-bottom: 1px solid #F0F0F0;">
                <td class="table-section-3-body">{{ $indexCollege++ }}</td>
                <td class="table-section-3-body">{{ $value['grade'] }}</td>
                <td class="table-section-3-body">{{ $value['school_name'] }}</td>
                <td class="table-section-3-body">{{ $value['faculty'] }}</td>
                <td class="table-section-3-body">{{ $value['major'] }}</td>
                <td class="table-section-3-body">{{ $value['status'] }}</td>
                <td class="table-section-3-body">{{ $value['year_graduate'] }}</td>
                <td class="table-section-3-body">{{ $value['desc'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="title-profile">Riwayat Jabatan</div>

    <table class="table-section-3">
        <thead class="table-section-3-title-row">
            <td class="table-section-3-title">No</td>
            <td class="table-section-3-title">Jabatan</td>
            <td class="table-section-3-title">Rumpun</td>
            <td class="table-section-3-title">TMT Menjabat</td>
            <td class="table-section-3-title">SK Menjabat</td>
            <td class="table-section-3-title">SK Jabatan</td>
            <td class="table-section-3-title">Jenis SK Jabatan</td>
            <td class="table-section-3-title">No SK Jabatan</td>
            <td class="table-section-3-title">Tanggal SK Jabatan</td>
            <td class="table-section-3-title">Keterangan Eselon</td>
            <td class="table-section-3-title">Keterangan Jabatan</td>
            <td class="table-section-3-title">TMT Selesai</td>
            <td class="table-section-3-title">SK Selesai</td>
            <td class="table-section-3-title">Jenis SK Selesai</td>
            <td class="table-section-3-title">No SK Selesai</td>
            <td class="table-section-3-title">Tanggal SK Selesai</td>
            <td class="table-section-3-title">Status Jabatan</td>
        </thead>

        <tbody>
            @php $indexGrade=1 @endphp
            @foreach($userGrade as $value)
            <tr style="border-bottom: 1px solid #F0F0F0;">
                <td class="table-section-3-body">{{ $indexGrade++ }}</td>
                <td class="table-section-3-body">Lorem Ipsummmm</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="title-profile">Riwayat Golongan</div>

    <table class="table-section-3">
        <thead class="table-section-3-title-row">
            <td class="table-section-3-title">No</td>
            <td class="table-section-3-title">Golongan</td>
            <td class="table-section-3-title">TMT Golongan</td>
            <td class="table-section-3-title">SK Golongan</td>
            <td class="table-section-3-title">SK Golongan</td>
            <td class="table-section-3-title">Jenis SK Golongan</td>
            <td class="table-section-3-title">No SK Golongan</td>
            <td class="table-section-3-title">Tanggal SK Golongan</td>
            <td class="table-section-3-title">Keterangan Golongan</td>
            <td class="table-section-3-title">Status Golongan</td>
        </thead>

        <tbody>
            @php $indexGolongan=1 @endphp
            @foreach($userGolongan as $value)
            <tr style="border-bottom: 1px solid #F0F0F0;">
                <td class="table-section-3-body">{{ $indexGolongan++ }}</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="title-profile">Riwayat Pelatihan Truktural</div>

    <table class="table-section-3">
        <thead class="table-section-3-title-row">
            <td class="table-section-3-title">No</td>
            <td class="table-section-3-title">Nama Diklat</td>
            <td class="table-section-3-title">No Surat Perintah</td>
            <td class="table-section-3-title">Jenjang</td>
            <td class="table-section-3-title">Tanggal Pelaksanaan</td>
            <td class="table-section-3-title">Durasi Pelatihan (Hari)</td>
            <td class="table-section-3-title">Penyelenggara</td>
            <td class="table-section-3-title">Sertifikat</td>
        </thead>

        <tbody>
            @php $indexTrainingStructural=1 @endphp
            @foreach($userTrainingStructural as $value)
            <tr style="border-bottom: 1px solid #F0F0F0;">
                <td class="table-section-3-body">{{ $indexTrainingStructural++ }}</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="title-profile">Riwayat Pelatihan Fungsional</div>

    <table class="table-section-3">
        <thead class="table-section-3-title-row">
            <td class="table-section-3-title">No</td>
            <td class="table-section-3-title">Nama Diklat</td>
            <td class="table-section-3-title">No Surat Perintah</td>
            <td class="table-section-3-title">Jenjang</td>
            <td class="table-section-3-title">Tanggal Pelaksanaan</td>
            <td class="table-section-3-title">Durasi Pelatihan (Hari)</td>
            <td class="table-section-3-title">Penyelenggara</td>
            <td class="table-section-3-title">Sertifikat</td>
        </thead>

        <tbody>
            @php $indexTrainingFunctional=1 @endphp
            @foreach($userTrainingFunctional as $value)
            <tr style="border-bottom: 1px solid #F0F0F0;">
                <td class="table-section-3-body">{{ $indexTrainingFunctional++ }}</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="title-profile">Riwayat Pelatihan Teknis</div>

    <table class="table-section-3">
        <thead class="table-section-3-title-row">
            <td class="table-section-3-title">No</td>
            <td class="table-section-3-title">Nama Diklat</td>
            <td class="table-section-3-title">No Surat Perintah</td>
            <td class="table-section-3-title">Tanggal Pelaksanaan</td>
            <td class="table-section-3-title">Durasi Pelatihan (Hari)</td>
            <td class="table-section-3-title">Sertifikat</td>
        </thead>

        <tbody>
            @php $indexTrainingTechnical=1 @endphp
            @foreach($userTrainingTechnical as $value)
            <tr style="border-bottom: 1px solid #F0F0F0;">
                <td class="table-section-3-body">{{ $indexTrainingTechnical++ }}</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="title-profile">Riwayat Penghargaan</div>

    <table class="table-section-3">
        <thead class="table-section-3-title-row">
            <td class="table-section-3-title">No</td>
            <td class="table-section-3-title">Nama Penghargaan</td>
            <td class="table-section-3-title">Keterangan Penghargaan</td>
            <td class="table-section-3-title">Jenis SK</td>
            <td class="table-section-3-title">Tanggal SK</td>
            <td class="table-section-3-title">No SK Penghargaan</td>
            <td class="table-section-3-title">Tahun SK</td>
            <td class="table-section-3-title">Instansi Pemberi Penghargaan</td>
            <td class="table-section-3-title">Tanggal Terima</td>
        </thead>

        <tbody>
            @php $indexAward=1 @endphp
            @foreach($userAward as $value)
            <tr style="border-bottom: 1px solid #F0F0F0;">
                <td class="table-section-3-body">{{ $indexAward++ }}</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="title-profile">Riwayat SKP</div>

    <table class="table-section-3">
        <thead class="table-section-3-title-row">
            <td class="table-section-3-title">No</td>
            <td class="table-section-3-title">Nilai SKP</td>
            <td class="table-section-3-title">Nilai Perilaku</td>
            <td class="table-section-3-title">Nilai Prestasi</td>
            <td class="table-section-3-title">Periode</td>
            <td class="table-section-3-title">Tahun</td>
        </thead>

        <tbody>
            @php $indexSKP=1 @endphp
            @foreach($userSKP as $value)
            <tr style="border-bottom: 1px solid #F0F0F0;">
                <td class="table-section-3-body">{{ $indexSKP++ }}</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="title-profile">Riwayat Penilaian Prestasi Kerja</div>

    <table class="table-section-3">
        <thead class="table-section-3-title-row">
            <td class="table-section-3-title">No</td>
            <td class="table-section-3-title">Periode PPK</td>
            <td class="table-section-3-title">Nilai Prestasi Kerja</td>
            <td class="table-section-3-title">Keterangan</td>
        </thead>

        <tbody>
            @php $indexPerformance=1 @endphp
            @foreach($userPerformance as $value)
            <tr style="border-bottom: 1px solid #F0F0F0;">
                <td class="table-section-3-body">{{ $indexPerformance++ }}</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="title-profile">Riwayat Hukuman Disiplin</div>

    <table class="table-section-3">
        <thead class="table-section-3-title-row">
            <td class="table-section-3-title">No</td>
            <td class="table-section-3-title">Golongan</td>
            <td class="table-section-3-title">Jabatan</td>
            <td class="table-section-3-title">Hukuman Disiplin</td>
            <td class="table-section-3-title">No SK Hukuman Disiplin</td>
            <td class="table-section-3-title">Tanggal SK Hukuman Disiplin</td>
            <td class="table-section-3-title">Tanggal Hukuman Disiplin</td>
            <td class="table-section-3-title">Status</td>
            <td class="table-section-3-title">Uraian</td>
            <td class="table-section-3-title">Pejabat Berwenang</td>
            <td class="table-section-3-title">Nama Pejabat Berwenang</td>
            <td class="table-section-3-title">Tingkat Hukuman</td>
            <td class="table-section-3-title">Jenis Hukuman</td>
            <td class="table-section-3-title">Masa Berlaku</td>
        </thead>

        <tbody>
            @php $indexPunishment=1 @endphp
            @foreach($userPunishment as $value)
            <tr style="border-bottom: 1px solid #F0F0F0;">
                <td class="table-section-3-body">{{ $indexPunishment++ }}</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="title-profile">Riwayat Keluarga</div>

    <table class="table-section-3">
        <thead class="table-section-3-title-row">
            <td class="table-section-3-title">No</td>
            <td class="table-section-3-title">No Kartu Keluarga</td>
            <td class="table-section-3-title">Nama Anggota Keluarga</td>
            <td class="table-section-3-title">No NIK</td>
            <td class="table-section-3-title">Jenis Kelamin</td>
            <td class="table-section-3-title">Agama</td>
            <td class="table-section-3-title">Tempat Lahir</td>
            <td class="table-section-3-title">Tanggal Lahir</td>
            <td class="table-section-3-title">Nama Bapak</td>
            <td class="table-section-3-title">Nama Ibu</td>
            <td class="table-section-3-title">Hubungan Keluarga</td>
            <td class="table-section-3-title">Pendidikan</td>
            <td class="table-section-3-title">Jenis Pekerjaan</td>
            <td class="table-section-3-title">Keterangan Pekerjaan</td>
            <td class="table-section-3-title">Status Perkawinan</td>
            <td class="table-section-3-title">No HP</td>
            <td class="table-section-3-title">Urut Keluarga</td>
        </thead>

        <tbody>
            @php $indexFamily=1 @endphp
            @foreach($userFamily as $value)
            <tr style="border-bottom: 1px solid #F0F0F0;">
                <td class="table-section-3-body">{{ $indexFamily++ }}</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="title-profile">Riwayat Cuti</div>

    <table class="table-section-3">
        <thead class="table-section-3-title-row">
            <td class="table-section-3-title">No</td>
            <td class="table-section-3-title">Golongan</td>
            <td class="table-section-3-title">Jabatan</td>
            <td class="table-section-3-title">Periode</td>
            <td class="table-section-3-title">Alasan</td>
            <td class="table-section-3-title">No Cuti</td>
            <td class="table-section-3-title">Tujuan</td>
            <td class="table-section-3-title">Surat Cuti</td>
        </thead>

        <tbody>
            @php $indexPaidLeave=1 @endphp
            @foreach($userPaidLeave as $value)
            <tr style="border-bottom: 1px solid #F0F0F0;">
                <td class="table-section-3-body">{{ $indexPaidLeave++ }}</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="title-profile">Catatan</div>

    <table class="table-section-3">
        <thead class="table-section-3-title-row">
            <td class="table-section-3-title">No</td>
            <td class="table-section-3-title">Tanggal</td>
            <td class="table-section-3-title">Pemberi Catatan</td>
            <td class="table-section-3-title">Catatan</td>
        </thead>

        <tbody>
            @php $indexNotes=1 @endphp
            @foreach($userNotes as $value)
            <tr style="border-bottom: 1px solid #F0F0F0;">
                <td class="table-section-3-body">{{ $indexNotes++ }}</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
                <td class="table-section-3-body">Lorem Ipsum</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>