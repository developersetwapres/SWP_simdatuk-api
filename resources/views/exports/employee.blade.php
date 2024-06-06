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
                @if($toggleField['isOrganization'])
                    <th class="section-header-color">Organisasi</th>
                @endif
                @if($toggleField['isWorkUnit'])
                    <th class="section-header-color">Unit Kerja</th>
                @endif
                @if($toggleField['isNoWorker'])
                    <th class="section-header-color">No. Pegawai</th>
                @endif
                @if($toggleField['workDuration'])
                    <th class="section-header-color">Lama Bekerja</th>
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
                    <td>{{ $value['position_description'] }}</td>
                @endif
                @if($toggleField['isEchelons'])
                    <td>{{ $value['echelons_name'] }}</td>
                @endif
                @if($toggleField['isGrade'])
                    <td>{{ $value['grade_name'] }}</td>
                @endif
                @if($toggleField['isNip'])
                    <td>{{ $value['employee_id_number'] }}</td>
                @endif
                @if($toggleField['isBirthPlaceDate'])
                    <td>{{ $value['place_of_birth'] }}, {{ $value['date_of_birth'] }}</td>
                @endif
                @if($toggleField['isAge'])
                    <td>{{ $value['age'] }}</td>
                @endif
                @if($toggleField['isReligion'])
                    <td>{{ $value['religion'] }}</td>
                @endif
                @if($toggleField['isGender'])
                    <td>{{ $value['gender'] }}</td>
                @endif
                @if($toggleField['isMaritalStatus'])
                    <td>{{ $value['marital_status'] }}</td>
                @endif
                @if($toggleField['isAgency'])
                    <td>{{ $value['institution_name'] }}</td>
                @endif
                @if($toggleField['isOrganization'])
                    <td>{{ $value['organization_name'] }}</td>
                @endif
                @if($toggleField['isWorkUnit'])
                    <td>{{ $value['work_unit'] }}</td>
                @endif
                @if($toggleField['isNoWorker'])
                    <td>{{ $value['employee_id_number'] }} / {{ $value['employee_id_card_number'] }}</td>
                @endif
                @if($toggleField['workDuration'])
                    <td>{{ $value['work_duration'] }}</td>
                @endif
                @if($toggleField['isGradeDuration'])
                    <td>{{ $value['grade_effective_date'] }}</td>
                @endif
                @if($toggleField['isNPWP'])
                    <td>{{ $value['id_tax'] }}</td>
                @endif
                @if($toggleField['isEmployeeStatus'])
                    <td>{{ $value['employee_status'] }}</td>
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
                @if($toggleField['isPensionCap'])
                    <td>{{ $value['pension_cap'] }}</td>
                @endif
                @if($toggleField['isPositionHistory'])
                    <td>{{ $value['position_history'] }}</td>
                @endif
                @if($toggleField['isGradeHistory'])
                    <td>{{ $value['grade_history'] }}</td>
                @endif
                @if($toggleField['isTrainingStructural'])
                    <td>{{ $value['structural_training_history'] }}</td>
                @endif
                @if($toggleField['isTrainingFunctional'])
                    <td>{{ $value['functional_training_history'] }}</td>
                @endif
                @if($toggleField['isTrainingTechnique'])
                    <td>{{ $value['technique_training_history'] }}</td>
                @endif
            </tr>
        @endforeach

        </tbody>
    </table>
