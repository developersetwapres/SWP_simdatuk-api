<?php

namespace App\Http\Controllers;

use App\Http\Requests\Export\ExportEmployeesRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\employee;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
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
     * @urlParam id Refers to the ID of Employee. Example: 1
     * @group Export Data
     * @authenticated
     */
    public function detailEmployee($employeeId =  null)
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
                'status' => $grade->status
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
        foreach ($userPosition as $position){
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
                'status' => $position->status
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
        $userAssessment =  $userAssessment->get();
        $assessmentResult = array();
        $assessmentCompetency = array();
        $assessmentTalent = array();
        foreach ($userAssessment as $assessment) {
            switch ($assessment->type) {
                case '1':
                    $assessmentResult[] = [
                        'date' => $assessment->assessment_date,
                        'result' => $assessment->point,
                        'organizer' => $assessment->organizer,
                        'document' => $assessment->document
                    ];
                    break;
                case '2':
                    $assessmentCompetency[] = [
                        'date' => $assessment->assessment_date,
                        'result' => $assessment->point,
                        'organizer' => $assessment->organizer,
                        'document' => $assessment->document
                    ];
                    break;
                case '3':
                    $assessmentTalent[] = [
                        'date' => $assessment->assessment_date,
                        'result' => $assessment->point,
                        'organizer' => $assessment->organizer,
                        'document' => $assessment->document
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
            'userEchelons' => ($userEchelons->name ?? ''). ', '. ($userEchelons->date ?? ' '),
            'userCurrentGrade' => ($userCurrentGrade->name ?? ''). '(' .($userCurrentGrade->code ?? '') . '), '. ($userCurrentGrade->date ?? ''),
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
            'userAssessmentTalent' => $assessmentTalent
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
     * @urlParam id Refers to the ID of Employee. Example: 1
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
        public function zipDetailEmployee(ExportEmployeesRequest $request)
    {

        $user = DB::table('users');
        $user->leftJoin('echelons', 'echelons.id', '=', 'users.echelon_id');
        $user->leftJoin('user_educations', 'user_educations.user_id', '=', 'users.id');
        $user->leftJoin('groups', 'groups.id', '=', 'users.organization_id');
        $user->leftJoin('grades', 'grades.id', '=', 'users.grade_id');
        $user->leftJoin('position_history_users', 'position_history_users.user_id', '=', 'users.id');
        if (isset($request->organization)){
            $user->whereIn('users.organization_id', $request->organization);
        }
        if (isset($request->echelons)){
            $user->whereIn('echelons.name', $request->echelons);
        }
        if (isset($request->grades)){
            $user->whereIn('users.grade_id', $request->grades);
        }
        if (isset($request->education)){
            $user->whereIn('user_educations.level', $request->education);
        }
        if (isset($request->position_status)){
            $user->whereIn('position_history_users.position_status', $request->position_status);
            $user->where('position_history_users.status', 1);
        }
        if (isset($request->gender)){
            $user->whereIn('users.gender', $request->gender);
        }
        if (isset($request->marital_status)){
            $user->whereIn('users.marital_status', $request->marital_status);
        }
        if (isset($request->employee_type)){
           $user->whereIn('users.type', $request->employee_type);
        }
        if (isset($request->age_range)){
            $ageRanges = $request->age_range;
            $today = Carbon::today();
            foreach ($ageRanges as $range) {
                list($minAge, $maxAge) = explode('-', $range);

                $minDateOfBirth = $today->copy()->subYears($maxAge)->toDateString();
                $maxDateOfBirth = $today->copy()->subYears($minAge)->endOfDay()->toDateString();

                $user->orWhereBetween('user.date_of_birth', [$minDateOfBirth, $maxDateOfBirth]);
            }
        }
//        if (isset($request->pension_age)){
//            $pensionRanges = $request->pension_age;
//            $today = Carbon::now();
//            foreach ($pensionRanges as $range){
//                list($minAge, $maxAge) = explode('-', $range);
//                $minPensionAge = $today->copy()->subYears($maxAge)->toDateString();
//                $maxPensionAge = $today->copy()->subYears($minAge)->endOfDay()->toDateString();
//
//               $user->orWhereBetween('user.expire_at', [$minPensionAge, $maxPensionAge]);
//            }
//        }
        $userIds = $user->pluck('users.id')->toArray();
        if (! $userIds){
            return $this->response( 400, 'Data pegawai tidak ditemukan');
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
//            return response()->download($zipFileLocation, $zipFileName, $headers)->deleteFileAfterSend(true);
            return response()->json(['message' => 'success'], 200);
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

    public function userExcel(request $request)
    {
        $toggleFieldBio = array();
        if ($request->isName = 1){
            $toggleFieldBio['isName'] = true;
        }
        if ($request->isPosition = 1){
            $toggleFieldBio['isPosition'] = true;
        }
        if ($request->isPositionDescription = 1){
            $toggleFieldBio['isPositionDescription'] = true;
        }
        if ($request->isEchelons = 1){
            $toggleFieldBio['isEchelons'] = true;
        }
        if ($request->isGrade = 1){
            $toggleFieldBio['isGrade'] = true;
        }
        if ($request->isNip = 1){
            $toggleFieldBio['isNip'] = true;
        }
        if ($request->isBirthPlaceDate = 1){
            $toggleFieldBio['isBirthPlaceDate'] = true;
        }
        if ($request->isAge = 1){
            $toggleFieldBio['isAge'] = true;
        }
        if ($request->isReligion = 1){
            $toggleFieldBio['isReligion'] = true;
        }
        if ($request->isGender = 1){
            $toggleFieldBio['isGender'] = true;
        }
        if ($request->isMaritalStatus = 1){
            $toggleFieldBio['isMaritalStatus'] = true;
        }
        if ($request->isAgency = 1){
            $toggleFieldBio['isAgency'] = true;
        }
        if ($request->isOrganization = 1){
            $toggleFieldBio['isOrganization'] = true;
        }
        if ($request->isWorkUnit = 1){
            $toggleFieldBio['isWorkUnit'] = true;
        }
        if ($request->isNoWorker = 1){
            $toggleFieldBio['isNoWorker'] = true;
        }
        if ($request->workDuration = 1){
            $toggleFieldBio['workDuration'] = true;
        }
        if ($request->isGradeDuration = 1){
            $toggleFieldBio['isGradeDuration'] = true;
        }
        if ($request->isNPWP = 1){
            $toggleFieldBio['isNPWP'] = true;
        }
        if ($request->isEmployeeStatus = 1){
            $toggleFieldBio['isEmployeeStatus'] = true;
        }
        if ($request->isCurrentAddress = 1){
            $toggleFieldBio['isCurrentAddress'] = true;
        }
        if ($request->isComplex = 1){
            $toggleFieldBio['isComplex'] = true;
        }
        if ($request->isHomeNumber = 1){
            $toggleFieldBio['isHomeNumber'] = true;
        }
        if ($request->isPhoneNumber = 1){
            $toggleFieldBio['isPhoneNumber'] = true;
        }
        if ($request->isOfficeAddress = 1){
            $toggleFieldBio['isOfficeAddress'] = true;
        }
        if ($request->isOfficeNumber = 1){
            $toggleFieldBio['isOfficeNumber'] = true;
        }
        if ($request->isEmail = 1){
            $toggleFieldBio['isEmail'] = true;
        }
        if ($request->isPensionCap = 1){
            $toggleFieldBio['isPensionCap'] = true;
        }
        if ($request->isPositionHistory = 1){
            $toggleFieldBio['isPositionHistory'] = true;
        }
        return Excel::download(new employee(10,  $toggleFieldBio), 'testing.xlsx');
    }
}
