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

        //Notes
        $userNote = DB::table('user_notes as un');
        $userNote->join('users', 'users.id', '=', 'un.user_id');
        $userNote->join('users as giver', 'giver.id', '=', 'un.giver_id');
        $userNote->where('un.user_id', $user->id);
        $userNote->select('un.description', 'un.created_at', 'giver.username');
        $userNote = $userNote->get();
        $userNoteData = array();
        foreach ($userNote as $note) {
            $userNoteData[] = [
                'description' => $note->description,
                'created_at' => $note->created_at,
                'giver' => $note->username,
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
        $pdf = Pdf::loadview('exports/user', [
            'userProfile' => [
                'Tempat, tanggal lahir' => $user->place_of_birth . ', ' . $user->date_of_birth,
                'Agama' => $user->religion,
                'Jenis Kelamin' => ($user->gender ? 'Pria' : 'Wanita'),
                'Status Perkawinan' => $user->marital_status,
                'Instansi Induk' => $userInstitution->name,
                'Satuan Organisasi' => 'Lorem ipsum',
                'Unit Kerja' => 'Lorem ipsum',
                'No. Karpeg/No. Karis/No. Karsu' => 'Lorem ipsum',
                'Masa Kerja Keseluruhan' => 'Lorem ipsum',
                'Masa Kerja Golongan' => 'Lorem ipsum',
                'NPWP' => $user->id_tax,
                'Status Pegawai' => ($user->employment_status ? 'Aktik' : 'Tidak Aktif'),
                'Komplek' => 'Lorem ipsum',
                'Nama Komplek' => 'Lorem ipsum',
                'Alamat Tempat Tinggal Saat Ini' => $user->current_address,
                'No. Telepon Rumah' => $user->home_phone_number,
                'No. HP' => $user->mobile_phone,
                'Alamat Kantor' => $user->office_address,
                'No. Telepon Kantor' => $user->office_phone_number,
                'Email' => $user->email,
                'Batas Usia Pensiun' => $user->expire_at,
            ],
            'userCollege' => $userCollegeData,
            'userGrade' => [0, 0],
            'userGolongan' => [0, 0],
            'userTrainingStructural' => $trainingStructural,
            'userTrainingFunctional' => $trainingFunctional,
            'userTrainingTechnical' => $trainingTechnique,
            'userAward' => $userRecognitionData,
            'userSKP' => $userTargetData,
            'userPerformance' => $userPerformanceData,
            'userPunishment' => [0, 0],
            'userFamily' => [0, 0, 0],
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
}
