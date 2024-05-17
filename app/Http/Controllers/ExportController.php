<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Export Data
 */
class ExportController extends Controller
{

    protected $request;
    protected $posted;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Export Recapitulation Data
     *
     * Export recapitulation data to .CSV, .XLSX and .PDF.
     * @group Export Data
     * Below are the endpoints for export recapitulation, list of employees and detail of employees to .CSV, .XLSX and .PDF.
     * @authenticated
     */
    public function recapitulations()
    {
        $tmp = sys_get_temp_dir();

        $pdf = Pdf::loadview('exports/recap-employee', [
            'date' => Carbon::now()
                ->timezone('Asia/Jakarta')
                ->locale('id')
                ->isoFormat('D MMMM Y'),
            'data' => [
                [
                    'title' => 'Pejabat Pimpinan',
                    'body' => 'Total : 52',
                    'type' => 1,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Pelaksana',
                    'body' => 'Total : 96',
                    'type' => 1,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Fungsional Keahlian',
                    'body' => 'Total : 14',
                    'type' => 1,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Fungsional Keterampilan',
                    'body' => 'Total : 22',
                    'type' => 1,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Kemensetneg Yang Diperbantukan di Setwapres',
                    'body' => 'Total : 66',
                    'type' => 1,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Aparatur Sipil Negara (ASN) Aktif + Perbantuan TNI/POLRI Pelaksana',
                    'body' => 'Total : 99',
                    'type' => 3,
                ],
                [
                    'title' => 'Lorem Ipsum',
                    'body' => '3',
                    'type' => 1,
                ],
                [
                    'title' => 'Lorem Ipsum',
                    'body' => '2',
                    'type' => 1,
                ],
                [
                    'title' => 'Lorem Ipsum',
                    'body' => '1',
                    'type' => 1,
                ],
                [
                    'title' => 'Aparatur Sipil Negara (ASN) Non Aktif',
                    'body' => 'Total : 6',
                    'type' => 3,
                ],
                [
                    'title' => 'Lorem Ipsum',
                    'body' => 'Total : 88',
                    'type' => 1,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum',
                    'body' => 'Total : 77',
                    'type' => 1,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Non Aparatur Sipil Negara (Non ASN) + Tim',
                    'body' => 'Total : 4',
                    'type' => 3,
                ],
                [
                    'title' => 'Lorem Ipsum',
                    'body' => 'Total : 88',
                    'type' => 1,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum',
                    'body' => 'Total : 77',
                    'type' => 1,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Lorem Ipsum Dolor Sit Amet',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Tenaga Outsourcing dan Non Outsourcing',
                    'body' => 'Total : 193',
                    'type' => 3,
                ],
            ],
        ]);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_paper("A4", "portrait");
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('fontDir', $tmp);
        $pdf->set_option('fontCache', $tmp);
        $pdf->set_option('tempDir', $tmp);
        return $pdf->download('recap-employee.pdf');
    }

    /**
     * Export List of Employees
     *
     * Export list of employees data to .CSV, .XLSX and .PDF.
     * @group Export Data
     * @authenticated
     */
    public function employees()
    {

    }

