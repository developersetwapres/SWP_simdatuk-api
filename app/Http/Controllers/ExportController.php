<?php

namespace App\Http\Controllers;

use App\Exports\employee;
use App\Http\Requests\Export\ExportZipEmployeesRequest;
use App\Http\Requests\Export\ExportEmployeesRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

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
     * Export Detail Employee
     *
     * Export detail employee data to .CSV, .XLSX and .PDF.
     * @urlParam id Refers to the ID of Employee. Example: 1
     * @group Export Data
     * @authenticated
     */
    public function detailEmployee($employeeId = null)
    {
        $employeeId = $employeeId ?? $this->request->id;
        if (empty($employeeId)) {
            return response()->json(['error' => 'No employee ID provided'], 400);
        }
        $tmp = sys_get_temp_dir();
        $user = DB::table('users as u');
        $user->where('id', $employeeId);
        $user->select('*');
        $user = $user->first();

        // Institution
        $userInstitution = DB::table('institutions as i');
        $userInstitution->join('users', 'users.institution_id', '=', 'i.id');
        $userInstitution->select('i.name');
        $userInstitution = $userInstitution->first();

        //Organization
        $userEchelons = DB::table('echelons as e');
        $userEchelons->join('users', 'users.echelon_id', '=', 'e.id');
        $userEchelons->select('e.name', 'users.echelon_effective_date');
        $userEchelons = $userEchelons->first();

        //CurrentGrade
        $userCurrentGrade = DB::table('grades as g');
        $userCurrentGrade->join('users', 'users.grade_id', '=', 'g.id');
        $userCurrentGrade->select('g.name', 'g.code', 'users.grade_effective_date as date');
        $userCurrentGrade = $userCurrentGrade->first();

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
        $userLeave->join('grades as g', 'g.id', '=', 'ul.grade');
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
        $userPerformance->select('up.description', 'p.performance_period', 'up.work_performance_score');
        $userPerformance = $userPerformance->get();
        $userPerformanceData = array();
        foreach ($userPerformance as $performance) {
            $userPerformanceData[] = [
                'period' => $performance->performance_period,
                'score' => $performance->work_performance_score,
                'description' => $performance->description,
            ];
        }

        //Grade
        $userGrade = DB::table('grade_history_users as ug');
        $userGrade->join('grades as g', 'g.id', '=', 'ug.grade_id');
        $userGrade->join('users', 'users.id', '=', 'ug.user_id');
        $userGrade->join('decrees', 'decrees.id', '=', 'ug.type_of_decree');
        $userGrade->select('g.name', 'g.code', 'ug.effective_date', 'ug.decree_name', 'ug.decree_document', 'decrees.name as type_of_decree',
            'ug.decree_number', 'ug.decree_date', 'ug.description', 'ug.status');
        $userGrade->where('ug.user_id', $user->id);
        $userGrade = $userGrade->get();
        $userGradeData = array();
        foreach ($userGrade as $grade) {
            $userGradeData[] = [
                'name' => $grade->name,
                'code' => $grade->code,
                'effective_date' => $grade->effective_date,
                'decree_name' => $grade->decree_name,
                'decree_document' => $grade->decree_document,
                'type' => $grade->type_of_decree,
                'decree_number' => $grade->decree_number,
                'decree_date' => $grade->decree_date,
                'description' => $grade->description,
                'status' => $grade->status,
            ];
        }

        //Position
        $userPosition = DB::table('position_history_users as up');
        $userPosition->join('users', 'users.id', '=', 'up.user_id');
        $userPosition->join('groups', 'groups.id', '=', 'up.group_id');
        $userPosition->join('decrees', 'decrees.id', '=', 'up.type_of_decree');
        $userPosition->join('decrees as termination', 'termination.id', '=', 'up.type_of_termination_decree');
        $userPosition->join('echelons', 'echelons.id', '=', 'up.echelon');
        $userPosition->select(
            'up.position',
            'groups.name as group_name',
            'up.effective_date',
            'up.decree',
            'up.decree_document',
            'up.decree_date',
            'decrees.name as decree_name',
            'up.decree_number',
            'echelons.name as echelons_name',
            'up.position_status',
            'up.termination_date',
            'up.termination_decree',
            'termination.name as termination_name',
            'up.termination_decree_number',
            'up.termination_decree_date',
            'up.status'
        );
        $userPosition->where('users.id', $user->id);
        $userPosition = $userPosition->get();
        $userPositionData = array();
        foreach ($userPosition as $position) {
            $userPositionData[] = [
                'position' => $position->position,
                'group' => $position->group_name,
                'effective_date' => $position->effective_date,
                'decree' => $position->decree,
                'decree_document' => $position->decree_document,
                'decree_name' => $position->decree_name,
                'decree_number' => $position->decree_number,
                'decree_date' => $position->decree_date,
                'echelons_name' => $position->echelons_name,
                'position_status' => $position->position_status,
                'termination_date' => $position->termination_date,
                'termination_decree' => $position->termination_decree,
                'termination_name' => $position->termination_name,
                'termination_decree_number' => $position->termination_decree_number,
                'termination_decree_date' => $position->termination_decree_date,
                'status' => $position->status,
            ];
        }
        //Discipline
        $userPunishment = DB::table('disciplinary_history_users as ud');
        $userPunishment->join('disciplinary_histories as d', 'd.id', '=', 'ud.disciplinary_history_id');
        $userPunishment->join('disciplinaries as dt', 'dt.id', '=', 'ud.disciplinary_id');
        $userPunishment->join('users', 'users.id', '=', 'ud.user_id');
        $userPunishment->where('ud.user_id', $user->id);
        $userPunishment->select('ud.grade', 'ud.position', 'ud.decree_number', 'ud.date_of_decree', 'ud.start_date',
            'ud.end_date', 'ud.description', 'ud.authorizing_officer', 'ud.name_of_authorizing_officer', 'dt.description as severity',
            'dt.name', 'dt.performance_allowance_duration');
        $userPunishment = $userPunishment->get();
        $userPunishmentData = array();
        foreach ($userPunishment as $punishment) {
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
        foreach ($userFamily as $family) {
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
                'sequence_number' => $family->sequence_number,
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
        foreach ($userNote as $note) {
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

        $userAssessment = DB::table('user_assessments as ua');
        $userAssessment->join('users', 'users.id', '=', 'ua.user_id');
        $userAssessment->select('ua.assessment_date', 'ua.point', 'ua.organizer', 'ua.assessment_document', 'ua.type');
        $userAssessment = $userAssessment->get();
        $assessmentResult = array();
        $assessmentCompetency = array();
        $assessmentTalent = array();
        foreach ($userAssessment as $assessment) {
            switch ($assessment->type) {
                case '1':
                    $assessmentResult[] = [
                        'assessment_date' => $assessment->assessment_date,
                        'point' => $assessment->point,
                        'organizer' => $assessment->organizer,
                        'document' => $assessment->document,
                    ];
                    break;
                case '2':
                    $assessmentCompetency[] = [
                        'assessment_date' => $assessment->assessment_date,
                        'point' => $assessment->point,
                        'organizer' => $assessment->organizer,
                        'document' => $assessment->document,
                    ];
                    break;
                case '3':
                    $assessmentTalent[] = [
                        'assessment_date' => $assessment->assessment_date,
                        'point' => $assessment->point,
                        'organizer' => $assessment->organizer,
                        'document' => $assessment->document,
                    ];
                    break;
            }
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
        $housingComplex = match ($user->residence_id) {
            1 => 'Dalam',
            2 => 'Luar',
            default => '-',
        };
        $pdf = Pdf::loadview('exports/user', [
            'userProfile' => [
                'Tempat, tanggal lahir' => $user->place_of_birth . ', ' . $user->date_of_birth,
                'Agama' => $religion,
                'Jenis Kelamin' => ($user->gender ? 'Pria' : 'Wanita'),
                'Status Perkawinan' => $maritalStatus,
                'Instansi Induk' => ($userInstitution->name ?? ''),
                'Satuan Organisasi' => 'Lorem ipsum',
                'Unit Kerja' => $user->work_unit_id,
                'No. Karpeg/No. Karis/No. Karsu' => $user->wife_id_card_number . '/' . $user->husband_id_card_number,
                'Masa Kerja Keseluruhan' => 'Lorem ipsum',
                'Masa Kerja Golongan' => 'Lorem',
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
            'userNIP' => $user->employee_id_number,
            'userName' => $user->name,
            'userEchelons' => ($userEchelons->name ?? '') . ', ' . ($userEchelons->date ?? ' '),
            'userCurrentGrade' => ($userCurrentGrade->name ?? '') . '(' . ($userCurrentGrade->code ?? '') . '), ' . ($userCurrentGrade->date ?? ''),
            'userCollege' => $userCollegeData,
            'userPosition' => $userPositionData,
            'userGrade' => $userGradeData,
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
            'userAssessment' => $assessmentResult,
            'userAssessmentCompetency' => $assessmentCompetency,
            'userAssessmentTalent' => $assessmentTalent,
        ]);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_paper("A4", "portrait");
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('fontDir', $tmp);
        $pdf->set_option('fontCache', $tmp);
        $pdf->set_option('tempDir', $tmp);
        return $pdf->download('user-pdf.pdf');
    }
    /**
     * Export Detail Employee
     *
     * Export detail of multiple employees data to .PDF inside a zip file.
     * @group Export Data
     * @bodyParam organization int[] list of organization's ids. Example [1,2]
     * @bodyParam employee_type int[] list of employee's type. Example [1,2]
     * @bodyParam echelons int[] list of echelons' id. Example [1,2]
     * @bodyParam grades int[] list of employee's grade. Example [1,2]
     * @bodyParam position_status int[] list of employee's position status. Example [1,2]
     * @bodyParam education int[] list of employee's education level. Example [1, 6]
     * @bodyParam gender int[] list of employee's gender.
     * @bodyParam marital_status int[] list of employee's marital status. Example [1,4]
     * @bodyParam age_range string[] list of employee's age range. Example ["30-40", "40-50"]
     * @authenticated
     */
    public function zipDetailEmployee(ExportZipEmployeesRequest $request)
    {

        $user = DB::table('users');
        $user->leftJoin('echelons', 'echelons.id', '=', 'users.echelon_id');
        $user->leftJoin('user_educations', 'user_educations.user_id', '=', 'users.id');
        $user->leftJoin('groups', 'groups.id', '=', 'users.organization_id');
        $user->leftJoin('grades', 'grades.id', '=', 'users.grade_id');
        $user->leftJoin('position_history_users', 'position_history_users.user_id', '=', 'users.id');

        if (isset($request->organization)) {
            $user->whereIn('users.organization_id', $request->organization);
        }
        if (isset($request->age_range)) {
            $ageRanges = $request->input('age_range', []);
            $now = Carbon::now();

            $user->where(function ($query) use ($ageRanges, $now, &$dateRanges) {
                foreach ($ageRanges as $range) {
                    [$minAge, $maxAge] = explode('-', $range);

                    // Calculate date range for the current age range
                    $maxDate = $now->copy()->subYears($minAge)->toDateString();
                    $minDate = $now->copy()->subYears($maxAge + 1)->addDay()->toDateString();

                    // Store the date range for debugging
                    $dateRanges[] = ['minAge' => $minAge, 'maxAge' => $maxAge, 'minDate' => $minDate, 'maxDate' => $maxDate];

                    // Add orWhereBetween clause within the nested query
                    $query->orWhereBetween('users.date_of_birth', [$minDate, $maxDate]);
                }
            });
        }
        if (isset($request->echelons)) {
            $user->whereIn('echelons.name', $request->echelons);
        }
        if (isset($request->grades)) {
            $user->whereIn('users.grade_id', $request->grades);
        }
        if (isset($request->education)) {
            $user->whereIn('user_educations.level', $request->education);
        }
        if (isset($request->position_status)) {
            $user->whereIn('position_history_users.position_status', $request->position_status);
        }
        if (isset($request->gender)) {
            $user->whereIn('users.gender', $request->gender);
        }
        if (isset($request->marital_status)) {
            $user->whereIn('users.marital_status', $request->marital_status);
        }
        if (isset($request->employee_type)) {
            $user->whereIn('users.type', $request->employee_type);
        }

        $userIds = $user->pluck('users.id')->toArray();
        if (!$userIds) {
            return $this->response(400, 'Data pegawai tidak ditemukan');
        }
        $zip = new \Madnest\Madzipper\Madzipper;
        $zipFileName = "Employee-" . Carbon::now()->format('Y-m-d_H-i-s') . ".zip";
        $zipFileLocation = storage_path('app/public/document/' . $zipFileName);
        $zip->make($zipFileLocation);
        $directory = 'app/public/document';
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }
        set_time_limit(0);
        $pdfFiles = [];
        foreach ($userIds as $employeeId) {
            $pdfContent = $this->detailEmployee($employeeId);
            $pdfFileName = 'employee_' . $employeeId . '.pdf';
            $pdfFilePath = 'public/document/' . $pdfFileName;
            Storage::put($pdfFilePath, $pdfContent);
            $pdfFiles[] = $pdfFilePath;
            $zip->add(storage_path('app/' . $pdfFilePath));
        }
        $zip->close();
        foreach ($pdfFiles as $pdfFile) {
            Storage::delete($pdfFile);
        }

        if (file_exists($zipFileLocation)) {
            $headers = [
                'Content-Type: application/zip',
                'Content-Disposition: attachment; filename="' . $zipFileName . '"',
                'Content-Length: ' . filesize($zipFileLocation),
            ];
            return response()->download($zipFileLocation, $zipFileName, $headers)->deleteFileAfterSend(true);
        } else {
            return response()->json(['error' => 'Zip file not found'], 404);
        }
    }
    public function rekapitulasi()
    {
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
    /**
     * Export List of Employees
     *
     * Export list of employees data to .CSV, .XLSX
     * @group Export Data
     * @bodyParam extension string Indicates exported file extension. Example xlsx
     * @bodyParam organization int[] list of organization's ids. Example [1,2]
     * @bodyParam employee_type int[] list of employee's type. Example [1,2]
     * @bodyParam echelons int[] list of echelons' id. Example [1,2]
     * @bodyParam grades int[] list of employee's grade. Example [1,2]
     * @bodyParam position_status int[] list of employee's position status. Example [1,2]
     * @bodyParam education int[] list of employee's education level. Example [1, 6]
     * @bodyParam gender int[] list of employee's gender. Example [1,0]
     * @bodyParam marital_status int[] list of employee's marital status. Example [1,4]
     * @bodyParam age_range string[] list of employee's age range. Example ["30-40", "40-50"]
     * @bodyParam isName int Indicates whether the name field is included in the request. Example 1
     * @bodyParam isPosition int Indicates whether the position field is included in the request. Example 1
     * @bodyParam isPositionDescription int Indicates whether the position description field is included in the request. Example 1
     * @bodyParam isEchelons int Indicates whether the echelons field is included in the request. Example 1
     * @bodyParam isGrade int Indicates whether the grade field is included in the request. Example 1
     * @bodyParam isNip int Indicates whether the NIP (National Identification Number) field is included in the request. Example 1
     * @bodyParam isBirthPlaceDate int Indicates whether the birth place and date field is included in the request. Example 1
     * @bodyParam isAge int Indicates whether the age field is included in the request. Example 1
     * @bodyParam isReligion int Indicates whether the religion field is included in the request. Example 1
     * @bodyParam isGender int Indicates whether the gender field is included in the request. Example 1
     * @bodyParam isMaritalStatus int Indicates whether the marital status field is included in the request. Example 1
     * @bodyParam isAgency int Indicates whether the agency field is included in the request. Example 1
     * @bodyParam isOrganization int Indicates whether the organization field is included in the request. Example 1
     * @bodyParam isWorkUnit int Indicates whether the work unit field is included in the request. Example 1
     * @bodyParam isNoWorker int Indicates whether the worker number field is included in the request. Example 1
     * @bodyParam workDuration int Indicates the duration of work. Example 1
     * @bodyParam isGradeDuration int Indicates whether the grade duration field is included in the request. Example 1
     * @bodyParam isNPWP int Indicates whether the NPWP (Tax Identification Number) field is included in the request. Example 1
     * @bodyParam isEmployeeStatus int Indicates whether the employee status field is included in the request. Example 1
     * @bodyParam isCurrentAddress int Indicates whether the current address field is included in the request. Example 1
     * @bodyParam isComplex int Indicates whether the complex field is included in the request. Example 1
     * @bodyParam isHomeNumber int Indicates whether the home number field is included in the request. Example 1
     * @bodyParam isPhoneNumber int Indicates whether the phone number field is included in the request. Example 1
     * @bodyParam isOfficeAddress int Indicates whether the office address field is included in the request. Example 1
     * @bodyParam isOfficeNumber int Indicates whether the office number field is included in the request. Example 1
     * @bodyParam isEmail int Indicates whether the email field is included in the request. Example 1
     * @bodyParam isPensionCap int Indicates whether the pension cap field is included in the request. Example 1
     * @bodyParam isPositionHistory int Indicates whether the position history field is included in the request. Example 1
     * @bodyParam isGradeHistory int Indicates whether the grade history field is included in the request. Example 1
     * @bodyParam isTrainingStructural int Indicates whether the structural training field is included in the request. Example 1
     * @bodyParam isTrainingFunctional int Indicates whether the functional training field is included in the request. Example 1
     * @bodyParam isTrainingTechnique int Indicates whether the technique training field is included in the request. Example 1
     * @bodyParam isSKP int Indicates whether the SKP (Employee Performance Target) field is included in the request. Example 1
     * @bodyParam isRecognition int Indicates whether the recognition field is included in the request. Example 1
     * @bodyParam isNotes int Indicates whether the notes field is included in the request. Example 1
     * @bodyParam isEducationHistory int Indicates whether the education history field is included in the request. Example 1
     * @bodyParam isDisciplinary int Indicates whether the disciplinary field is included in the request. Example 1
     * @bodyParam isFamilyHistory int Indicates whether the family history field is included in the request. Example 1
     * @bodyParam isLeave int Indicates whether the leave field is included in the request. Example 1
     * @bodyParam isAssessment int Indicates whether the assessment field is included in the request. Example 1
     * @bodyParam isCompetency int Indicates whether the competency field is included in the request. Example 1
     * @bodyParam isTalentPool int Indicates whether the talent pool field is included in the request. Example 1
     * @authenticated
     */
    public function employees(ExportEmployeesRequest $request)
    {
        // filter user to get ids
        $users = DB::table('users')
            ->leftJoin('echelons', 'users.echelon_id', '=', 'echelons.id')
            ->leftJoin('user_educations', 'users.id', '=', 'user_educations.user_id')
            ->leftJoin('position_history_users', 'users.id', '=', 'position_history_users.user_id')
            ->select('users.id');
        if (isset($request->organization)){
            $users->whereIn('users.organization_id', $request->organization);
        }
        if (isset($request->age_range)) {
            $ageRanges = $request->input('age_range', []);
            $now = Carbon::now();

            $users->where(function ($query) use ($ageRanges, $now, &$dateRanges) {
                foreach ($ageRanges as $range) {
                    [$minAge, $maxAge] = explode('-', $range);

                    $maxDate = $now->copy()->subYears($minAge)->toDateString();
                    $minDate = $now->copy()->subYears($maxAge + 1)->addDay()->toDateString();

                    // Add orWhereBetween clause within the nested query
                    $query->orWhereBetween('users.date_of_birth', [$minDate, $maxDate]);
                }
            });
        }
        if (isset($request->echelons)) {
            $users->whereIn('echelons.name', $request->echelons);
        }
        if (isset($request->grades)) {
            $users->whereIn('users.grade_id', $request->grades);
        }
        if (isset($request->education)) {
            $users->whereIn('user_educations.level', $request->education);
        }
        if (isset($request->position_status)) {
            $users->whereIn('position_history_users.position_status', $request->position_status);
        }
        if (isset($request->gender)) {
            $users->whereIn('users.gender', $request->gender);
        }
        if (isset($request->marital_status)) {
            $users->whereIn('users.marital_status', $request->marital_status);
        }
        if (isset($request->employee_type)) {
            $users->whereIn('users.type', $request->employee_type);
        }
        $userIds = $users->pluck('users.id')->toArray();
        if (!$userIds) {
            return $this->response(400, 'Data pegawai tidak ditemukan');
        }

        // toggle field
        $toggleFieldBio = array();
        $toggleFieldBio['isName'] = $request->isName == 1;
        $toggleFieldBio['isPosition'] = $request->isPosition == 1;
        $toggleFieldBio['isPositionDescription'] = $request->isPositionDescription == 1;
        $toggleFieldBio['isEchelons'] = $request->isEchelons == 1;
        $toggleFieldBio['isGrade'] = $request->isGrade == 1;
        $toggleFieldBio['isNip'] = $request->isNip == 1;
        $toggleFieldBio['isBirthPlaceDate'] = $request->isBirthPlaceDate == 1;
        $toggleFieldBio['isAge'] = $request->isAge == 1;
        $toggleFieldBio['isReligion'] = $request->isReligion == 1;
        $toggleFieldBio['isGender'] = $request->isGender == 1;
        $toggleFieldBio['isMaritalStatus'] = $request->isMaritalStatus == 1;
        $toggleFieldBio['isAgency'] = $request->isAgency == 1;
        $toggleFieldBio['isOrganization'] = $request->isOrganization == 1;
        $toggleFieldBio['isWorkUnit'] = $request->isWorkUnit == 1;
        $toggleFieldBio['isNoWorker'] = $request->isNoWorker == 1;
        $toggleFieldBio['workDuration'] = $request->workDuration == 1;
        $toggleFieldBio['isGradeDuration'] = $request->isGradeDuration == 1;
        $toggleFieldBio['isNPWP'] = $request->isNPWP == 1;
        $toggleFieldBio['isEmployeeStatus'] = $request->isEmployeeStatus == 1;
        $toggleFieldBio['isCurrentAddress'] = $request->isCurrentAddress == 1;
        $toggleFieldBio['isComplex'] = $request->isComplex == 1;
        $toggleFieldBio['isHomeNumber'] = $request->isHomeNumber == 1;
        $toggleFieldBio['isPhoneNumber'] = $request->isPhoneNumber == 1;
        $toggleFieldBio['isOfficeAddress'] = $request->isOfficeAddress == 1;
        $toggleFieldBio['isOfficeNumber'] = $request->isOfficeNumber == 1;
        $toggleFieldBio['isEmail'] = $request->isEmail == 1;
        $toggleFieldBio['isPensionCap'] = $request->isPensionCap == 1;
        $toggleFieldBio['isPositionHistory'] = $request->isPositionHistory == 1;
        $toggleFieldBio['isGradeHistory'] = $request->isGradeHistory == 1;
        $toggleFieldBio['isTrainingStructural'] = $request->isTrainingStructural == 1;
        $toggleFieldBio['isTrainingFunctional'] = $request->isTrainingFunctional == 1;
        $toggleFieldBio['isTrainingTechnique'] = $request->isTrainingTechnique == 1;
        $toggleFieldBio['isSKP'] = $request->isSKP == 1;
        $toggleFieldBio['isRecognition'] = $request->isRecognition == 1;
        $toggleFieldBio['isNotes'] = $request->isNotes == 1;
        $toggleFieldBio['isEducationHistory'] = $request->isEducationHistory == 1;
        $toggleFieldBio['isDisciplinary'] = $request->isDisciplinary == 1;
        $toggleFieldBio['isFamilyHistory'] = $request->isFamilyHistory == 1;
        $toggleFieldBio['isLeave'] = $request->isLeave == 1;
        $toggleFieldBio['isAssessment'] = $request->isAssessment == 1;
        $toggleFieldBio['isCompetency'] = $request->isCompetency == 1;
        $toggleFieldBio['isTalentPool'] = $request->isTalentPool == 1;
        if ($request->extension == "csv"){
            return Excel::download(new employee($toggleFieldBio, $userIds), 'Employees-' . Carbon::now() . '.csv',  \Maatwebsite\Excel\Excel::CSV);
        }else if ($request->extension == "xlsx"){
            return Excel::download(new employee($toggleFieldBio, $userIds), 'Employees-' . Carbon::now() . '.xlsx',  \Maatwebsite\Excel\Excel::XLSX);
        }
    }

    /**
     * Preview Export List of Employees
     *
     * Preview Export list of employees data
     * @group Export Data
     * @bodyParam organization int[] list of organization's ids. Example [1,2]
     * @bodyParam employee_type int[] list of employee's type. Example [1,2]
     * @bodyParam echelons int[] list of echelons' id. Example [1,2]
     * @bodyParam grades int[] list of employee's grade. Example [1,2]
     * @bodyParam position_status int[] list of employee's position status. Example [1,2]
     * @bodyParam education int[] list of employee's education level. Example [1, 6]
     * @bodyParam gender int[] list of employee's gender. Example [1,0]
     * @bodyParam marital_status int[] list of employee's marital status. Example [1,4]
     * @bodyParam age_range string[] list of employee's age range. Example ["30-40", "40-50"]
     * @bodyParam isName int Indicates whether the name field is included in the request. Example 1
     * @bodyParam isPosition int Indicates whether the position field is included in the request. Example 1
     * @bodyParam isPositionDescription int Indicates whether the position description field is included in the request. Example 1
     * @bodyParam isEchelons int Indicates whether the echelons field is included in the request. Example 1
     * @bodyParam isGrade int Indicates whether the grade field is included in the request. Example 1
     * @bodyParam isNip int Indicates whether the NIP (National Identification Number) field is included in the request. Example 1
     * @bodyParam isBirthPlaceDate int Indicates whether the birth place and date field is included in the request. Example 1
     * @bodyParam isAge int Indicates whether the age field is included in the request. Example 1
     * @bodyParam isReligion int Indicates whether the religion field is included in the request. Example 1
     * @bodyParam isGender int Indicates whether the gender field is included in the request. Example 1
     * @bodyParam isMaritalStatus int Indicates whether the marital status field is included in the request. Example 1
     * @bodyParam isAgency int Indicates whether the agency field is included in the request. Example 1
     * @bodyParam isOrganization int Indicates whether the organization field is included in the request. Example 1
     * @bodyParam isWorkUnit int Indicates whether the work unit field is included in the request. Example 1
     * @bodyParam isNoWorker int Indicates whether the worker number field is included in the request. Example 1
     * @bodyParam workDuration int Indicates the duration of work. Example 1
     * @bodyParam isGradeDuration int Indicates whether the grade duration field is included in the request. Example 1
     * @bodyParam isNPWP int Indicates whether the NPWP (Tax Identification Number) field is included in the request. Example 1
     * @bodyParam isEmployeeStatus int Indicates whether the employee status field is included in the request. Example 1
     * @bodyParam isCurrentAddress int Indicates whether the current address field is included in the request. Example 1
     * @bodyParam isComplex int Indicates whether the complex field is included in the request. Example 1
     * @bodyParam isHomeNumber int Indicates whether the home number field is included in the request. Example 1
     * @bodyParam isPhoneNumber int Indicates whether the phone number field is included in the request. Example 1
     * @bodyParam isOfficeAddress int Indicates whether the office address field is included in the request. Example 1
     * @bodyParam isOfficeNumber int Indicates whether the office number field is included in the request. Example 1
     * @bodyParam isEmail int Indicates whether the email field is included in the request. Example 1
     * @bodyParam isPensionCap int Indicates whether the pension cap field is included in the request. Example 1
     * @bodyParam isPositionHistory int Indicates whether the position history field is included in the request. Example 1
     * @bodyParam isGradeHistory int Indicates whether the grade history field is included in the request. Example 1
     * @bodyParam isTrainingStructural int Indicates whether the structural training field is included in the request. Example 1
     * @bodyParam isTrainingFunctional int Indicates whether the functional training field is included in the request. Example 1
     * @bodyParam isTrainingTechnique int Indicates whether the technique training field is included in the request. Example 1
     * @bodyParam isSKP int Indicates whether the SKP (Employee Performance Target) field is included in the request. Example 1
     * @bodyParam isRecognition int Indicates whether the recognition field is included in the request. Example 1
     * @bodyParam isNotes int Indicates whether the notes field is included in the request. Example 1
     * @bodyParam isEducationHistory int Indicates whether the education history field is included in the request. Example 1
     * @bodyParam isDisciplinary int Indicates whether the disciplinary field is included in the request. Example 1
     * @bodyParam isFamilyHistory int Indicates whether the family history field is included in the request. Example 1
     * @bodyParam isLeave int Indicates whether the leave field is included in the request. Example 1
     * @bodyParam isAssessment int Indicates whether the assessment field is included in the request. Example 1
     * @bodyParam isCompetency int Indicates whether the competency field is included in the request. Example 1
     * @bodyParam isTalentPool int Indicates whether the talent pool field is included in the request. Example 1
     * @authenticated
     */
    public function exportExcelsPreview(request $request){
        // filter user to get ids
        $users = DB::table('users')
            ->leftJoin('echelons', 'users.echelon_id', '=', 'echelons.id')
            ->leftJoin('user_educations', 'users.id', '=', 'user_educations.user_id')
            ->leftJoin('position_history_users', 'users.id', '=', 'position_history_users.user_id')
            ->select('users.id');
        if (isset($request->organization)){
            $users->whereIn('users.organization_id', $request->organization);
        }
        if (isset($request->age_range)) {
            $ageRanges = $request->input('age_range', []);
            $now = Carbon::now();

            $users->where(function ($query) use ($ageRanges, $now, &$dateRanges) {
                foreach ($ageRanges as $range) {
                    [$minAge, $maxAge] = explode('-', $range);

                    // Calculate date range for the current age range
                    $maxDate = $now->copy()->subYears($minAge)->toDateString();
                    $minDate = $now->copy()->subYears($maxAge + 1)->addDay()->toDateString();

                    // Store the date range for debugging
                    $dateRanges[] = ['minAge' => $minAge, 'maxAge' => $maxAge, 'minDate' => $minDate, 'maxDate' => $maxDate];

                    // Add orWhereBetween clause within the nested query
                    $query->orWhereBetween('users.date_of_birth', [$minDate, $maxDate]);
                }
            });
        }
        if (isset($request->echelons)) {
            $users->whereIn('echelons.name', $request->echelons);
        }
        if (isset($request->grades)) {
            $users->whereIn('users.grade_id', $request->grades);
        }
        if (isset($request->education)) {
            $users->whereIn('user_educations.level', $request->education);
        }
        if (isset($request->position_status)) {
            $users->whereIn('position_history_users.position_status', $request->position_status);
        }
        if (isset($request->gender)) {
            $users->whereIn('users.gender', $request->gender);
        }
        if (isset($request->marital_status)) {
            $users->whereIn('users.marital_status', $request->marital_status);
        }
        if (isset($request->employee_type)) {
            $users->whereIn('users.type', $request->employee_type);
        }
        $userIds = $users->pluck('users.id')->toArray();
        if (!$userIds) {
            return $this->response(400, 'Data pegawai tidak ditemukan');
        }

        $usersPreview = DB::table('users');
        $toggleFieldBio = array();

        if( $this->request->isName == 1){
            $usersPreview->addSelect('users.name');
        }
        if ($this->request->isPosition == 1){
            $usersPreview->leftJoin('positions', 'users.position_id', '=', 'positions.id' );
            $usersPreview->addSelect('positions.name as position_name');
        }
//        if ($this->toggleField['isPositionDescription']){
//            //
//        }
        if ($this->request->isEchelons){
            $usersPreview->leftJoin('echelons', 'users.echelon_id', '=', 'echelons.id');
            $usersPreview->addSelect('echelons.name as echelons_name');
        }
        if ($this->request->isGrade == 1){
            $usersPreview->leftJoin('grades as g', 'users.grade_id', '=', 'g.id');
            $usersPreview->addSelect('g.name as grade_name');
        }
        if ($this->request->isNip == 1){
            $usersPreview->addSelect(DB::raw("users.employee_id_number"));
        }
        if ($this->request->isBirthPlaceDate == 1){
            $usersPreview->addSelect('users.place_of_birth', 'users.date_of_birth');
        }
        if ($this->request->isAge == 1){
            $usersPreview->addSelect(DB::raw("TIMESTAMPDIFF(YEAR, users.date_of_birth, CURDATE()) AS age"));
        }
        if ($this->request->isWorkUnit == 1){
            $usersPreview->addSelect('users.work_unit_id as work_unit');
        }
        if ($this->request->isEmployeeStatus == 1){
            $usersPreview->addSelect('users.employment_status');
        }
        if ($this->request->isReligion == 1){
            $usersPreview->addSelect('users.religion');
        }
        if ($this->request->isGender == 1){
            $usersPreview->addSelect('users.gender');
        }
        if ($this->request->isMaritalStatus == 1){
            $usersPreview->addSelect('users.marital_status');
        }
        if ($this->request->isAgency == 1){
            $usersPreview->leftJoin('institutions as i', 'users.institution_id', '=', 'i.id');
            $usersPreview->addSelect('i.name as institution_name');
        }
        if ($this->request->isOrganization == 1) {
            $usersPreview->leftJoin('groups as o', 'users.organization_id', '=', 'o.id');
            $usersPreview->addSelect('o.name as organization_name');
        }
        if ($this->request->isNoWorker == 1){
            $usersPreview->addSelect('users.employee_id_number', 'users.employee_registration_number');
        }
        //add full work duration later
        if ($this->request->isGradeDuration == 1){
            $usersPreview->addSelect(['users.grade_effective_date']);
        }
        if ($this->request->isNPWP == 1){
            $usersPreview->addSelect('users.id_tax');
        }
        if ($this->request->isCurrentAddress == 1){
            $usersPreview->addSelect('users.current_address');
        }
        if ($this->request->isComplex == 1){
            $usersPreview->leftJoin('residences as r', 'users.residence_id', '=', 'r.id');
            $usersPreview->addSelect('r.name as residence_name');
        }
        if ($this->request->isHomeNumber == 1){
            $usersPreview->addSelect('users.home_phone_number');
        }
        if ($this->request->isPhoneNumber == 1){
            $usersPreview->addSelect('users.mobile_phone');
        }
        if ($this->request->isOfficeAddress == 1){
            $usersPreview->addSelect('users.office_address');
        }
        if ($this->request->isOfficeNumber == 1){
            $usersPreview->addSelect('users.office_phone_number');
        }
        if ($this->request->isEmail == 1){
            $usersPreview->addSelect('users.email');
        }
        if (isset($this->toggleField['isPositionHistory'])){
            $gradeHistorySubquery = DB::table('grade_history_users as ghu');
            $gradeHistorySubquery->join('grades', 'grades.id', '=', 'ghu.grade_id');
            $gradeHistorySubquery->select('ghu.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>', grades.name, grades.code,
            ' (', ghu.decree_date, ',', ghu.decree_number, ')', '</li>') SEPARATOR ' ') as grade_history"));
            $gradeHistorySubquery->whereIn('ghu.user_id', $this->userIds);
            $gradeHistorySubquery->groupBy('ghu.user_id');
            $usersPreview->leftJoinSub($gradeHistorySubquery, 'grade_history', function ($join) {
                $join->on('users.id', '=', 'grade_history.user_id');
            });
            $usersPreview->addSelect('grade_history.grade_history');
        }
        if (isset($this->toggleField['isGradeHistory'])){
            $positionHistorySubquery = DB::table('position_history_users as phu');
            $positionHistorySubquery->select('phu.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>', phu.position, '
            (', phu.decree_date, ',', phu.decree_number, ')' , '</li>') SEPARATOR ' ') as position_history"));
            $positionHistorySubquery->whereIn('phu.user_id', $this->userIds);
            $positionHistorySubquery->groupBy('phu.user_id');
            $usersPreview->leftJoinSub($positionHistorySubquery, 'position_history', function ($join) {
                $join->on('users.id', '=', 'position_history.user_id');
            });
            $usersPreview->addSelect('position_history.position_history');
        }
        if (isset($this->toggleField['isTrainingStructural'])){
            $trainingStructuralSubquery = DB::table('trainings as t');
            $trainingStructuralSubquery->join('user_trainings as ut', 't.id', '=', 'ut.training_id');
            $trainingStructuralSubquery->select('ut.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>', t.name, '
            (Periode: ', t.period_month, ' ', t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ',
            Organizer: ', t.organizer ,'</li>') SEPARATOR ' ') as structural_training_history"));
            $trainingStructuralSubquery->whereIn('ut.user_id', $this->userIds);
            $trainingStructuralSubquery->where('t.type', 1);
            $trainingStructuralSubquery->groupBy('ut.user_id');

            $usersPreview->leftJoinSub($trainingStructuralSubquery, 'structural_training_history', function ($join) {
                $join->on('users.id', '=', 'structural_training_history.user_id');
            });

            $usersPreview->addSelect('structural_training_history.structural_training_history');
        }

        if (isset($this->toggleField['isTrainingFunctional'])){
            $trainingFunctionalSubquery = DB::table('trainings as t');
            $trainingFunctionalSubquery->join('user_trainings as ut', 't.id', '=', 'ut.training_id');
            $trainingFunctionalSubquery->select('ut.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>', t.name, '
            (Periode: ', t.period_month, ' ', t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ',
            Organizer: ', t.organizer ,'</li>') SEPARATOR ' ' ) as functional_training_history "));
            $trainingFunctionalSubquery->whereIn('ut.user_id', $this->userIds);
            $trainingFunctionalSubquery->where('t.type', 2);
            $trainingFunctionalSubquery->groupBy('ut.user_id');

            $usersPreview->leftJoinSub($trainingFunctionalSubquery, 'functional_training_history', function ($join) {
                $join->on('users.id', '=', 'functional_training_history.user_id');
            });

            $usersPreview->addSelect('functional_training_history.functional_training_history');
        }

        if (isset($this->toggleField['isTrainingTechnique'])){
            $trainingTechnicSubquery = DB::table('trainings as t');
            $trainingTechnicSubquery->join('user_trainings as ut', 't.id', '=', 'ut.training_id');
            $trainingTechnicSubquery->select('ut.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>', t.name, '
            (Periode: ', t.period_month, ' ', t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ',
            Organizer: ', t.organizer, '</li>') SEPARATOR ' ') as technique_training_history"));
            $trainingTechnicSubquery->whereIn('ut.user_id', $this->userIds);
            $trainingTechnicSubquery->where('t.type', 3);
            $trainingTechnicSubquery->groupBy('ut.user_id');

            $usersPreview->leftJoinSub($trainingTechnicSubquery, 'technique_training_history', function ($join) {
                $join->on('users.id', '=', 'technique_training_history.user_id');
            });

            $usersPreview->addSelect('technique_training_history.technique_training_history');
        }
        if ($this->request->isRecognition == 1){
            $recognitionSubquery = DB::table('recognitions as r');
            $recognitionSubquery->join('user_recognitions as ur', 'r.id', '=', 'ur.recognition_id');
            $recognitionSubquery->select('ur.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>',r.name, '
            (Periode: ', r.period_month, ' ', r.period_year, ', Tanggal Terima: ', r.date_of_receipt, ') Decree: ',
            r.decree_number, ', Institusi: ', r.awarding_institution,'</li>') SEPARATOR ' ') as recognition_history"));
            $recognitionSubquery->whereIn('ur.user_id', $this->userIds);
            $recognitionSubquery->groupBy('ur.user_id');

            $usersPreview->leftJoinSub($recognitionSubquery, 'recognition_history', function ($join) {
                $join->on('users.id', '=', 'recognition_history.user_id');
            });

            $usersPreview->addSelect('recognition_history.recognition_history');
        }
        if ($this->request->isSKP == 1){
            $skpSubquery = DB::table('targets as t');
            $skpSubquery->join('user_targets as ut', 't.id', '=', 'ut.target_id');
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
            $skpSubquery->whereIn('ut.user_id', $this->userIds);
            $skpSubquery->groupBy('ut.user_id');

            $usersPreview->leftJoinSub($skpSubquery, 'skp_history', function ($join) {
                $join->on('users.id', '=', 'skp_history.user_id');
            });

            $usersPreview->addSelect('skp_history.skp_history');
        }
        if ($this->request->isEducationHistory == 1){
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
            $educationSubquery->whereIn('ut.user_id', $this->userIds);
            $educationSubquery->groupBy('ut.user_id');

            $usersPreview->leftJoinSub($educationSubquery, 'education_history', function ($join) {
                $join->on('users.id', '=', 'education_history.user_id');
            });

            $usersPreview->addSelect('education_history.education_history');
        }
        if ($this->request->isDisciplinary == 1){
            $disciplinarySubquery = DB::table('disciplinary_history_users as dhu')
                ->join('disciplinary_histories as dh', 'dhu.disciplinary_history_id', '=', 'dh.id')
                ->join('disciplinaries as d', 'dhu.disciplinary_id', '=', 'd.id')
                ->select('dhu.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li> Golongan: ', dhu.grade, ' Posisi: ', dhu.position, ' (Periode: ', dh.period_month, ' ', dh.period_year, ', Tanggal Awal: ', dhu.start_date, ' Tanggal Akhir: ', dhu.end_date, ') Decree: ', dhu.decree_number, ', Kantor Otorisasi: ', dhu.authorizing_officer, ' Petugas: ', dhu.name_of_authorizing_officer, '</li>') SEPARATOR ' ') as disciplinary_history"))
                ->whereIn('dhu.user_id', $this->userIds)
                ->groupBy('dhu.user_id');

            $usersPreview->leftJoinSub($disciplinarySubquery, 'disciplinary_history', function ($join) {
                $join->on('users.id', '=', 'disciplinary_history.user_id');
            });

            $usersPreview->addSelect('disciplinary_history.disciplinary_history');
        }
        if ($this->request->isFamilyHistory == 1){
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
            $familyHistory->whereIn('uf.user_id', $this->userIds);
            $familyHistory->groupBy('uf.user_id');

            $usersPreview->leftJoinSub($familyHistory, 'family_history', function ($join) {
                $join->on('users.id', '=', 'family_history.user_id');
            });

            $usersPreview->addSelect('family_history.family_history');
        }
        if ($this->request->isLeave == 1){
            $leaveSubquery = DB::table('user_leaves as ul');
            $leaveSubquery->select('ul.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li> Golongan : ',ul.grade, '
            Jabatan: ', ul.position, ' Tanggal Mulai: ', ul.start_date, ', Tanggal Selesai: ', ul.end_date, ' Alasan: ',
            ul.reason , ', Tujuan: ', ul.purpose,', Nomor: ', ul.number , '</li>') SEPARATOR ' ') as leave_history"));
            $leaveSubquery->whereIn('ul.user_id', $this->userIds);
            $leaveSubquery->groupBy('ul.user_id');

            $usersPreview->leftJoinSub($leaveSubquery, 'leave_history', function ($join) {
                $join->on('users.id', '=', 'leave_history.user_id');
            });

            $usersPreview->addSelect('leave_history.leave_history');
        }
        if ($this->request->isAssessment == 1){
            $assessmentSubquery = DB::table('user_assessments as ua');
            $assessmentSubquery->select('ua.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Tanggal Assessment : ', ua.assessment_date, '
             Point: ', ua.point, ' Organizer : ', ua.organizer,'</li>') SEPARATOR ' ') as assessment_history"));
            $assessmentSubquery->whereIn('ua.user_id', $this->userIds);
            $assessmentSubquery->where('ua.type', 1);
            $assessmentSubquery->groupBy('ua.user_id');

            $usersPreview->leftJoinSub($assessmentSubquery, 'assessment_history', function ($join) {
                $join->on('users.id', '=', 'assessment_history.user_id');
            });

            $usersPreview->addSelect('assessment_history.assessment_history');
        }
        if ($this->request->isCompetency == 1){
            $assessmentSubquery = DB::table('user_assessments as ua');
            $assessmentSubquery->select('ua.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Tanggal Assessment : ', ua.assessment_date, '
             Point: ', ua.point, ' Organizer : ', ua.organizer,'</li>') SEPARATOR ' ') as competency_history"));
            $assessmentSubquery->whereIn('ua.user_id', $this->userIds);
            $assessmentSubquery->where('ua.type', 2);
            $assessmentSubquery->groupBy('ua.user_id');

            $usersPreview->leftJoinSub($assessmentSubquery, 'competency_history', function ($join) {
                $join->on('users.id', '=', 'competency_history.user_id');
            });

            $usersPreview->addSelect('competency_history.competency_history');
        }
        if ($this->request->isTalentPool == 1){
            $assessmentSubquery = DB::table('user_assessments as ua');
            $assessmentSubquery->select('ua.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Tanggal Assessment : ', ua.assessment_date, '
             Point: ', ua.point, ' Organizer : ', ua.organizer,'</li>') SEPARATOR ' ') as talent_pool_history"));
            $assessmentSubquery->whereIn('ua.user_id', $this->userIds);
            $assessmentSubquery->where('ua.type', 3);
            $assessmentSubquery->groupBy('ua.user_id');

            $usersPreview->leftJoinSub($assessmentSubquery, 'talent_pool_history', function ($join) {
                $join->on('users.id', '=', 'talent_pool_history.user_id');
            });

            $usersPreview->addSelect('talent_pool_history.talent_pool_history');
        }
        if ($this->request->isNotes == 1){
            $assessmentSubquery = DB::table('user_notes as un');
            $assessmentSubquery->join('users', 'un.giver_id', '=', 'users.id');
            $assessmentSubquery->select('un.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Catatan : ', un.description, '
             Pemberi catatan: ', users.name, ' Tanggal : ', un.created_at,'</li>') SEPARATOR ' ') as notes"));
            $assessmentSubquery->whereIn('un.user_id', $this->userIds);
            $assessmentSubquery->groupBy('un.user_id');

            $usersPreview->leftJoinSub($assessmentSubquery, 'notes', function ($join) {
                $join->on('users.id', '=', 'notes.user_id');
            });

            $usersPreview->addSelect('notes.notes');
        }
        $usersPreview->whereIn('users.id', $userIds);
        $usersPreview->groupBy('users.id');
        $usersPreview = $usersPreview->get();
        $usersPreviewData = $usersPreview->map(function($item) {
            return (array) $item;
        })->toArray();
        $toggleFieldBio['isName'] = $request->isName == 1;
        $toggleFieldBio['isPosition'] = $request->isPosition == 1;
        $toggleFieldBio['isPositionDescription'] = $request->isPositionDescription == 1;
        $toggleFieldBio['isEchelons'] = $request->isEchelons == 1;
        $toggleFieldBio['isGrade'] = $request->isGrade == 1;
        $toggleFieldBio['isNip'] = $request->isNip == 1;
        $toggleFieldBio['isBirthPlaceDate'] = $request->isBirthPlaceDate == 1;
        $toggleFieldBio['isAge'] = $request->isAge == 1;
        $toggleFieldBio['isReligion'] = $request->isReligion == 1;
        $toggleFieldBio['isGender'] = $request->isGender == 1;
        $toggleFieldBio['isMaritalStatus'] = $request->isMaritalStatus == 1;
        $toggleFieldBio['isAgency'] = $request->isAgency == 1;
        $toggleFieldBio['isOrganization'] = $request->isOrganization == 1;
        $toggleFieldBio['isWorkUnit'] = $request->isWorkUnit == 1;
        $toggleFieldBio['isNoWorker'] = $request->isNoWorker == 1;
        $toggleFieldBio['workDuration'] = $request->workDuration == 1;
        $toggleFieldBio['isGradeDuration'] = $request->isGradeDuration == 1;
        $toggleFieldBio['isNPWP'] = $request->isNPWP == 1;
        $toggleFieldBio['isEmployeeStatus'] = $request->isEmployeeStatus == 1;
        $toggleFieldBio['isCurrentAddress'] = $request->isCurrentAddress == 1;
        $toggleFieldBio['isComplex'] = $request->isComplex == 1;
        $toggleFieldBio['isHomeNumber'] = $request->isHomeNumber == 1;
        $toggleFieldBio['isPhoneNumber'] = $request->isPhoneNumber == 1;
        $toggleFieldBio['isOfficeAddress'] = $request->isOfficeAddress == 1;
        $toggleFieldBio['isOfficeNumber'] = $request->isOfficeNumber == 1;
        $toggleFieldBio['isEmail'] = $request->isEmail == 1;
        $toggleFieldBio['isPensionCap'] = $request->isPensionCap == 1;
        $toggleFieldBio['isPositionHistory'] = $request->isPositionHistory == 1;
        $toggleFieldBio['isGradeHistory'] = $request->isGradeHistory == 1;
        $toggleFieldBio['isTrainingStructural'] = $request->isTrainingStructural == 1;
        $toggleFieldBio['isTrainingFunctional'] = $request->isTrainingFunctional == 1;
        $toggleFieldBio['isTrainingTechnique'] = $request->isTrainingTechnique == 1;
        $toggleFieldBio['isSKP'] = $request->isSKP == 1;
        $toggleFieldBio['isRecognition'] = $request->isRecognition == 1;
        $toggleFieldBio['isNotes'] = $request->isNotes == 1;
        $toggleFieldBio['isEducationHistory'] = $request->isEducationHistory == 1;
        $toggleFieldBio['isDisciplinary'] = $request->isDisciplinary == 1;
        $toggleFieldBio['isFamilyHistory'] = $request->isFamilyHistory == 1;
        $toggleFieldBio['isLeave'] = $request->isLeave == 1;
        $toggleFieldBio['isAssessment'] = $request->isAssessment == 1;
        $toggleFieldBio['isCompetency'] = $request->isCompetency == 1;
        $toggleFieldBio['isTalentPool'] = $request->isTalentPool == 1;
        return view('exports.preview-employee', [
            'userData' => $usersPreviewData,
            'toggleField' => $toggleFieldBio,
        ]);
    }
}
