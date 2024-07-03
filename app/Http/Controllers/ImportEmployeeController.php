<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Imports\EmployeesImport;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class ImportEmployeeController extends Controller
{
    protected $type;

    protected $skippedRow = [];

    /**
     * Maps the column names to their respective indexes in the `Data Pegawai` Sheet.
     */
    protected $personalInfoPos = [
        'name' => 0,
        'employee_id_number' => 1, // NIP
        'employee_registration_number' => 2, // NRP
        'title_prefix' => 3, // Nama Gelar Depan
        'title_suffix' => 4, // Nama Gelar Belakang
        'place_of_birth' => 5,
        'date_of_birth' => 6,
        'religion' => 7,
        'gender' => 8,
        'marital_status' => 9,
        'employment_type' => 10, // Jenis Pegawai
        'outsource_type' => 11, // Jenis Outsource
        'grade' => 12, // Golongan
        'grade_effective_date' => 13, // TMT Golongan
        'echelon' => 14, // Eselon
        'echelon_effective_date' => 15, // TMT Eselon
        'institution' => 16, // Instansi Induk
        'education_level' => 17, // Tingkat
        'education_name' => 18, // Nama Sekolah/Universitas
        'education_year' => 19, // Tahun Lulus
        'employee_id_card_number' => 20, // No Karpeg
        'karisu_number' => 21,
        'id_tax' => 22, // NPWP
        'employment_status' => 23, // Status Pegawai
        'family_registration_number' => 24, // No KK
        'id_number' => 25, // NIK
        'residence' => 26, // Komplek
        'current_address' => 27, // Alamat Tempat Tinggal Saat Ini
        'home_phone_number' => 28, // No Telepon Rumah
        'mobile_phone' => 29, // No HP
        'office_address' => 30, // Alamat Kantor
        'office_phone_number' => 31, // No Telepon Kantor
        'email' => 32,
        'emergency_contact' => 33 // Kontak Darurat
    ];

    /**
     * Maps the column names to their respective indexes in the `Pendidikan` Sheet.
     */
    protected $educationInfoPos = [
        'nik' => 0,
        'level' => 1,
        'name' => 2,
        'faculty' => 3,
        'major' => 4,
        'status' => 5,
        'year_of_graduation' => 6,
        'description' => 7,
    ];

    /**
     * Maps the column names to their respective indexes in the `Keluarga` Sheet.
     */
    protected $familyInfoPos = [
        'nik' => 0,
        'card_number' => 1, // No. Kartu Keluarga
        'name' => 2, // Nama Anggota Keluarga
        'id_number' => 3, // No. NIK
        'gender' => 4,
        'religion' => 5,
        'place_of_birth' => 6, // Tempat Lahir
        'date_of_birth' => 7, // Tanggal Lahir
        'name_of_father' => 8, // Nama Bapak
        'name_of_mother' => 9, // Nama Ibu
        'relationship_status' => 10, // Hubungan Keluarga
        'education' => 11, // Pendidikan
        'occupation' => 12, // Jenis Pekerjaan
        'occupation_description' => 13, // Keterangan Pekerjaan
        'marital_status' => 14, // Status Perkawinan
        'mobile_phone' => 15, //No. HP
        'sequence_number' => 16, // Urut Keluarga
    ];

    /**
     * Maps the column names to their respective indexes in the `Cuti` Sheet.
     */
    protected $leaveInfoPos = [
        'nik' => 0,
        'start_date' => 1, // Tanggal Awal Cuti
        'end_date' => 2, // Tanggal Akhir Cuti
        'type' => 3, // Jenis Cuti
        'number' => 4, // No Cuti
        'description' => 5, // Keterangan
    ];

    /**
     * Maps the column names to their respective indexes in the `Catatan` Sheet.
     */
    protected $noteInfoPos = [
        'nik' => 0,
        'description' => 1
    ];

    /**
     * Maps the column names to their respective indexes in the `Hasil Assessment` Sheet.
     */
    protected $assessmentInfoPos = [
        'nik' => 0,
        'event_date' => 1,
        'point' => 2,
        'organizer' => 3,
    ];

    /**
     * Maps the column names to their respective indexes in the `Hasil Uji Kompetensi` Sheet.
     */
    protected $competencyInfoPos = [
        'nik' => 0,
        'event_date' => 1,
        'point' => 2,
        'organizer' => 3,
    ];

    /**
     * Maps the column names to their respective indexes in the `Hasil Talent Pool` Sheet.
     */
    protected $talentInfoPos = [
        'nik' => 0,
        'event_date' => 1,
        'point' => 2,
        'organizer' => 3,
    ];


    // Array mapping gender to their respective numeric codes
    protected $gender = [
        'wanita' => 0,
        'pria' => 1,
    ];

    // Array mapping religions to their respective numeric codes
    protected $religions = [
        'islam' => 1,
        'kristen' => 2,
        'katolik' => 3,
        'hindu' => 4,
        'budha' => 5,
        'konghucu' => 6
    ];

    // Array mapping marital status to their respective numeric codes
    protected $maritalStatus = [
        'belum_menikah' => 1,
        'menikah' => 2,
        'cerai' => 3,
        'janda' => 4,
        'duda' => 5
    ];

    // Array mapping education level to their respective numeric codes
    protected $educationLevel = [
        'sd/sederajat' => 1,
        'sltp/sederajat' => 2,
        'slta/sederajat' => 3,
        'diploma_i/ii' => 4,
        'akademik/d3/s.muda' => 5,
        'diploma_iv/strata_i' => 6,
        'strata_ii' => 7,
        'strata_iii' => 8
    ];

    // Array mapping education status to their respective numeric codes
    protected $educationStatus = [
        'lulus' => 1,
        'do' => 2,
        'aktif' => 3,
        'non-aktif' => 4,
        'mengundurkan_diri' => 5
    ];

    // Array mapping employment status to their respective numeric codes
    protected $employmentStatus = [
        'aktif' => 1,
        'pensiun' => 2,
        'berhenti' => 3,
        'meninggal' => 4,
        'alih_status' => 5,
        'aktif_perbantuan_setneg' => 6,
        'cltn' => 7,
        'tbln' => 8,
        'non_aktif' => 9
    ];

    // Array mapping family relationship to their respective numeric codes
    protected $familyRelationship = [
        'kepala_keluarga' => 1,
        'suami' => 2,
        'istri' => 3,
        'anak' => 4,
        'menantu' => 5,
        'cucu' => 6,
        'orang_tua' => 7,
        'mertua' => 8,
        'famili_lainnya' => 9,
        'pembantu' => 10,
        'lainnya' => 11,
    ];

    // Array mapping education to their respective numeric codes (for family info)
    protected $familyEducation = [
        'tidak/belum_sekolah' => 1,
        'belum_tamat_sd/sederajat' => 2,
        'tamat_sd/sederajat' => 3,
        'sltp/sederajat' => 4,
        'slta/sederajat' => 5,
        'diploma_i/ii' => 6,
        'akademi/diploma_iii/sarjana_muda' => 7,
        'diploma_iv/strata_i' => 8,
        'strata_ii' => 9,
        'strata_iii' => 10,
    ];

    // Array mapping marital status to their respective numeric codes (for family info)
    protected $familyMaritalStatus = [
        'belum_menikah' => 1,
        'menikah' => 2,
        'cerai_hidup' => 3,
        'cerai_mati' => 4,
    ];

    // Array mapping leave type to their respective numeric codes
    protected $leaveType = [
        'cuti_diluar_tanggungan_negara' => 1,
        'cuti_sakit' => 2,
        'cuti_besar' => 3,
        'cuti_bersalin' => 4,
        'cuti_belajar_luar_negeri' => 5,
        'cuti_tahunan_luar_negeri' => 6,
    ];

    // Array mapping assessment point to their respective numeric codes
    protected $assessmentPoint = [
        'kurang_memenuhi_syarat' => 1,
        'masih_memenuhi_syarat' => 2,
        'memenuhi_syarat' => 3,
    ];

    // Array mapping competency point to their respective numeric codes
    protected $competencyPoint = [
        'lulus' => 1,
        'tidak_lulus' => 2,
    ];

    // Array mapping talent point to their respective numeric codes
    protected $talentPoint = [
        'kotak_1' => 1,
        'kotak_2' => 2,
        'kotak_3' => 3,
        'kotak_4' => 4,
        'kotak_5' => 5,
        'kotak_6' => 6,
        'kotak_7' => 7,
        'kotak_8' => 8,
        'kotak_9' => 9,
    ];

    /**
     * Import excel for add bulk employee
     *
     * Add bulk employee with excel template.
     * @group Employee
     * @authenticated
     * @queryParam type integer Refers to the type of employee 1=ASN 2=NON ASN 3=OUTSOURCE. Example: 1
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @response 400 {"code": 400,"message": "Import pegawai gagal.","data": null}
     * @response 200 {"code": 200,"message": "Import pegawai berhasil.","data": null}
     */
    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
            'type' => 'required|in:1,2,3'
        ], [
            'type' => [
                'in' => 'Tipe Pegawai tidak dikenali.'
            ]
        ]);

        $this->type = $request->type;

        $employeesImport = new EmployeesImport();

        $extension = $request->file('file')->extension();

        // Import sheets
        if ($extension == 'csv') {
            Excel::import($employeesImport, $request->file('file')->path(), null, \Maatwebsite\Excel\Excel::CSV);
        } else {
            Excel::import($employeesImport, $request->file('file')->path(), null, \Maatwebsite\Excel\Excel::XLSX);
        }

        // Extract data from imports
        $employeesData = $employeesImport->data;

        if ($request->type == 3) { // Outsource
            $personalInfo = $employeesData[0] ?? []; // Sheet 1 : Data Pegawai
            $educationInfo = $employeesData[1] ?? []; // Sheet 2 : Riwayat Pendidikan
            $noteInfo = $employeesData[2] ?? []; // Sheet 3 : Riwayat Catatan
        } else { // ASN / NON ASN
            $personalInfo = $employeesData[0] ?? []; // Sheet 1 : Data Pegawai
            $educationInfo = $employeesData[1] ?? []; // Sheet 2 : Pendidikan
            $familyInfo = $employeesData[2] ?? []; // Sheet 3 : Keluarga
            $leaveInfo = $employeesData[3] ?? []; // Sheet 4 : Cuti
            $noteInfo = $employeesData[4] ?? []; // Sheet 5 : Catatan
            $assessmentInfo = $employeesData[5] ?? []; // Sheet 6 : Hasil Assessment
            $competencyInfo = $employeesData[6] ?? []; // Sheet 7 : Hasil Uji Kompetensi
            $talentInfo = $employeesData[7] ?? []; // Sheet 8 : Hasil Talent Pool
        }

        if (sizeOf($personalInfo) == 0) {
            return $this->response(400, 'Import pegawai gagal', ['message' => 'Sheet Data Pegawai kosong']);
        }

        if ($request->type == 3) { // Outsource
            // Process personal info
            $personalInfo = $this->personalInfo($personalInfo);

            // Process education info
            $personalInfo = $this->educationInfo($educationInfo, $personalInfo);

            // Process note info
            $personalInfo = $this->noteInfo($noteInfo, $personalInfo, $request->user()->id);
        } else { // ASN / NON ASN
            // Process personal info
            $personalInfo = $this->personalInfo($personalInfo);

            // Process education info
            $personalInfo = $this->educationInfo($educationInfo, $personalInfo);

            // Process family info
            $personalInfo = $this->familyInfo($familyInfo, $personalInfo);

            // Process leave info
            $personalInfo = $this->leaveInfo($leaveInfo, $personalInfo);

            // Process note info
            $personalInfo = $this->noteInfo($noteInfo, $personalInfo, $request->user()->id);

            // Process assessment info
            $personalInfo = $this->assessmentInfo($assessmentInfo, $personalInfo);

            // Process competency info
            $personalInfo = $this->competencyInfo($competencyInfo, $personalInfo);

            // Process talent info
            $personalInfo = $this->talentInfo($talentInfo, $personalInfo);
        }

        return $this->save($personalInfo, $request->user()->id);
    }

    private function save($personalInfo, $logUserID)
    {
        if (sizeOf($this->skippedRow) > 0) {
            return $this->response(400, 'Import pegawai gagal', $this->skippedRow);
        }

        foreach ($personalInfo as $nik => $data) {
            // Skip. If personal info is empty
            if (!isset($personalInfo[$nik]['personal_info'])) continue;

            try {
                DB::beginTransaction();

                // Save user
                $data['personal_info']['created_at'] = date('Y-m-d');
                $userID = DB::table('users')->insertGetId($data['personal_info']);

                $additionalInfo = [
                    'user_id' => $userID,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                if ($this->type == 3) { // OUTSOURCE
                    // Save Education
                    $data['education'] = $this->mergeValuesIntoArrayElements($additionalInfo, $data['education']);
                    DB::table('user_educations')->insert($data['education']);

                    // Save Notes
                    $data['note'] = $this->mergeValuesIntoArrayElements($additionalInfo, $data['note']);
                    DB::table('user_notes')->insert($data['note']);
                } else { // ASN/NON ASN

                    // Save Education
                    $data['education'] = $this->mergeValuesIntoArrayElements($additionalInfo, $data['education']);
                    DB::table('user_educations')->insert($data['education']);

                    // Save Family
                    $data['family'] = $this->mergeValuesIntoArrayElements($additionalInfo, $data['family']);
                    DB::table('user_families')->insert($data['family']);

                    // Save Leave
                    $data['leave'] = $this->mergeValuesIntoArrayElements($additionalInfo, $data['leave']);
                    DB::table('user_leaves')->insert($data['leave']);

                    // Save Notes
                    $data['note'] = $this->mergeValuesIntoArrayElements($additionalInfo, $data['note']);
                    DB::table('user_notes')->insert($data['note']);

                    // Save Assessment
                    $data['assessment'] = $this->mergeValuesIntoArrayElements($additionalInfo, $data['assessment']);
                    DB::table('user_assessments')->insert($data['assessment']);

                    // Save Competencies
                    $data['competency'] = $this->mergeValuesIntoArrayElements($additionalInfo, $data['competency']);
                    DB::table('user_competencies')->insert($data['competency']);

                    // Save Talents
                    $data['talent'] = $this->mergeValuesIntoArrayElements($additionalInfo, $data['talent']);
                    DB::table('user_talents')->insert($data['talent']);
                }

                DB::commit();
            } catch (Throwable $th) {
                DB::rollback();

                \Log::warning($th);
                return $this->response(500, 'Import pegawai gagal');
            }
        }

        $logType = '';
        $logDescription = '';
        if ($this->type == 1) {
            $logType = 'add-bulk-asn';
            $logDescription = 'Tambah Massal Data Pegawai ASN';
        } else if ($this->type == 2) {
            $logType = 'add-bulk-non-asn';
            $logDescription = 'Tambah Massal Data Pegawai NON ASN';
        } else if ($this->type == 3) {
            $logType = 'add-bulk-outsource';
            $logDescription = 'Tambah Massal Data Pegawai OUTSOURCE';
        }
        DB::table('activity_log')->insert([
            'user_id' => $logUserID,
            'type' => $logType,
            'description' => $logDescription,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        return $this->response(200, 'Import pegawai berhasil');
    }

    private function personalInfo($personalInfo)
    {
        $result = [];
        foreach ($personalInfo as $personalInfoKey => $personalInfoRow) {
            if ($personalInfoKey == 0) continue; // Skip header row

            /**
             * Check Required Field. 
             * Based on rules in App\Http\Requests\Employee\CreateEmployeeRequest
             */
            $requiredFields = [
                'id_number',
                'name',
                'place_of_birth',
                'date_of_birth',
                'religion',
                'gender',
                'marital_status',
                'employment_type',
                'grade',
                'grade_effective_date',
                'institution',
                'education_level',
                'education_name',
                'education_year',
                'employment_status',
                'residence',
                'emergency_contact'
            ];
            $requiredFieldFilled = true;
            foreach ($requiredFields as $key => $field) {
                if (empty($personalInfoRow[$this->personalInfoPos[$field]])) {
                    $requiredFieldFilled = false;

                    $this->skippedRow('Data Pegawai', $personalInfoKey, 'Kolom ' . $personalInfo[0][$this->personalInfoPos[$field]] . ' kosong');
                }
            }
            if (!$requiredFieldFilled) continue; // Skip jika ada required field yang tidak diisi

            // Check Unique
            $user = User::where('email', '=', $personalInfoRow[$this->personalInfoPos['email']])
                ->orWhere('employee_id_number', '=', $personalInfoRow[$this->personalInfoPos['employee_id_number']])
                ->orWhere('employee_registration_number', '=', $personalInfoRow[$this->personalInfoPos['employee_registration_number']])
                ->orWhere('employee_id_card_number', '=', $personalInfoRow[$this->personalInfoPos['employee_id_card_number']])
                ->orWhere('karisu_number', '=', $personalInfoRow[$this->personalInfoPos['karisu_number']])
                ->orWhere('id_tax', '=', $personalInfoRow[$this->personalInfoPos['id_tax']])
                ->orWhere('id_number', '=', $personalInfoRow[$this->personalInfoPos['id_number']])
                ->orWhere('family_registration_number', '=', $personalInfoRow[$this->personalInfoPos['family_registration_number']])
                ->first();
            if ($user !== null) {
                $nonUnique = '';
                if ($user->email == $personalInfoRow[$this->personalInfoPos['email']]) {
                    $nonUnique .= $personalInfo[0][$this->personalInfoPos['email']] . ' = ' . $personalInfoRow[$this->personalInfoPos['email']] . ',';
                }
                if ($user->employee_id_number == $personalInfoRow[$this->personalInfoPos['employee_id_number']]) {
                    $nonUnique .= $personalInfo[0][$this->personalInfoPos['employee_id_number']] . ' = ' . $personalInfoRow[$this->personalInfoPos['employee_id_number']] . ',';
                }
                if ($user->employee_registration_number == $personalInfoRow[$this->personalInfoPos['employee_registration_number']]) {
                    $nonUnique .= $personalInfo[0][$this->personalInfoPos['employee_registration_number']] . ' = ' . $personalInfoRow[$this->personalInfoPos['employee_registration_number']] . ',';
                }
                if ($user->employee_id_card_number == $personalInfoRow[$this->personalInfoPos['employee_id_card_number']]) {
                    $nonUnique .= $personalInfo[0][$this->personalInfoPos['employee_id_card_number']] . ' = ' . $personalInfoRow[$this->personalInfoPos['employee_id_card_number']] . ',';
                }
                if ($user->karisu_number == $personalInfoRow[$this->personalInfoPos['karisu_number']]) {
                    $nonUnique .= $personalInfo[0][$this->personalInfoPos['karisu_number']] . ' = ' . $personalInfoRow[$this->personalInfoPos['karisu_number']] . ',';
                }
                if ($user->id_tax == $personalInfoRow[$this->personalInfoPos['id_tax']]) {
                    $nonUnique .= $personalInfo[0][$this->personalInfoPos['id_tax']] . ' = ' . $personalInfoRow[$this->personalInfoPos['id_tax']] . ',';
                }
                if ($user->id_number == $personalInfoRow[$this->personalInfoPos['id_number']]) {
                    $nonUnique .= $personalInfo[0][$this->personalInfoPos['id_number']] . ' = ' . $personalInfoRow[$this->personalInfoPos['id_number']] . ',';
                }
                if ($user->family_registration_number == $personalInfoRow[$this->personalInfoPos['family_registration_number']]) {
                    $nonUnique .= $personalInfo[0][$this->personalInfoPos['family_registration_number']] . ' = ' . $personalInfoRow[$this->personalInfoPos['family_registration_number']] . ',';
                }

                $this->skippedRow('Data Pegawai', $personalInfoKey, 'Pegawai dengan ' . $nonUnique . ' sudah ada');

                continue; // Skip jika tidak unique
            }

            // Get ID 
            $employmentType = DB::select("SELECT id FROM employment_types WHERE LOWER(REPLACE(name,' ','')) = ? AND status = 1", [str_replace(' ', '', strtolower($personalInfoRow[$this->personalInfoPos['employment_type']]))]);
            $grade = DB::select("SELECT id FROM grades WHERE LOWER(REPLACE(name,' ','')) = ? ", [str_replace(' ', '', strtolower($personalInfoRow[$this->personalInfoPos['grade']]))]);
            $echelon = DB::select("SELECT id FROM echelons WHERE LOWER(REPLACE(name,' ','')) = ? ", [str_replace(' ', '', strtolower($personalInfoRow[$this->personalInfoPos['echelon']]))]);
            $institution = DB::select("SELECT id FROM institutions WHERE LOWER(REPLACE(name,' ','')) = ? ", [str_replace(' ', '', strtolower($personalInfoRow[$this->personalInfoPos['institution']]))]);
            $residence = DB::select("SELECT id FROM residences WHERE LOWER(REPLACE(name,' ','')) = ? ", [str_replace(' ', '', strtolower($personalInfoRow[$this->personalInfoPos['residence']]))]);;

            $employmentTypeID = (sizeof($employmentType) > 0) ? $employmentType[0]->id : null;
            $gradeID = (sizeof($grade) > 0) ? $grade[0]->id : null;
            $echelonID = (sizeof($echelon) > 0) ? $echelon[0]->id : null;
            $institutionID = (sizeof($institution) > 0) ? $institution[0]->id : null;
            $residenceID = (sizeof($residence) > 0) ? $residence[0]->id : null;
            $religionID = $this->findInArray($personalInfoRow[$this->personalInfoPos['religion']], $this->religions);
            $maritalStatusID = $this->findInArray($personalInfoRow[$this->personalInfoPos['marital_status']], $this->maritalStatus);
            $educationLevelID = $this->findInArray($personalInfoRow[$this->personalInfoPos['education_level']], $this->educationLevel);
            $employmentStatusID = $this->findInArray($personalInfoRow[$this->personalInfoPos['employment_status']], $this->employmentStatus);

            // Format Date & Gender
            $dateOfBirth = Carbon::createFromFormat('d/m/Y', $personalInfoRow[$this->personalInfoPos['date_of_birth']])->format('Y-m-d');
            $gradeEffectiveDate = Carbon::createFromFormat('d/m/Y', $personalInfoRow[$this->personalInfoPos['grade_effective_date']])->format('Y-m-d');
            $echelonEffectiveDate = Carbon::createFromFormat('d/m/Y', $personalInfoRow[$this->personalInfoPos['echelon_effective_date']])->format('Y-m-d');
            $gender = $this->findInArray($personalInfoRow[$this->personalInfoPos['gender']], $this->gender);

            $result[$personalInfoRow[$this->personalInfoPos['id_number']]]['personal_info'] = [
                'email' => $personalInfoRow[$this->personalInfoPos['email']],
                'title_prefix' => $personalInfoRow[$this->personalInfoPos['title_prefix']],
                'name' => $personalInfoRow[$this->personalInfoPos['name']],
                'title_suffix' => $personalInfoRow[$this->personalInfoPos['title_suffix']],
                'employee_id_number' => $personalInfoRow[$this->personalInfoPos['employee_id_number']],
                'employee_registration_number' => $personalInfoRow[$this->personalInfoPos['employee_registration_number']],
                'place_of_birth' => $personalInfoRow[$this->personalInfoPos['place_of_birth']],
                'date_of_birth' => $dateOfBirth,
                'religion' => $religionID,
                'gender' => $gender,
                'marital_status' => $maritalStatusID,
                'employment_type_id' => $employmentTypeID,
                'grade_id' => $gradeID,
                'grade_effective_date' => $gradeEffectiveDate,
                'echelon_id' => $echelonID,
                'echelon_effective_date' => $echelonEffectiveDate,
                'institution_id' => $institutionID,
                'education_level' => $educationLevelID,
                'education_name' => $personalInfoRow[$this->personalInfoPos['education_name']],
                'education_year' => $personalInfoRow[$this->personalInfoPos['education_year']],
                'employee_id_card_number' => $personalInfoRow[$this->personalInfoPos['employee_id_card_number']],
                'karisu_number' =>  $personalInfoRow[$this->personalInfoPos['karisu_number']],
                'id_tax' => $personalInfoRow[$this->personalInfoPos['id_tax']],
                'employment_status' => $employmentStatusID,
                'id_number' => $personalInfoRow[$this->personalInfoPos['id_number']],
                'family_registration_number' => $personalInfoRow[$this->personalInfoPos['family_registration_number']],
                'residence_id' => $residenceID,
                'current_address' => $personalInfoRow[$this->personalInfoPos['current_address']],
                'home_phone_number' => $personalInfoRow[$this->personalInfoPos['home_phone_number']],
                'mobile_phone' => $personalInfoRow[$this->personalInfoPos['mobile_phone']],
                'office_address' => $personalInfoRow[$this->personalInfoPos['office_address']],
                'office_phone_number' => $personalInfoRow[$this->personalInfoPos['office_phone_number']],
                'emergency_contact' => $personalInfoRow[$this->personalInfoPos['emergency_contact']],
                'type' => $this->type
            ];
        }

        return $result;
    }

    private function educationInfo($educationInfo, $personalInfo)
    {
        foreach ($educationInfo as $educationKey => $educationRow) {
            if ($educationKey == 0) continue; // Skip header row

            $levelID = $this->findInArray($educationRow[$this->educationInfoPos['level']], $this->educationLevel);
            $statusID = $this->findInArray($educationRow[$this->educationInfoPos['status']], $this->educationStatus);

            $personalInfo[$educationRow[$this->educationInfoPos['nik']]]['education'][] = [
                'level' => $levelID,
                'name' => $educationRow[$this->educationInfoPos['name']],
                'faculty' => $educationRow[$this->educationInfoPos['faculty']],
                'major' => $educationRow[$this->educationInfoPos['major']],
                'status' => $statusID,
                'year_of_graduation' => $educationRow[$this->educationInfoPos['year_of_graduation']],
                'description' => $educationRow[$this->educationInfoPos['description']],
            ];
        }

        return $personalInfo;
    }

    private function familyInfo($familyInfo, $personalInfo)
    {
        foreach ($familyInfo as $familyKey => $familyRow) {
            if ($familyKey == 0) continue; // Skip header row

            $genderID = $this->findInArray($familyRow[$this->familyInfoPos['gender']], $this->gender);
            $religionID = $this->findInArray($familyRow[$this->familyInfoPos['religion']], $this->religions);
            $familyRelationshipID = $this->findInArray($familyRow[$this->familyInfoPos['relationship_status']], $this->familyRelationship);
            $familyEducationID = $this->findInArray($familyRow[$this->familyInfoPos['education']], $this->familyEducation);
            $familyMaritalStatusID = $this->findInArray($familyRow[$this->familyInfoPos['marital_status']], $this->familyMaritalStatus);

            $birthDate = Carbon::createFromFormat('d/m/Y', $familyRow[$this->familyInfoPos['date_of_birth']])->format('Y-m-d');

            $personalInfo[$familyRow[$this->familyInfoPos['nik']]]['family'][] = [
                'card_number' => $familyRow[$this->familyInfoPos['card_number']],
                'name' => $familyRow[$this->familyInfoPos['name']],
                'id_number' => $familyRow[$this->familyInfoPos['id_number']],
                'gender' => $genderID,
                'religion' => $religionID,
                'place_of_birth' => $familyRow[$this->familyInfoPos['place_of_birth']],
                'date_of_birth' => $birthDate,
                'name_of_father' => $familyRow[$this->familyInfoPos['name_of_father']],
                'name_of_mother' => $familyRow[$this->familyInfoPos['name_of_mother']],
                'relationship_status' => $familyRelationshipID,
                'education' => $familyEducationID,
                'occupation' => $familyRow[$this->familyInfoPos['occupation']],
                'occupation_description' => $familyRow[$this->familyInfoPos['occupation_description']],
                'marital_status' => $familyMaritalStatusID,
                'mobile_phone' => $familyRow[$this->familyInfoPos['mobile_phone']],
                'sequence_number' => $familyRow[$this->familyInfoPos['sequence_number']]
            ];
        }

        return $personalInfo;
    }

    private function leaveInfo($leaveInfo, $personalInfo)
    {
        foreach ($leaveInfo as $leaveKey => $leaveRow) {
            if ($leaveKey == 0) continue; // Skip header row

            $leaveTypeID = $this->findInArray($leaveRow[$this->leaveInfoPos['type']], $this->leaveType);

            $startDate = Carbon::createFromFormat('d/m/Y', $leaveRow[$this->leaveInfoPos['start_date']])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $leaveRow[$this->leaveInfoPos['end_date']])->format('Y-m-d');

            $personalInfo[$leaveRow[$this->leaveInfoPos['nik']]]['leave'][] = [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'type' => $leaveTypeID,
                'number' => $leaveRow[$this->leaveInfoPos['number']],
                'description' => $leaveRow[$this->leaveInfoPos['description']],
            ];
        }

        return $personalInfo;
    }

    private function noteInfo($noteInfo, $personalInfo, $giverID)
    {
        foreach ($noteInfo as $noteKey => $noteRow) {
            if ($noteKey == 0) continue; // Skip header row

            $personalInfo[$noteRow[$this->noteInfoPos['nik']]]['note'][] = [
                'giver_id' => $giverID,
                'description' => $noteRow[$this->noteInfoPos['description']],
            ];
        }

        return $personalInfo;
    }

    private function assessmentInfo($assessmentInfo, $personalInfo)
    {
        foreach ($assessmentInfo as $assessmentKey => $assessmentRow) {
            if ($assessmentKey == 0) continue; // Skip header row

            $assessmentPointID = $this->findInArray($assessmentRow[$this->assessmentInfoPos['point']], $this->assessmentPoint);

            $eventDate = Carbon::createFromFormat('d/m/Y', $assessmentRow[$this->assessmentInfoPos['event_date']])->format('Y-m-d');

            $personalInfo[$assessmentRow[$this->assessmentInfoPos['nik']]]['assessment'][] = [
                'event_date' => $eventDate,
                'point' => $assessmentPointID,
                'organizer' => $assessmentRow[$this->assessmentInfoPos['organizer']],
            ];
        }

        return $personalInfo;
    }

    private function competencyInfo($competencyInfo, $personalInfo)
    {
        foreach ($competencyInfo as $competencyKey => $competencyRow) {
            if ($competencyKey == 0) continue; // Skip header row

            $competencyPointID = $this->findInArray($competencyRow[$this->competencyInfoPos['point']], $this->competencyPoint);

            $eventDate = Carbon::createFromFormat('d/m/Y', $competencyRow[$this->competencyInfoPos['event_date']])->format('Y-m-d');

            $personalInfo[$competencyRow[$this->competencyInfoPos['nik']]]['competency'][] = [
                'event_date' => $eventDate,
                'point' => $competencyPointID,
                'organizer' => $competencyRow[$this->competencyInfoPos['organizer']],
            ];
        }

        return $personalInfo;
    }

    private function talentInfo($talentInfo, $personalInfo)
    {
        foreach ($talentInfo as $talentKey => $talentRow) {
            if ($talentKey == 0) continue; // Skip header row

            $talentPointID = $this->findInArray($talentRow[$this->talentInfoPos['point']], $this->talentPoint);

            $eventDate = Carbon::createFromFormat('d/m/Y', $talentRow[$this->talentInfoPos['event_date']])->format('Y-m-d');

            $personalInfo[$talentRow[$this->talentInfoPos['nik']]]['talent'][] = [
                'event_date' => $eventDate,
                'point' => $talentPointID,
                'organizer' => $talentRow[$this->talentInfoPos['organizer']],
            ];
        }

        return $personalInfo;
    }



    /**
     * Finds a value in an array based on a given key.
     *
     * This function converts the provided key to lowercase,
     * replaces spaces with underscores, and then searches for
     * this key in the given array. If the key is found, it returns
     * the associated value; otherwise, it returns null.
     *
     * @param string $find The key to search for.
     * @param array $array The array to search in.
     * @return mixed|null The value associated with the key, or null if not found.
     */
    private function findInArray($find, $array)
    {
        $find = strtolower($find);
        $find = str_replace(' ', '_', $find);
        return $array[$find] ?? null;
    }

    /**
     * Merges an array of values into each element of another array.
     *
     * @param array $injectValue The array of values to inject into each element of the main array.
     * @param array $array The main array whose elements will be modified.
     * @return array The modified array with injected values.
     */
    private function mergeValuesIntoArrayElements(array $injectValue, array $array)
    {
        foreach ($array as $key => $value) {
            // Merge $injectValue into each element of $array and assign it back to the corresponding key
            $array[$key] = array_merge($value, $injectValue);
        }
        return $array;
    }

    private function skippedRow($sheet, $row, $reason = '')
    {
        $this->skippedRow[] = [
            'sheet' => $sheet,
            'row' => $row,
            'reason' => $reason
        ];
    }

    /**
     * Download Excel Template Add Bulk Employee
     *
     * Download Excel Template to add bulk ASN/NON-ASN/OUTSOURCE employee.
     * @group Employee
     * @authenticated
     * @urlParam type integer Refers to the type of employee 1=ASN 2=NON ASN 3=OUTSOURCE. Example: 1
     * @response 404 {"code": 404,"message": "File not found.","data": null}
     * @response 200 file downloaded
     */
    public function downloadTemplate($type)
    {
        if ($type == 1) {
            $fileName = "DATA PEGAWAI ASN";
        } else if ($type == 2) {
            $fileName = "DATA PEGAWAI NON ASN";
        } else if ($type == 3) {
            $fileName = "DATA PEGAWAI OUTSOURCE";
        } else {
            return $this->response(404, 'File not found.');
        }

        $filePath = public_path('template_excel/' . $fileName . '.xlsx');

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return $this->response(404, 'File not found.');
        }
    }

    /**
     * Get List of Import Histories
     *
     * Retrieve all Import Histories.
     * @group Employee
     * Below are the Endpoints for retrieving import histories:
     * @authenticated
     * @queryParam type integer Refers to the type of employee 1=ASN 2=NON ASN 3=OUTSOURCE. Example: 1
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @response 200 {"code": 200,"message": "success","data": [{"user_id": 1,"name": "administrator","type": "add-bulk-asn","description": "Tambah Massal Data Pegawai ASN","created_at": "2024-07-02 00:00:00"}],"pagination": {"total": 1,"count": 1,"per_page": 5,"current_page": 1,"total_pages": 1,"links": {"first_page": "http://localhost/api/employees/import-histories?page=1","last_page": "http://localhost/api/employees/import-histories?page=1","next_page": null,"prev_page": null}}}
     */
    public function getRiwayatImport(Request $request)
    {
        $messages = [
            'type.required' => 'Type harus diisi.',
            'type.numeric' => 'Type harus berupa angka.',
            'type.in' => 'Type tidak dikenali.',
            'page.numeric' => 'Page harus berupa angka.',
            'page.min' => 'Page minimal harus 1 atau lebih.',
            'limit.numeric' => 'Limit harus berupa angka.',
            'limit.min' => 'Limit minimal harus 1 atau lebih.',
        ];

        $request->validate([
            'type' => 'required|numeric|in:1,2,3',
            'page' => 'nullable|numeric|min:1',
            'limit' => 'nullable|numeric|min:1',
        ], $messages);

        if ($request->type == 1) {
            $type = "add-bulk-asn";
        } else if ($request->type == 2) {
            $type = "add-bulk-non-asn";
        } else if ($request->type == 3) {
            $type = "add-bulk-outsource";
        }

        $riwayat = DB::table('activity_log as a')
            ->select('a.user_id', 'u.name', 'a.type', 'a.description', 'a.created_at')
            ->join('users as u', 'u.id', '=', 'a.user_id')
            ->where('a.type', $type)
            ->orderBy('a.created_at', 'desc');

        if (is_null($request->limit)) {
            $riwayat = $riwayat->get();
            $message = (count($riwayat) < 1) ? 'Mohon maaf, data tidak ditemukan.' : 'success';

            return $this->response(200, $message, $riwayat);
        } else {
            $riwayat = $riwayat->paginate($request->limit);
            $message = ($riwayat->isEmpty()) ? 'Mohon maaf, data tidak ditemukan.' : 'success';

            return $this->paginateResponse(200, $message, $riwayat);
        }
    }
}