    /**
     * Export Detail Employee
     *
     * Export detail employee data to .CSV, .XLSX and .PDF.
     * @group Export Data
     * @authenticated
     */
    public function detailEmployee()
    {
        $tmp = sys_get_temp_dir();
        $user = DB::table('users as u');
        $user->where('id', $this->request->id);
        $user->select('*');
        $user = $user->first();

        // Institution
        $userInstitution = DB::table('institutions as i');
        $userInstitution->join('users', 'users.institution_id', '=', 'i.id');
        $userInstitution->select('i.name');
        $userInstitution = $userInstitution->first();

        //Organization

        // Education
        $userEducation = DB::table('user_educations as ue');
        $userEducation->join('users', 'users.id', '=', 'ue.user_id');
        $userEducation->where('ue.user_id', $user->id);
        $userEducation->select('ue.level', 'ue.name as school_name', 'ue.faculty', 'ue.major', 'ue.status as education_status', 'ue.year_of_graduation', 'ue.description as education_description');
        $userEducation = $userEducation->get();
        $userCollegeData = array();
        foreach ($userEducation as $education) {
            $userCollegeData[] = [
                'grade' => $education->level,
                'school_name' => $education->school_name,
                'faculty' => $education->faculty,
                'major' => $education->major,
                'status' => $education->education_status,
                'year_graduate' => $education->year_of_graduation,
                'desc' => $education->education_description,
            ];
        }

        //Recognition
        $userRecognition = DB::table('user_recognitions as ur');
        $userRecognition->join('recognitions as r', 'r.id', '=', 'ur.recognition_id');
        $userRecognition->join('users', 'users.id', '=', 'ur.user_id');
        $userRecognition->where('ur.user_id', $user->id);
        $userRecognition->select('r.name as recognition_name', 'r.description as recognition_description', 'r.type_of_decree as recognition_type',
            'r.decree_date', 'r.decree_number', 'r.decree_year', 'r.awarding_institution', 'r.date_of_receipt');
        $userRecognition = $userRecognition->get();
        $userRecognitionData = array();
        foreach ($userRecognition as $recognition) {
            $userRecognitionData[] = [
                'decree_name' => $recognition->recognition_name,
                'desc' => $recognition->recognition_description,
                'decree' => $recognition->recognition_type,
                'decree_date' => $recognition->decree_date,
                'decree_number' => $recognition->decree_number,
                'decree_year' => $recognition->decree_year,
                'awarding_institution' => $recognition->awarding_institution,
                'receipt_date' => $recognition->date_of_receipt,
            ];
        }

        //Leaves
        $userLeave = DB::table('user_leaves as ul');
        $userLeave->join('grades as g', 'g.id', '=', 'ul.grade_id');
        $userLeave->join('users as u', 'u.id', '=', 'ul.user_id');
        $userLeave->where('ul.user_id', $user->id);
        $userLeave->select('g.name', 'ul.start_date', 'ul.end_date', 'ul.reason', 'ul.number', 'ul.purpose', 'ul.leave_letter');
        $userLeave = $userLeave->get();
        $userLeaveData = array();
        foreach ($userLeave as $leave) {
            $userLeaveData[] = [
                'grade' => $leave->name,
                'start_date' => $leave->start_date,
                'end_date' => $leave->end_date,
                'reason' => $leave->reason,
                'number' => $leave->number,
                'purpose' => $leave->purpose,
                'letter' => $leave->leave_letter,
            ];
        }

        //Target
        $userTarget = DB::table('user_targets as ut');
        $userTarget->join('targets as t', 't.id', '=', 'ut.target_id');
        $userTarget->join('users', 'users.id', '=', 'ut.user_id');
        $userTarget->where('ut.user_id', $user->id);
        $userTarget->select('t.appraisal_period as period', 't.year as target_year', 'ut.work_behavior_rating', 'ut.employee_performance_predicate', 'ut.organizational_performance_achievement');
        $userTarget = $userTarget->get();
        $userTargetData = array();
        foreach ($userTarget as $target) {
            $userTargetData[] = [
                'period' => $target->period,
                'target_year' => $target->target_year,
                'work_behavior_rating' => $target->work_behavior_rating,
                'employee_performance_predicate' => $target->employee_performance_predicate,
                'organizational_performance_achievement' => $target->organizational_performance_achievement,
            ];
        }

        //Performance
        $userPerformance = DB::table('user_performances as up');
        $userPerformance->join('performances as p', 'p.id', '=', 'up.performance_id');
        $userPerformance->join('users as u', 'u.id', '=', 'up.user_id');
        $userPerformance->where('up.user_id', $user->id);
        $userPerformance->select('p.description', 'p.performance_period', 'up.work_performance_score');
        $userPerformance = $userPerformance->get();
        $userPerformanceData = array();
        foreach ($userPerformance as $performance) {
            $userPerformanceData[] = [
                'period' => $performance->performance_period,
                'score' => $performance->work_performance_score,
                'description' => $performance->description,
            ];
        }

        //Discipline
        $userPunishment = DB::table('user_disciplinaries as ud');
        $userPunishment->join('disciplinaries as d', 'd.id', '=', 'ud.disciplinary_id');
        $userPunishment->join('disciplinary_types as dt', 'dt.id', '=', 'ud.disciplinary_type_id');
        $userPunishment->join('users', 'users.id', '=', 'ud.user_id');
        $userPunishment->where('ud.user_id', $user->id);
        $userPunishment->select('ud.grade', 'ud.position', 'ud.decree_number', 'ud.date_of_decree', 'ud.start_date',
            'ud.end_date', 'ud.description', 'ud.authorizing_officer', 'ud.name_of_authorizing_officer', 'dt.description as severity',
            'dt.name', 'dt.performance_allowance_duration');
        $userPunishment = $userPunishment->get();
        $userPunishmentData = array();
        foreach ($userPunishment as $punishment){
            $userPunishmentData[] = [
                'grade' => $punishment->grade,
                'position' => $punishment->position,
                'decree_number' => $punishment->decree_number,
                'date_of_decree' => $punishment->date_of_decree,
                'start_date' => $punishment->start_date,
                'end_date' => $punishment->end_date,
                'description' => $punishment->description,
                'authorizing_officer' => $punishment->authorizing_officer,
                'name_of_authorizing_officer' => $punishment->name_of_authorizing_officer,
                'severity' => $punishment->severity,
                'name' => $punishment->name,
                'performance_allowance_duration' => $punishment->performance_allowance_duration,
            ];
        }

        //Family
        $userFamily = DB::table('user_families as uf');
        $userFamily->join('users', 'users.id', '=', 'uf.user_id');
        $userFamily->where('uf.user_id', $user->id);
        $userFamily->select('uf.*');
        $userFamily = $userFamily->get();
        $userFamilyData = array();
        foreach ($userFamily as $family){
            $userFamilyData[] = [
                'card_number' => $family->card_number,
                'name' => $family->name,
                'id_number' => $family->id_number,
                'gender' => $family->gender,
                'religion' => $family->religion,
                'place_of_birth' => $family->place_of_birth,
                'date_of_birth' => $family->date_of_birth,
                'name_of_father' => $family->name_of_father,
                'name_of_mother' => $family->name_of_mother,
                'relationship_status' => $family->relationship_status,
                'education' => $family->education,
                'occupation' => $family->occupation,
                'occupation_description' => $family->occupation_description,
                'marital_status' => $family->marital_status,
                'mobile_phone' => $family->mobile_phone,
                'order' => $family->order,
            ];
        }

        //Notes
        $userNote = DB::table('user_notes as un');
        $userNote->join('users', 'users.id', '=', 'un.user_id');
        $userNote->join('users as giver', 'giver.id', '=', 'un.giver_id');
        $userNote->where('un.user_id', $user->id);
        $userNote->select('un.description', 'un.created_at', 'un.giver_id');
        $userNote = $userNote->get();
        $userNoteData = array();
        foreach ($userNote as $note){
            $noteGiver = DB::table('user_notes as un');
            $noteGiver->join('users', 'users.id', '=', 'un.giver_id');
            $noteGiver->where('un.giver_id', $note->giver_id);
            $noteGiver->select('users.username');
            $noteGiver = $noteGiver->first();
            $userNoteData[] = [
                'description' => $note->description,
                'created_at' => $note->created_at,
                'giver' => $noteGiver->username,
            ];
        }

        //Training
        $userTraining = DB::table('user_trainings as ut');
        $userTraining->join('trainings as t', 't.id', '=', 'ut.training_id');
        $userTraining->join('users', 'users.id', '=', 'ut.user_id');
        $userTraining->where('ut.user_id', $user->id);
        $userTraining->select('t.organizer', 't.type', 't.reference_number', 't.start_date', 't.link', 't.name', 't.level', 't.duration');
        $userTraining = $userTraining->get();
        $trainingFunctional = array();
        $trainingStructural = array();
        $trainingTechnique = array();
        foreach ($userTraining as $training) {
            switch ($training->type) {
                case '1':
                    $trainingStructural[] = [
                        'name' => $training->name,
                        'certificate' => $training->reference_number,
                        'level' => $training->level,
                        'start_date' => $training->start_date,
                        'duration' => $training->duration,
                        'organizer' => $training->organizer,
                        'link' => $training->link,
                    ];
                    break;
                case '2':
                    $trainingFunctional[] = [
                        'name' => $training->name,
                        'certificate' => $training->reference_number,
                        'level' => $training->level,
                        'start_date' => $training->start_date,
                        'duration' => $training->duration,
                        'organizer' => $training->organizer,
                        'link' => $training->link,
                    ];
                    break;
                case '3':
                    $trainingTechnique[] = [
                        'name' => $training->name,
                        'certificate' => $training->reference_number,
                        'start_date' => $training->start_date,
                        'duration' => $training->duration,
                        'link' => $training->link,
                    ];
                    break;
            }
        }
        $religion = match ($user->religion) {
            1 => 'Islam',
            2 => 'Kristen',
            3 => 'Katolik',
            4 => 'Hindu',
            5 => 'Budha',
            6 => 'Konghucu',
            default => '-',
        };
        $maritalStatus = match ($user->marital_status) {
            1 => 'Belum Menikah',
            2 => 'Menikah',
            3 => 'Cerai',
            4 => 'Janda',
            5 => 'Duda',
            default => '-',
        };
        $housingComplex = match ($user->inner_housing_complex){
            1 => 'Dalam',
            2 => 'Luar',
            default => '-',
        };
        $pdf = Pdf::loadview('exports/user', [
            'userProfile' => [
                'Tempat, tanggal lahir' => $user->place_of_birth.', '.$user->date_of_birth,
                'Agama' => $religion,
                'Jenis Kelamin' => ($user->gender ? 'Pria' : 'Wanita'),
                'Status Perkawinan' => $maritalStatus,
                'Instansi Induk' => $userInstitution->name,
                'Satuan Organisasi' => 'Lorem ipsum',
                'Unit Kerja' => $user->work_unit_id,
                'No. Karpeg/No. Karis/No. Karsu' => $user->wife_id_card_number. '/'.$user->husband_id_card_number,
                'Masa Kerja Keseluruhan' => 'Lorem ipsum',
                'Masa Kerja Golongan' => 'Lorem ipsum',
                'NPWP' => $user->id_tax,
                'Status Pegawai' => ($user->employment_status ? 'Aktif' : 'Tidak Aktif'),
                'Komplek' => $housingComplex,
                'Nama Komplek' => 'Lorem ipsum',
                'Alamat Tempat Tinggal Saat Ini' => $user->current_address,
                'No. Telepon Rumah' => $user->home_phone_number,
                'No. HP' => $user->mobile_phone,
                'Alamat Kantor' => $user->office_address,
                'No. Telepon Kantor' => $user->office_phone_number,
                'Email' => $user->email,
                'Batas Usia Pensiun' => $user->expire_at,
            ],
            'photoProfile' => $user->photo_profile,
            'userName' => $user->name,
            'userCollege' => $userCollegeData,
            'userGrade' => [0, 0],
            'userGolongan' => [0, 0],
            'userTrainingStructural' => $trainingStructural,
            'userTrainingFunctional' => $trainingFunctional,
            'userTrainingTechnical' => $trainingTechnique,
            'userAward' => $userRecognitionData,
            'userSKP' => $userTargetData,
            'userPerformance' => $userPerformanceData,
            'userPunishment' => $userPunishmentData,
            'userFamily' => $userFamilyData,
            'userPaidLeave' => $userLeaveData,
            'userNotes' => $userNoteData,
        ]);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_paper("A4", "portrait");
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('fontDir', $tmp);
        $pdf->set_option('fontCache', $tmp);
        $pdf->set_option('tempDir', $tmp);
        return $pdf->download('user-pdf.pdf');
    }
    public function rekapitulasi(){
        $tmp = sys_get_temp_dir();
        $pdf = pdf::loadView('exports/rekapitulasi', []);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setPaper("A4", "portrait");
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->set_option('fontDir', $tmp);
        $pdf->set_option('fontCache', $tmp);
        $pdf->set_option('tempDir', $tmp);
        return $pdf->download('rekapitulasi-pdf.pdf');
    }

    public function rekapitulasiNonASN()
    {
        $tmp = sys_get_temp_dir();
        $pdf = pdf::loadView('exports/rekapitulasi-non-asn', []);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setPaper("A4", "portrait");
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->set_option('fontDir', $tmp);
        $pdf->set_option('fontCache', $tmp);
        $pdf->set_option('tempDir', $tmp);
        return $pdf->download('rekapitulasi-non-asn-pdf.pdf');
    }

    public function rekapitulasiASN()
    {
        $tmp = sys_get_temp_dir();
        $pdf = pdf::loadView('exports/rekapitulasi-asn', []);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setPaper("A4", "portrait");
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->set_option('fontDir', $tmp);
        $pdf->set_option('fontCache', $tmp);
        $pdf->set_option('tempDir', $tmp);
        return $pdf->download('rekapitulasi-asn-pdf.pdf');
    }
}


