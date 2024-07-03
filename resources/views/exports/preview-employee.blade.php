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
            @if($toggleField['isName'])
                <th class="section-header-color">Nama</th>
            @endif
            @if($toggleField['isPosition'])
                <th class="section-header-color">Jabatan</th>
            @endif
            @if($toggleField['isPositionDescription'])
                <th class="section-header-color">Deskripsi Jabatan</th>
            @endif
            @if($toggleField['isEchelons'])
                <th class="section-header-color">Eselon</th>
            @endif
            @if($toggleField['isGrade'])
                <th class="section-header-color">Grade</th>
            @endif
            @if($toggleField['isNip'])
                <th class="section-header-color">NIP</th>
            @endif
            @if($toggleField['isBirthPlaceDate'])
                <th class="section-header-color">Tempat, Tanggal Lahir</th>
            @endif
            @if($toggleField['isAge'])
                <th class="section-header-color">Usia</th>
            @endif
            @if($toggleField['isReligion'])
                <th class="section-header-color">Agama</th>
            @endif
            @if($toggleField['isGender'])
                <th class="section-header-color">Jenis Kelamin</th>
            @endif
            @if($toggleField['isMaritalStatus'])
                <th class="section-header-color">Status Perkawinan</th>
            @endif
            @if($toggleField['isAgency'])
                <th class="section-header-color">Instansi</th>
            @endif
            @if($toggleField['isWorkUnit'])
                <th class="section-header-color">Unit Kerja</th>
            @endif
            @if($toggleField['isNoWorker'])
                <th class="section-header-color">No. Pegawai</th>
            @endif
            @if($toggleField['isGradeDuration'])
                <th class="section-header-color">Lama Grade</th>
            @endif
            @if($toggleField['isNPWP'])
                <th class="section-header-color">NPWP</th>
            @endif
            @if($toggleField['isEmployeeStatus'])
                <th class="section-header-color">Status Kepegawaian</th>
            @endif
            @if($toggleField['isCurrentAddress'])
                <th class="section-header-color">Alamat Sekarang</th>
            @endif
            @if($toggleField['isComplex'])
                <th class="section-header-color">Perumahan / Kompleks</th>
            @endif
            @if($toggleField['isHomeNumber'])
                <th class="section-header-color">No. Rumah</th>
            @endif
            @if($toggleField['isPhoneNumber'])
                <th class="section-header-color">No. Telepon</th>
            @endif
            @if($toggleField['isOfficeAddress'])
                <th class="section-header-color">Alamat Kantor</th>
            @endif
            @if($toggleField['isOfficeNumber'])
                <th class="section-header-color">No. Kantor</th>
            @endif
            @if($toggleField['isEmail'])
                <th class="section-header-color">Email</th>
            @endif
            @if($toggleField['isOfficeEmail'])
                <th class="section-header-color">Email Dinas</th>
            @endif
            @if($toggleField['isPensionCap'])
                <th class="section-header-color">Maksimal Pensiun</th>
            @endif
            @if($toggleField['isPositionHistory'])
                <th class="section-header-color">Riwayat Jabatan</th>
            @endif
            @if($toggleField['isGradeHistory'])
                <th class="section-header-color">Riwayat Golongan</th>
            @endif
            @if($toggleField['isTrainingStructural'])
                <th class="section-header-color">Riwayat Pelatihan Struktural</th>
            @endif
            @if($toggleField['isTrainingFunctional'])
                <th class="section-header-color">Riwayat Pelatihan Fungsional</th>
            @endif
            @if($toggleField['isTrainingTechnique'])
                <th class="section-header-color">Riwayat Pelatihan Teknik</th>
            @endif
            @if($toggleField['isRecognition'])
                <th class="section-header-color">Riwayat Penghargaan</th>
            @endif
            @if($toggleField['isSKP'])
                <th class="section-header-color">Riwayat SKP</th>
            @endif
            @if($toggleField['isEducationHistory'])
                <th class="section-header-color">Riwayat Edukasi</th>
            @endif
            @if($toggleField['isDisciplinary'])
                <th class="section-header-color">Riwayat Hukuman</th>
            @endif
            @if($toggleField['isFamilyHistory'])
                <th class="section-header-color">Riwayat Keluarga</th>
            @endif
            @if($toggleField['isLeave'])
                <th class="section-header-color">Riwayat Cuti</th>
            @endif
            @if($toggleField['isAssessment'])
                <th class="section-header-color">Hasil Assessment</th>
            @endif
            @if($toggleField['isCompetency'])
                <th class="section-header-color">Hasil Uji Kompetensi</th>
            @endif
            @if($toggleField['isTalentPool'])
                <th class="section-header-color">Hasil Talent Pool</th>
            @endif
            @if($toggleField['isNotes'])
                <th class="section-header-color">Catatan</th>
            @endif
            @if($toggleField['isEmployeeType'])
                <th class="section-header-color">Jenis Pegawai</th>
            @endif
            @if($toggleField['isEchelonDate'])
                <th class="section-header-color">TMT Eselon</th>
            @endif
            @if($toggleField['isGradeDate'])
                <th class="section-header-color">TMT Golongan</th>
            @endif
            @if($toggleField['isKarisu'])
                <th class="section-header-color">No. Karisu</th>
            @endif
            @if($toggleField['isNoFamily'])
                <th class="section-header-color">No. KK</th>
            @endif
            @if($toggleField['isNIK'])
                <th class="section-header-color">No. NIK</th>
            @endif
            @if($toggleField['isStartDate'])
                <th class="section-header-color">Tanggal Mulai Bekerja</th>
            @endif
            @if($toggleField['isDateCPNS'])
                <th class="section-header-color">TMT CPNS</th>
            @endif
            @if($toggleField['isEndDate'])
                <th class="section-header-color">Tanggal Terakhir Bekerja</th>
            @endif
            @if($toggleField['isDatePosition'])
                <th class="section-header-color">Tanggal Mulai Menjabat</th>
            @endif
            @if($toggleField['isOutsourcingType'])
                <th class="section-header-color">Jenis Outsourcing</th>
            @endif
            @if($toggleField['isAssistanceType'])
                <th class="section-header-color">Jenis Perbantuan</th>
            @endif
            @if($toggleField['isEmergencyContact'])
                <th class="section-header-color">Kontak Darurat</th>
            @endif
            @if($toggleField['isWorkDuration'])
                <th class="section-header-color">Masa Kerja Keseluruhan</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @php $indexData = 1 @endphp
        @foreach($userData as $value)
            <tr>
                <td>{{ $indexData++ }}</td>
                @if($toggleField['isName'])
                    <td>{{ $value['name'] }}</td>
                @endif
                @if($toggleField['isPosition'])
                    <td>{{ $value['position_name'] }}</td>
                @endif
                @if($toggleField['isPositionDescription'])
                    <td>{{ $value['description'] }}</td>
                @endif
                @if($toggleField['isEchelons'])
                    <td>{{ $value['echelons_name'] }}</td>
                @endif
                @if($toggleField['isGrade'])
                    <td>{{ $value['grade_name'] }}</td>
                @endif
                @if($toggleField['isNip'])
                    <td>{!! $value['employee_id_card_number'] !!}/{!! $value['employee_registration_number'] !!}}</td>
                @endif
                @if($toggleField['isBirthPlaceDate'])
                    <td>{{ $value['place_of_birth'] }}, {{ $value['date_of_birth'] }}</td>
                @endif
                @if($toggleField['isAge'])
                    <td>{{ $value['age'] }}</td>
                @endif
                @if($toggleField['isReligion'])
                    <td class="table-section-3-body">
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
                        @endswitch</td>
                @endif
                @if($toggleField['isGender'])
                    <td>{{ $value['gender'] === 1 ? 'Pria' : 'Wanita' }}</td>
                @endif
                @if($toggleField['isMaritalStatus'])
                    <td class="table-section-3-body">
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
                        @endswitch</td>
                @endif
                @if($toggleField['isAgency'])
                    <td>{{ $value['institution_name'] }}</td>
                @endif
                @if($toggleField['isWorkUnit'])
                    <td>{{ $value['employee_registration_number'] }}</td>
                @endif
                @if($toggleField['isNoWorker'])
                    <td>{{ $value['employee_id_card_number'] }} / {{ $value['employee_registration_number'] }}</td>
                @endif
                @if($toggleField['isWorkDuration'])
                    <td>{{ $value['position_effective_date'] }}</td>
                @endif
                @if($toggleField['isGradeDuration'])
                    <td>{{ $value['grade_effective_date'] }}</td>
                @endif
                @if($toggleField['isNPWP'])
                    <td>{{ $value['id_tax'] }}</td>
                @endif
                @if($toggleField['isEmployeeStatus'])
                    <td class="table-section-3-body">
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
                        @endswitch</td>
                @endif
                @if($toggleField['isCurrentAddress'])
                    <td>{{ $value['current_address'] }}</td>
                @endif
                @if($toggleField['isComplex'])
                    <td>{{ $value['residence_name'] }}</td>
                @endif
                @if($toggleField['isHomeNumber'])
                    <td>{{ $value['home_phone_number'] }}</td>
                @endif
                @if($toggleField['isPhoneNumber'])
                    <td>{{ $value['mobile_phone'] }}</td>
                @endif
                @if($toggleField['isOfficeAddress'])
                    <td>{{ $value['office_address'] }}</td>
                @endif
                @if($toggleField['isOfficeNumber'])
                    <td>{{ $value['office_phone_number'] }}</td>
                @endif
                @if($toggleField['isEmail'])
                    <td>{{ $value['email'] }}</td>
                @endif
                @if($toggleField['isOfficeEmail'])
                    <td>{{ $value['office_email'] }}</td>
                @endif
                @if($toggleField['isPensionCap'])
                    <td>{{ $value['pension_cap'] }}</td>
                @endif
                @if($toggleField['isPositionHistory'])
                    <td>
                        <ul>{!! $value['position_history'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isGradeHistory'])
                    <td>
                        <ul>{!! $value['grade_history'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isTrainingStructural'])
                    <td>
                        <ul>{!! $value['structural_training_history'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isTrainingFunctional'])
                    <td>
                        <ul>{!! $value['functional_training_history'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isTrainingTechnique'])
                    <td>
                        <ul>{!! $value['technique_training_history'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isRecognition'])
                    <td>
                        <ul>{!! $value['recognition_history'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isSKP'])
                    <td>
                        <ul>{!! $value['skp_history'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isEducationHistory'])
                    <td>
                        <ul>{!! $value['education_history'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isDisciplinary'])
                    <td>
                        <ul>{!! $value['disciplinary_history'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isFamilyHistory'])
                    <td>
                        <ul>{!! $value['family_history'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isLeave'])
                    <td>
                        <ul>{!! $value['leave_history'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isAssessment'])
                    <td>
                        <ul>{!! $value['assessment_history'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isCompetency'])
                    <td>
                        <ul>{!! $value['competency_history'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isTalentPool'])
                    <td>
                        <ul>{!! $value['talent_pool_history'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isNotes'])
                    <td>
                        <ul>{!! $value['notes'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isEmployeeType'])
                    <td>
                        <ul>{!! $value['employee_type'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isEchelonDate'])
                    <td>
                        <ul>{!! $value['echelon_effective_date'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isGradeDate'])
                    <td>
                        <ul>{!! $value['grade_effective_date'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isKarisu'])
                    <td>
                        <ul>{!! $value['karisu_number'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isNoFamily'])
                    <td>
                        <ul>{!! $value['family_registration_number'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isNIK'])
                    <td>
                        <ul>{!! $value['id_number'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isStartDate'])
                    <td>
                        <ul>{!! $value['pns_effective_date'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isDateCPNS'])
                    <td>
                        <ul>{!! $value['cpns_effective_date'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isEndDate'])
                    <td>
                        <ul>{!! $value['retirement_effective_date'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isDatePosition'])
                    <td>
                        <ul>{!! $value['position_effective_date'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isOutsourcingType'])
                    <td>
                        <ul>{!! $value['outsource_type'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isAssistanceType'])
                    <td>
                        <ul>{!! $value['assistance_type'] !!}</ul>
                    </td>
                @endif
                @if($toggleField['isEmergencyContact'])
                    <td>
                        <ul>{!! $value['emergency_contact'] !!}</ul>
                    </td>
                @endif
            </tr>
        @endforeach

        </tbody>
    </table>
