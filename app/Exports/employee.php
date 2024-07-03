<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class employee implements FromView, WithDrawings, WithEvents
{
    private array $toggleField, $userIds;

    public function __construct(array $toggleField, array $userIds)
    {
        $this->toggleField = $toggleField;
        $this->userIds = $userIds;
    }

    public function columnFormats(): array
    {
        return [
            'F' => '#0',
        ];
    }

    public function  registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $alphabet       = $event->sheet->getHighestDataColumn();
                $totalRow       = $event->sheet->getHighestDataRow();
                $cellRange      = 'A3:'.$alphabet.$totalRow;
                $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
                $event->getDelegate()->getRowDimension('1')->setRowHeight('20');
                $event->sheet->styleCells(
                    'A2:'.$alphabet.'2',
                    [
                        //Set border Style
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                            ],

                        ],

                        //Set font style
                        'font' => [
                            'name'      =>  'Inter',
                            'color' => ['rgb' => 'ffffff'],
                        ],

                        //Set background style
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'argb' => '394346',
                            ]
                        ],

                    ]
                );
                $lastColumn = $event->sheet->getHighestColumn();
                $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($lastColumn);

                // Iterate through columns from L to the last column
                for ($col = 12; $col <= $lastColumnIndex; $col++) {
                    // Get the cell coordinate
                    $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '2';

                    // Check if there's data in the cell
                    if (!empty($event->sheet->getCell($cellCoordinate)->getValue())) {
                        // Apply the same style as in row 2, columns A to K
                        $event->sheet->duplicateStyle(
                            $event->sheet->getStyle('A2:B2'),
                            $cellCoordinate
                        );
                    }
                }
                $desiredWidth = 30; // Example width, adjust as needed
                for ($col = 2; $col <= $lastColumnIndex; $col++) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                    $event->sheet->getColumnDimension($columnLetter)->setWidth($desiredWidth);

                    // Apply text alignment to top center
                    $event->sheet->getStyle($columnLetter . '2:' . $columnLetter . $totalRow)
                        ->getAlignment()->setVertical(Alignment::HORIZONTAL_CENTER)
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },

        ];
    }

    public function drawings()
    {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('img/setneg-logo.png'));
        $drawing->setWidthAndHeight( 200, 25);
        $drawing->setCoordinates('A1');
        return $drawing;
    }


    public function view(): View
    {
        $userId = collect($this->userIds);
        $userIdsChunk = $userId->chunk(100);
        $results = collect();
        $usersData = array();
        foreach ($userIdsChunk as $userIds) {
            $users = DB::table('users');
            if (isset($this->toggleField['isName'])) {
                $users->addSelect('users.name');
            }
            if (isset($this->toggleField['isPosition'])){
                $users->leftJoin('positions', 'users.position_id', '=', 'positions.id' );
                $users->addSelect('positions.name as position_name');
            }
            if (isset($this->toggleField['isPositionDescription'])){
                $users->addSelect('users.description as position_description');
            }
            if (isset($this->toggleField['isEchelons'])){
                $users->leftJoin('echelons', 'users.echelon_id', '=', 'echelons.id');
                $users->addSelect('echelons.name as echelons_name');
            }
            if (isset($this->toggleField['isGrade'])){
                $users->leftJoin('grades as g', 'users.grade_id', '=', 'g.id');
                $users->addSelect('g.name as grade_name');
            }
            if (isset($this->toggleField['isNip'])){
                $users->addSelect('users.employee_id_card_number', 'users.employee_registration_number');
            }
            if (isset($this->toggleField['isBirthPlaceDate'])){
                $users->addSelect('users.place_of_birth', 'users.date_of_birth');
            }
            if (isset($this->toggleField['isAge'])){
                $users->addSelect(DB::raw("TIMESTAMPDIFF(YEAR, users.date_of_birth, CURDATE()) AS age"));
            }
            if (isset($this->toggleField['isWorkUnit'])){
                $users->addSelect('users.employment_type_id  as work_unit');
            }
            if (isset($this->toggleField['isEmployeeStatus'])){
                $users->addSelect('users.employment_status');
            }
            if (isset($this->toggleField['isReligion'])){
                $users->addSelect('users.religion');
            }
            if (isset($this->toggleField['isGender'])){
                $users->addSelect('users.gender');
            }
            if (isset($this->toggleField['isMaritalStatus'])){
                $users->addSelect('users.marital_status');
            }
            if (isset($this->toggleField['isAgency'])){
                $users->leftJoin('institutions as i', 'users.institution_id', '=', 'i.id');
                $users->addSelect('i.name as institution_name');
            }
//        if (isset($this->toggleField['isOrganization'])) {
//            $users->leftJoin('groups as o', 'users.organization_id', '=', 'o.id');
//            $users->addSelect('o.name as organization_name');
//        }
            if (isset($this->toggleField['isNoWorker'])){
                $users->addSelect('users.employee_registration_number');
            }
            if(isset($this->toggleField['isWorkDuration'])){
                $users->addSelect('users.position_effective_date');
            }
            if (isset($this->toggleField['isGradeDuration'])){
                $users->addSelect(['users.grade_effective_date']);
            }
            if (isset($this->toggleField['isNPWP'])){
                $users->addSelect('users.id_tax');
            }
            if (isset($this->toggleField['isCurrentAddress'])){
                $users->addSelect('users.current_address');
            }
            if (isset($this->toggleField['isComplex'])){
                $users->leftJoin('residences as r', 'users.residence_id', '=', 'r.id');
                $users->addSelect('r.name as residence_name');
            }
            if (isset($this->toggleField['isHomeNumber'])){
                $users->addSelect('users.home_phone_number');
            }
            if (isset($this->toggleField['isPhoneNumber'])){
                $users->addSelect('users.mobile_phone');
            }
            if (isset($this->toggleField['isOfficeAddress'])){
                $users->addSelect('users.office_address');
            }
            if (isset($this->toggleField['isOfficeNumber'])){
                $users->addSelect('users.office_phone_number');
            }
            if (isset($this->toggleField['isEmail'])){
                $users->addSelect('users.email');
            }
            if (isset($this->toggleField['isPositionHistory'])){
                $gradeHistorySubquery = DB::table('grade_history_users as ghu');
                $gradeHistorySubquery->join('grades', 'grades.id', '=', 'ghu.grade_id');
                $gradeHistorySubquery->select('ghu.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>', grades.name, grades.code,
            ' (', ghu.decree_date, ',', ghu.decree_number, ')', '</li>') SEPARATOR ' ') as grade_history"));
                $gradeHistorySubquery->whereIn('ghu.user_id', $userIds);
                $gradeHistorySubquery->groupBy('ghu.user_id');
                $users->leftJoinSub($gradeHistorySubquery, 'grade_history', function ($join) {
                    $join->on('users.id', '=', 'grade_history.user_id');
                });
                $users->addSelect('grade_history.grade_history');
            }
            if (isset($this->toggleField['isGradeHistory'])){
                $positionHistorySubquery = DB::table('position_history_users as phu');
                $positionHistorySubquery->select('phu.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>', phu.position, '
            (', phu.decree_date, ',', phu.decree_number, ')' , '</li>') SEPARATOR ' ') as position_history"));
                $positionHistorySubquery->whereIn('phu.user_id', $userIds);
                $positionHistorySubquery->groupBy('phu.user_id');
                $users->leftJoinSub($positionHistorySubquery, 'position_history', function ($join) {
                    $join->on('users.id', '=', 'position_history.user_id');
                });
                $users->addSelect('position_history.position_history');
            }
            if (isset($this->toggleField['isTrainingStructural'])){
                $trainingStructuralSubquery = DB::table('training_histories as t');
                $trainingStructuralSubquery->join('training_history_users as ut', 't.id', '=', 'ut.training_history_id');
                $trainingStructuralSubquery->select('ut.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>', t.name, '
            (Periode: ', t.period_month, ' ', t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ',
            Organizer: ', t.organizer ,'</li>') SEPARATOR ' ') as structural_training_history"));
                $trainingStructuralSubquery->whereIn('ut.user_id', $userIds);
                $trainingStructuralSubquery->where('t.type', 1);
                $trainingStructuralSubquery->groupBy('ut.user_id');

                $users->leftJoinSub($trainingStructuralSubquery, 'structural_training_history', function ($join) {
                    $join->on('users.id', '=', 'structural_training_history.user_id');
                });

                $users->addSelect('structural_training_history.structural_training_history');
            }

            if (isset($this->toggleField['isTrainingFunctional'])){
                $trainingFunctionalSubquery = DB::table('training_histories as t');
                $trainingFunctionalSubquery->join('training_history_users as ut', 't.id', '=', 'ut.training_history_id');
                $trainingFunctionalSubquery->select('ut.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>', t.name, '
            (Periode: ', t.period_month, ' ', t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ',
            Organizer: ', t.organizer ,'</li>') SEPARATOR ' ' ) as functional_training_history "));
                $trainingFunctionalSubquery->whereIn('ut.user_id', $userIds);
                $trainingFunctionalSubquery->where('t.type', 2);
                $trainingFunctionalSubquery->groupBy('ut.user_id');

                $users->leftJoinSub($trainingFunctionalSubquery, 'functional_training_history', function ($join) {
                    $join->on('users.id', '=', 'functional_training_history.user_id');
                });

                $users->addSelect('functional_training_history.functional_training_history');
            }

            if (isset($this->toggleField['isTrainingTechnique'])){
                $trainingTechnicSubquery = DB::table('training_histories as t');
                $trainingTechnicSubquery->join('training_history_users as ut', 't.id', '=', 'ut.training_history_id');
                $trainingTechnicSubquery->select('ut.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>', t.name, '
            (Periode: ', t.period_month, ' ', t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ',
            Organizer: ', t.organizer, '</li>') SEPARATOR ' ') as technique_training_history"));
                $trainingTechnicSubquery->whereIn('ut.user_id', $userIds);
                $trainingTechnicSubquery->where('t.type', 3);
                $trainingTechnicSubquery->groupBy('ut.user_id');

                $users->leftJoinSub($trainingTechnicSubquery, 'technique_training_history', function ($join) {
                    $join->on('users.id', '=', 'technique_training_history.user_id');
                });

                $users->addSelect('technique_training_history.technique_training_history');
            }
            if (isset($this->toggleField['isRecognition'])){
                $recognitionSubquery = DB::table('recognition_histories as r');
                $recognitionSubquery->join('recognition_history_users as ur', 'r.id', '=', 'ur.recognition_history_id');
                $recognitionSubquery->join('recognitions', 'r.recognition_id', '=', 'recognitions.id');
                $recognitionSubquery->select('ur.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>',recognitions.name, '
            (Periode: ', r.period_month, ' ', r.period_year, ', Tanggal Terima: ', r.date_of_receipt, ') Decree: ',
            r.decree_number, ', Institusi: ', r.awarding_institution,'</li>') SEPARATOR ' ') as recognition_history"));
                $recognitionSubquery->whereIn('ur.user_id', $userIds);
                $recognitionSubquery->groupBy('ur.user_id');

                $users->leftJoinSub($recognitionSubquery, 'recognition_history', function ($join) {
                    $join->on('users.id', '=', 'recognition_history.user_id');
                });

                $users->addSelect('recognition_history.recognition_history');
            }
            if (isset($this->toggleField['isSKP'])){
                $skpSubquery = DB::table('target_histories as t');
                $skpSubquery->join('target_history_users as ut', 't.id', '=', 'ut.target_history_id');
                $skpSubquery->select('ut.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>',t.name, ' (Tanggal: ', t.period_month, ' ',
                t.period_year, ', Periode Penilaian: ', t.appraisal_period, ') Penilaian Perilaku : ',
                 CASE ut.work_behavior_rating
                        WHEN 1 THEN 'Diatas Ekspektasi'
                        WHEN 2 THEN 'Sesuai Ekspektasi'
                        WHEN 3 THEN 'Dibawah Ekspektasi'
                 END
                , ', Penilaian Predikat Performa : ',
                CASE ut.employee_performance_predicate
                        WHEN 1 THEN 'Sangat Baik'
                        WHEN 2 THEN 'Baik'
                        WHEN 3 THEN 'Butuh Perbaikan'
                        WHEN 4 THEN 'Kurang'
                        WHEN 5 THEN 'Sangat Kurang'
                 END
                 ,', Penilaian Performa Organisasi : ',
                 CASE ut.employee_performance_predicate
                        WHEN 1 THEN 'Sangat Baik'
                        WHEN 2 THEN 'Baik'
                        WHEN 3 THEN 'Cukup'
                 END, '</li>') SEPARATOR ' ') as skp_history"));
                $skpSubquery->whereIn('ut.user_id', $userIds);
                $skpSubquery->groupBy('ut.user_id');

                $users->leftJoinSub($skpSubquery, 'skp_history', function ($join) {
                    $join->on('users.id', '=', 'skp_history.user_id');
                });

                $users->addSelect('skp_history.skp_history');
            }
            if (isset($this->toggleField['isEducationHistory'])){
                $educationSubquery = DB::table('user_educations as ut');
                $educationSubquery->select('ut.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li> Nama Sekolah : ',ut.name, '
            (Fakultas: ', ut.faculty, ' Jurusan: ', ut.major, ', Tahun Lulus: ', ut.year_of_graduation, ') Level: ',
                 CASE ut.level
                        WHEN 1 THEN 'SD/Sederajat'
                        WHEN 2 THEN 'SLTP/Sederajat'
                        WHEN 3 THEN 'SLTA/Sederajat'
                        WHEN 4 THEN 'Diploma I/II'
                        WHEN 5 THEN 'Akademik/D3/S.Muda'
                        WHEN 6 THEN 'Diploma IV/Strata I'
                        WHEN 7 THEN 'Strata II'
                        WHEN 8 THEN 'Strata III'
                 END
                , ', Status: ',
                CASE ut.status
                        WHEN 1 THEN 'Lulus'
                        WHEN 2 THEN 'DO'
                        WHEN 3 THEN 'Aktif'
                        WHEN 4 THEN 'Non-Aktif'
                        WHEN 5 THEN 'Mengundurkan diri'
                 END
                 ,', Description: ', ut.description , '</li>') SEPARATOR ' ') as education_history"));
                $educationSubquery->whereIn('ut.user_id', $userIds);
                $educationSubquery->groupBy('ut.user_id');

                $users->leftJoinSub($educationSubquery, 'education_history', function ($join) {
                    $join->on('users.id', '=', 'education_history.user_id');
                });

                $users->addSelect('education_history.education_history');
            }
            if (isset($this->toggleField['isDisciplinary'])){
                $disciplinarySubquery = DB::table('disciplinary_history_users as dhu')
                    ->join('disciplinary_histories as dh', 'dhu.disciplinary_history_id', '=', 'dh.id')
                    ->join('disciplinaries as d', 'dhu.disciplinary_id', '=', 'd.id')
                    ->select('dhu.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li> Golongan: ', dhu.grade, ' Posisi: ', dhu.position, ' (Periode: ', dh.period_month, ' ', dh.period_year, ', Tanggal Awal: ', dhu.start_date, ' Tanggal Akhir: ', dhu.end_date, ') Decree: ', dhu.decree_number, ', Kantor Otorisasi: ', dhu.authorizing_officer, ' Petugas: ', dhu.name_of_authorizing_officer, '</li>') SEPARATOR ' ') as disciplinary_history"))
                    ->whereIn('dhu.user_id', $userIds)
                    ->groupBy('dhu.user_id');

                $users->leftJoinSub($disciplinarySubquery, 'disciplinary_history', function ($join) {
                    $join->on('users.id', '=', 'disciplinary_history.user_id');
                });

                $users->addSelect('disciplinary_history.disciplinary_history');
            }
            if (isset($this->toggleField['isFamilyHistory'])){
                $familyHistory = DB::table('user_families as uf');
                $familyHistory->select('uf.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li> Nama : ',uf.name, '
            Nomor KTP: ', uf.id_number, ' Nomor KK: ', uf.card_number, ', Tempat Tanggal Lahir: ', uf.place_of_birth, ', ', uf.date_of_birth ,' Agama: ',
            CASE uf.religion
                WHEN 1 THEN 'Islam'
                WHEN 2 THEN 'Kristen'
                WHEN 3 THEN 'Katolik'
                WHEN 4 THEN 'Hindu'
                WHEN 5 THEN 'Buddha'
                WHEN 6 THEN 'Konghucu'
            END
            , ', Jenis Kelamin: ',
            CASE uf.gender
                WHEN 1 THEN 'Pria'
                WHEN 2 THEN 'Wanita'
            END
            ,', Nama Ayah: ', uf.name_of_father , ' Nama Ibu:', uf.name_of_mother,
            ' Relasi Keluarga : ',
            CASE uf.relationship_status
                WHEN 1 THEN 'Kepala Keluarga'
                WHEN 2 THEN 'Suami'
                WHEN 3 THEN 'Istri'
                WHEN 4 THEN 'Anak'
                WHEN 5 THEN 'Menantu'
                WHEN 6 THEN 'Cucu'
                WHEN 7 THEN 'Orang Tua'
                WHEN 8 THEN 'Mertua'
                WHEN 9 THEN 'Famili Lainnya'
                WHEN 10 THEN 'Pembantu'
                WHEN 11 THEN 'Lainnya'
            END
            ,' Edukasi: ',
            CASE uf.education
                WHEN 1 THEN 'Kepala Keluarga'
                WHEN 2 THEN 'Suami'
                WHEN 3 THEN 'Istri'
                WHEN 4 THEN 'Anak'
                WHEN 5 THEN 'Menantu'
                WHEN 6 THEN 'Cucu'
                WHEN 7 THEN 'Orang Tua'
                WHEN 8 THEN 'Mertua'
                WHEN 9 THEN 'Famili Lainnya'
                WHEN 10 THEN 'Pembantu'
                WHEN 11 THEN 'Lainnya'
            END
            ,' Okupasi: ', uf.occupation,' Status Perkawinan',
            CASE uf.marital_status
                WHEN 1 THEN 'Belum Menikah'
                WHEN 2 THEN 'Menikah'
                WHEN 3 THEN 'Cerai Hidup'
                WHEN 4 THEN 'Cerai Mati'
            END
            ,' Nomor Handphone', uf.mobile_phone,'</li>') SEPARATOR ' ') as family_history"));
                $familyHistory->whereIn('uf.user_id', $userIds);
                $familyHistory->groupBy('uf.user_id');

                $users->leftJoinSub($familyHistory, 'family_history', function ($join) {
                    $join->on('users.id', '=', 'family_history.user_id');
                });

                $users->addSelect('family_history.family_history');
            }
            if (isset($this->toggleField['isLeave'])){
                $leaveSubquery = DB::table('user_leaves as ul');
                $leaveSubquery->join('users', 'users.id', '=', 'ul.user_id');
                $leaveSubquery->join('grades', 'grades.id', '=', 'users.grade_id');
                $leaveSubquery->join('positions', 'positions.id', '=', 'users.position_id');
                $leaveSubquery->select('ul.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li> Golongan : ',grades.name, '
            Jabatan: ', positions.name, ' Tanggal Mulai: ', ul.start_date, ', Tanggal Selesai: ', ul.end_date, ' Alasan: ',
            CASE ul.type
                WHEN 1 THEN 'Cuti diluar Tanggungan Negara'
                WHEN 2 THEN 'Cuti Sakit'
                WHEN 3 THEN 'Cuti Besar'
                WHEN 4 THEN 'Cuti Bersalin'
                WHEN 5 THEN 'Cuti Belajar Luar Negeri'
                WHEN 6 THEN 'Cuti Tahunan Luar Negeri'
            END
             , ', Tujuan: ', ul.description,', Nomor: ', ul.number , '</li>') SEPARATOR ' ') as leave_history"));
                $leaveSubquery->whereIn('ul.user_id', $userIds);
                $leaveSubquery->groupBy('ul.user_id');

                $users->leftJoinSub($leaveSubquery, 'leave_history', function ($join) {
                    $join->on('users.id', '=', 'leave_history.user_id');
                });

                $users->addSelect('leave_history.leave_history');
            }
            if (isset($this->toggleField['isAssessment'])){
                $assessmentSubquery = DB::table('user_assessments as ua');
                $assessmentSubquery->select('ua.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Tanggal Assessment : ', ua.event_date, '
             Point: ', ua.point, ' Organizer : ', ua.organizer,'</li>') SEPARATOR ' ') as assessment_history"));
                $assessmentSubquery->whereIn('ua.user_id', $userIds);
                $assessmentSubquery->groupBy('ua.user_id');

                $users->leftJoinSub($assessmentSubquery, 'assessment_history', function ($join) {
                    $join->on('users.id', '=', 'assessment_history.user_id');
                });

                $users->addSelect('assessment_history.assessment_history');
            }
            if (isset($this->toggleField['isCompetency'])){
                $assessmentSubquery = DB::table('user_competencies as ua');
                $assessmentSubquery->select('ua.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Tanggal Assessment : ', ua.event_date, '
             Point: ', ua.point, ' Organizer : ', ua.organizer,'</li>') SEPARATOR ' ') as competency_history"));
                $assessmentSubquery->whereIn('ua.user_id', $userIds);
                $assessmentSubquery->groupBy('ua.user_id');

                $users->leftJoinSub($assessmentSubquery, 'competency_history', function ($join) {
                    $join->on('users.id', '=', 'competency_history.user_id');
                });

                $users->addSelect('competency_history.competency_history');
            }
            if (isset($this->toggleField['isTalentPool'])){
                $assessmentSubquery = DB::table('user_talents as ua');
                $assessmentSubquery->select('ua.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Tanggal Assessment : ', ua.event_date, '
             Point: ', ua.point, ' Organizer : ', ua.organizer,'</li>') SEPARATOR ' ') as talent_pool_history"));
                $assessmentSubquery->whereIn('ua.user_id', $userIds);
                $assessmentSubquery->groupBy('ua.user_id');

                $users->leftJoinSub($assessmentSubquery, 'talent_pool_history', function ($join) {
                    $join->on('users.id', '=', 'talent_pool_history.user_id');
                });

                $users->addSelect('talent_pool_history.talent_pool_history');
            }
            if (isset($this->toggleField['isNotes'])){
                $assessmentSubquery = DB::table('user_notes as un');
                $assessmentSubquery->join('users', 'un.giver_id', '=', 'users.id');
                $assessmentSubquery->select('un.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Catatan : ', un.description, '
             Pemberi catatan: ', users.name, ' Tanggal : ', un.created_at,'</li>') SEPARATOR ' ') as notes"));
                $assessmentSubquery->whereIn('un.user_id', $userIds);
                $assessmentSubquery->groupBy('un.user_id');

                $users->leftJoinSub($assessmentSubquery, 'notes', function ($join) {
                    $join->on('users.id', '=', 'notes.user_id');
                });

                $users->addSelect('notes.notes');
            }
            if (isset($this->toggleField['isEmployeeType'])){
                $employmeeType = DB::table('employment_types as et');
                $employmeeType->join('users', 'et.id', '=', 'users.employment_type_id');
                $employmeeType->select('users.id as user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>',et.name,'</li>') SEPARATOR '') as employee_type"));
                $employmeeType->whereIn('users.id', $userIds);
                $employmeeType->groupBy('users.id');

                $users->leftJoinSub($employmeeType, 'employee_type', function ($join) {
                    $join->on('users.id', '=', 'employee_type.user_id');
                });

                $users->addSelect('employee_type.employee_type');
            }
            if (isset($this->toggleField['isEchelonDate'])){
                $users->addSelect('users.echelon_effective_date');
            }
            if (isset($this->toggleField['isGradeDate'])){
                $users->addSelect('users.grade_effective_date');
            }
            if (isset($this->toggleField['isKarisu'])){
                $users->addSelect('users.karisu_number');
            }
            if (isset($this->toggleField['isNoFamily'])){
                $users->addSelect('users.family_registration_number');
            }
            if (isset($this->toggleField['isNIK'])){
                $users->addSelect('users.id_number');
            }
            if (isset($this->toggleField['isStartDate'])){
                $users->addSelect('users.pns_effective_date');
            }
            if (isset($this->toggleField['isDateCPNS'])){
                $users->addSelect('users.cpns_effective_date');
            }
            if (isset($this->toggleField['isEndDate'])){
                $users->addSelect('users.retirement_effective_date');
            }
            if (isset($this->toggleField['isDatePosition'])){
                $users->addSelect('users.position_effective_date');
            }
            if (isset($this->toggleField['isOutsourcingType'])){
                $outsourcingSubquery = DB::table('employment_types as et');
                $outsourcingSubquery->join('users', 'et.id', '=', 'users.employment_type_id');
                $outsourcingSubquery->select('users.id as user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>',et.name,'</li>') SEPARATOR '') as outsource_type"));
                $outsourcingSubquery->where('et.type', 3);
                $outsourcingSubquery->whereIn('users.id', $userIds);
                $outsourcingSubquery->groupBy('users.id');

                $users->leftJoinSub($outsourcingSubquery, 'outsource_type', function ($join) {
                    $join->on('users.id', '=', 'outsource_type.user_id');
                });

                $users->addSelect('outsource_type.outsource_type');
            }
            if (isset($this->toggleField['isAssistanceType'])){
                $assistanceSubquery = DB::table('employment_types as et');
                $assistanceSubquery->join('users', 'et.id', '=', 'users.employment_type_id');
                $assistanceSubquery->select('users.id as user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>',et.name,'</li>') SEPARATOR '') as assistance_type"));
                $assistanceSubquery->where('et.type', 3);
                $assistanceSubquery->whereIn('users.id', $userIds);
                $assistanceSubquery->groupBy('users.id');

                $users->leftJoinSub($assistanceSubquery, 'assistance_type', function ($join) {
                    $join->on('users.id', '=', 'assistance_type.user_id');
                });

                $users->addSelect('assistance_type.assistance_type');
            }
            if (isset($this->toggleField['isOfficeEmail'])){
                $users->addSelect('users.office_email');
            }
            if (isset($this->toggleField['isEmergencyContact'])){
                $users->addSelect('users.emergency_contact');
            }
            $users->whereIn('users.id', $userIds);
            $users->groupBy('users.id');
            $users = $users->get();
            $chunkResults = $users->map(function($item) {
                return (array) $item;
            })->toArray();
            $usersData = $results->concat($chunkResults);
        }
        return view('exports.employee', [
            'userData' => $usersData,
            'toggleField' => $this->toggleField,
        ]);
    }
}
