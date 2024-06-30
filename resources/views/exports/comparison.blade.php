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
            margin: 30px 50px;
        }
        .title {
            font-size: 21px;
            font-weight: 700;
        }

        .sub-title {
            font-size: 16px;
            font-weight: 600;
            margin-top: 2px;
            margin-bottom: 50px;
        }
        td {
          font-size:12px;
        }
        .avatar {
          width: 150px;
        }
        .image {
          text-align: center;
        }
        .title-name {
          color: #895700;
          font-size: 16px;
          font-weight: bold;
        }
        .title-sidebar {
          border-bottom: 2px solid #F0F0F0;
          padding-bottom: 10px;
          text-align: left;
          vertical-align: top;
          font-weight: bold;
        }
        .content {
          border-bottom: 2px solid #F0F0F0;
          padding-bottom: 5px;
          text-align: left;
          vertical-align: top;
          padding-right: 20px;
        }
        ol {
          text-align: left;
          padding-left: 15px;
          margin-top:0px;
        }
    </style>
</head>

<body>

    <!-- start of page 1 -->
    <header>
        <img src='img/setneg-logo.png' style="height: 50px;" />
    </header>

    <center>
        <div class="title">
            {{$title}}
        </div>
        <div class="sub-title">
            Per Tanggal : {{$date}}
        </div>
    </center>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
          <td style="width: 230px;"></td>
            @foreach($data['users'] as $user)
            <td class="image">
              <img src="{{$user->photo_profile}}" class="avatar" /><br>
              <p class="title-name">
                {{$user->name}}
              </p>
            </td>
            @endforeach
        </tr>
        <tr>
          <td class="title-sidebar">Jabatan</td>
          @foreach($data['users'] as $user)
          <td class="content">{{$user->position_name}}</td>
          @endforeach
        </tr>
        <tr>
          <td class="title-sidebar">Eselon</td>
          @foreach($data['users'] as $user)
          <td class="content">{{$user->echelon_name}},{{$user->echelon_effective_date}}</td>
          @endforeach
        </tr>
        <tr>
          <td class="title-sidebar">Golongan</td>
          @foreach($data['users'] as $user)
          <td class="content">{{$user->grade_name}},{{$user->grade_effective_date}}</td>
          @endforeach
        </tr>
        <tr>
          <td class="title-sidebar">Pendidikan Terakhir</td>
          @foreach($data['users'] as $user)
          <td class="content">{{$user->position_name}}</td>
          @endforeach
        </tr>
        <tr>
          <td class="title-sidebar">Riwayat Jabatan</td>
          @foreach($data['positions'] as $position)
          <td class="content">
            <ol>
              @foreach($position as $item)
              <li>{{$item['position']}}</li>
              @endforeach
            </ol>
          </td>
          @endforeach
        </tr>
        <tr>
          <td class="title-sidebar">Riwayat Pelatihan Struktural</td>
          @foreach($data['strukturals'] as $struktural)
          <td class="content">
            <ol>
              @foreach($struktural as $item)
              <li>{{$item['name']}}</li>
              @endforeach
            </ol>
          </td>
          @endforeach
        </tr>
        <tr>
          <td class="title-sidebar">Riwayat Pelatihan Fungsional</td>
          @foreach($data['fungsionals'] as $fungsional)
          <td class="content">
            <ol>
              @foreach($fungsional as $item)
              <li>{{$item['name']}}</li>
              @endforeach
            </ol>
          </td>
          @endforeach
        </tr>
        <tr>
          <td class="title-sidebar">Riwayat Pelatihan Teknis</td>
          @foreach($data['tekniss'] as $teknis)
          <td class="content">
            <ol>
              @foreach($teknis as $item)
              <li>{{$item['name']}}</li>
              @endforeach
            </ol>
          </td>
          @endforeach
        </tr>
        <tr>
          <td class="title-sidebar">Penilaian SKP (2 Tahun Terakhir)</td>
          <td class="content">
            <ol>
              <li>Rating Perilaku Kerja : Di bawah ekspektasi, Predikat Kinerja Pegawai : Baik</li>
              <li>Rating Perilaku Kerja : Di bawah ekspektasi, Predikat Kinerja Pegawai : Baik</li>
            </ol>
          </td>
          <td class="content">
            <ol>
              <li>Rating Perilaku Kerja : Di bawah ekspektasi, Predikat Kinerja Pegawai : Baik</li>
              <li>Rating Perilaku Kerja : Di bawah ekspektasi, Predikat Kinerja Pegawai : Baik</li>
            </ol>
          </td>
        </tr>
        <tr>
          <td class="title-sidebar">Riwayat Hukuman Displin</td>
          <td class="content">
            <ol>
              <li>Ringan, 12-12-2023</li>
              <li>Ringan, 12-12-20123</li>
            </ol>
          </td>
          <td class="content">
            <ol>
              <li>Ringan, 12-12-2023</li>
              <li>Ringan, 12-12-20123</li>
            </ol>
          </td>
        </tr>
        <tr>
          <td class="title-sidebar">Catatan</td>
          <td class="content">
            <ol>
              <li>Sangat rajin dan selalu bis3a diandalkan</li>
              <li>Sangat rajin dan selalu bisa diandalkan</li>
            </ol>
          </td>
          <td class="content">
            <ol>
              <li>Sangat rajin dan selalu bis3a diandalkan</li>
              <li>Sangat rajin dan selalu bisa diandalkan</li>
            </ol>
          </td>
        </tr>
        <tr>
          <td class="title-sidebar">Hasil Assessment</td>
          <td class="content">
            <ol>
              <li>26-04-2024, Baik</li>
              <li>26-04-2024, Baik1</li>
            </ol>
          </td>
          <td class="content">
            <ol>
              <li>26-04-2024, Baik</li>
              <li>26-04-2024, Baik1</li>
            </ol>
          </td>
        </tr>
        <tr>
          <td class="title-sidebar">Hasil Uji Kompetensi</td>
          <td class="content">
            <ol>
              <li>6-04-2024, 80</li>
              <li>6-04-2024, 80</li>
            </ol>
          </td>
          <td class="content">
            <ol>
              <li>6-04-2024, 80</li>
              <li>6-04-2024, 80</li>
            </ol>
          </td>
        </tr>
        <tr>
          <td class="title-sidebar">Hasil Talent Pool</td>
          <td class="content">
            <ol>
              <li>26-04-2024, 80</li>
              <li>26-04-2024, 81</li>
            </ol>
          </td>
          <td class="content">
            <ol>
              <li>26-04-2024, 80</li>
              <li>26-04-2024, 81</li>
            </ol>
          </td>
        </tr>
    </table>
</body>
</html>
