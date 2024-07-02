<?php

namespace App\Http\Controllers;

use App\Exports\employee;
use App\Http\Requests\Export\ExportEmployeesRequest;
use App\Http\Requests\Export\ExportZipEmployeesRequest;
use App\Http\Requests\Export\PreviewExportEmployeesRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

/**
 * @group Export
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
     * Export Detail DRH Employee
     *
     * Export detail employee data to .PDF.
     * @urlParam id Refers to the ID of Employee. Example:: 1
     * @group Export
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

        //CurrentPosition
        $userCurrentPosition = DB::table('positions');
        $userCurrentPosition->join('users', 'users.position_id', '=', 'positions.id');
        $userCurrentPosition->select('positions.name');
        $userCurrentPosition = $userCurrentPosition->first();

        // Education
        $userEducation = DB::table('user_educations as ue');
        $userEducation->join('users', 'users.id', '=', 'ue.user_id');
        $userEducation->where('ue.user_id', $user->id);
        $userEducation->select('ue.level', 'ue.name as school_name', 'ue.faculty', 'ue.major', 'ue.status as education_status', 'ue.year_of_graduation', 'ue.description as education_description');
        $userEducation->orderBy('ue.level');
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
        $userRecognition = DB::table('recognition_history_users as ur');
        $userRecognition->join('recognition_histories as r', 'r.id', '=', 'ur.recognition_history_id');
        $userRecognition->join('recognitions', 'r.recognition_id', '=', 'recognitions.id');
        $userRecognition->join('decrees', 'decrees.id', '=', 'r.type_of_decree');
        $userRecognition->join('users', 'users.id', '=', 'ur.user_id');
        $userRecognition->where('ur.user_id', $user->id);
        $userRecognition->select('recognitions.name as recognition_name', 'r.description as recognition_description', 'decrees.name as recognition_type',
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
        $userLeave->join('users as u', 'u.id', '=', 'ul.user_id');
        $userLeave->join('grades as g', 'g.id', '=', 'u.grade_id');
        $userLeave->join('positions as p', 'p.id', '=', 'u.position_id');
        $userLeave->where('ul.user_id', $user->id);
        $userLeave->select('g.name', 'ul.start_date', 'ul.end_date', 'ul.type', 'ul.number', 'ul.description', 'ul.letter', 'p.name as position_name');
        $userLeave = $userLeave->get();
        $userLeaveData = array();
        foreach ($userLeave as $leave) {
            $userLeaveData[] = [
                'grade' => $leave->name,
                'position' => $leave->position_name,
                'start_date' => $leave->start_date,
                'end_date' => $leave->end_date,
                'type' => $leave->type,
                'number' => $leave->number,
                'purpose' => $leave->description,
                'letter' => $leave->letter,
            ];
        }

        //Target
        $userTarget = DB::table('target_history_users as ut');
        $userTarget->join('target_histories as t', 't.id', '=', 'ut.target_history_id');
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

        //Credit Score
        $userCredit = DB::table('user_credits as ucs');
        $userCredit->where('ucs.user_id', $user->id);
        $userCredit->select('ucs.position', 'ucs.period', 'ucs.year', 'ucs.score');
        $userCredit = $userCredit->get();
        $userCreditData = array();
        foreach ($userCredit as $credit) {
            $userCreditData[] = [
                'position' => $credit->position,
                'period' => $credit->period,
                'year' => $credit->year,
                'credit_score' => $credit->score,
            ];
        }

        //Performance
        $userPerformance = DB::table('performance_history_users as up');
        $userPerformance->join('performance_histories as p', 'p.id', '=', 'up.performance_history_id');
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
            $noteGiver->select('users.name');
            $noteGiver = $noteGiver->first();
            $userNoteData[] = [
                'description' => $note->description,
                'created_at' => $note->created_at,
                'giver' => $noteGiver->name,
            ];
        }

        $userAssessment = DB::table('user_assessments as ua');
        $userAssessment->join('users', 'users.id', '=', 'ua.user_id');
        $userAssessment->select('ua.event_date', 'ua.point', 'ua.organizer', 'ua.assessment_document');
        $userAssessment = $userAssessment->get();
        $userAssessmentData = array();
        foreach ($userAssessment as $assessment){
            $userAssessmentData[] = [
                'assessment_date' => $assessment->event_date,
                'point' => $assessment->point,
                'organizer' => $assessment->organizer,
                'document' => $assessment->assessment_document,
            ];
        }

        $userCompetency = DB::table('user_competencies as uc');
        $userCompetency->join('users', 'users.id', '=', 'uc.user_id');
        $userCompetency->select('uc.event_date', 'uc.point', 'uc.organizer', 'uc.competency_document');
        $userCompetency = $userCompetency->get();
        $userCompetencyData = array();
        foreach ($userCompetency as $competency){
            $userCompetencyData[] = [
                'assessment_date' => $competency->event_date,
                'point' => $competency->point,
                'organizer' => $competency->organizer,
                'document' => $competency->competency_document,
            ];
        }
        $userTalent = DB::table('user_talents as ut');
        $userTalent->join('users', 'users.id', '=', 'ut.user_id');
        $userTalent->select('ut.event_date', 'ut.point', 'ut.organizer', 'ut.talent_document');
        $userTalent = $userTalent->get();
        $userTalentData = array();
        foreach ($userTalent as $talent){
            $userTalentData[] = [
                'assessment_date' => $talent->event_date,
                'point' => $talent->point,
                'organizer' => $talent->organizer,
                'document' => $talent->talent_document,
            ];
        }

        //Training
        $userTraining = DB::table('training_history_users as ut');
        $userTraining->join('training_histories as t', 't.id', '=', 'ut.training_history_id');
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
        $employeeType = match ($user->type){
            1 => 'ASN',
            2 => 'NON ASN',
            3 => 'OUTSOURCING',
            default => '-',
        };
        $educationLevel = match ($user->education_level){
            1 => 'SD/Sederajat',
            2 => 'SLTP/Sederajat',
            3 => 'SLTA/Sederajat',
            4 => 'Diploma I/II',
            5 => 'Akademik/D3/S.Muda',
            6 => 'Diploma IV/Strata I',
            7 => 'Strata II',
            8 => 'Strata III',
            default => '-',
        };
        // Housing
        $housingComplex = DB::table('residences');
        $housingComplex->join('users as u', 'u.residence_id', '=', 'residences.id');
        $housingComplex->select('residences.name');
        $housingComplex = $housingComplex->first();
        $complex = 'Luar';
        $complexName = '-';
        if (isset($housingComplex->name) && $housingComplex->name != 'Luar Komplek' ){
            $complex = 'dalam';
            $complexName = $housingComplex->name;
        }

        //Grade Date
        $gradeStartDate = $user->grade_effective_date;
        $gradeDate = new \DateTime($gradeStartDate);
        $currentDate = new \DateTime();
        $gradeDate = $currentDate->diff($gradeDate);


        $pdf = Pdf::loadview('exports/user', [
            'userProfile' => [
                'Tempat, tanggal lahir' => $user->place_of_birth . ', ' . $user->date_of_birth,
                'Agama' => $religion,
                'Jenis Kelamin' => ($user->gender ? 'Pria' : 'Wanita'),
                'Status Perkawinan' => $maritalStatus,
                'Jenis Pegawai' => $employeeType,
                'TMT Menjabat' => ($user->position_effective_date ?? ''),
                'Instansi Induk' => ($userInstitution->name ?? ''),
                'Tingkat' => $educationLevel,
                'Nama Sekolah/Universitas' => $user->education_name,
                'Tahun Lulus' => $user->education_year,
                'No. Karpeg/No. Karis/No. Karsu' => $user->karisu_number,
                'Masa Kerja Keseluruhan' => 'Lorem ipsum',
                'Masa Kerja Golongan' => $gradeDate->y . ' Tahun ' . $gradeDate->m . ' Bulan' . $gradeDate->d . ' Hari',
                'NPWP' => $user->id_tax,
                'Status Pegawai' => ($user->employment_status ? 'Aktif' : 'Tidak Aktif'),
                'Nomor NIK' => $user->id_number,
                'Komplek' => $complex,
                'Nama Komplek' => $complexName,
                'Alamat Tempat Tinggal Saat Ini' => $user->current_address,
                'No. Telepon Rumah' => $user->home_phone_number,
                'No. HP' => $user->mobile_phone,
                'Alamat Kantor' => $user->office_address,
                'No. Telepon Kantor' => $user->office_phone_number,
                'Email' => $user->email,
                'Email Dinas' => $user->office_email,
                'Kontak Darurat' => $user->emergency_contact,
                'Batas Usia Pensiun' => $user->retirement_effective_date,
            ],
            'currentPosition' => ($userCurrentPosition->name ?? '-'),
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
            'userCredit' => $userCreditData,
            'userPerformance' => $userPerformanceData,
            'userPunishment' => $userPunishmentData,
            'userFamily' => $userFamilyData,
            'userLeave' => $userLeaveData,
            'userNotes' => $userNoteData,
            'userAssessment' => $userAssessmentData,
            'userAssessmentCompetency' => $userCompetencyData,
            'userAssessmentTalent' => $userTalentData,
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
     * Export Detail DRH Employee
     *
     * Export detail of multiple employees DRH data to .PDF inside a zip file.
     * @group Export
     * @bodyParam organization int[] list of organization's ids. Example: [1,2]
     * @bodyParam employee_type int[] list of employee's type. Example: [1,2]
     * @bodyParam echelons int[] list of echelons' id. Example: [1,2]
     * @bodyParam grades int[] list of employee's grade. Example: [1,2]
     * @bodyParam job_description int[] list of employee's position status. Example: [1,2]
     * @bodyParam education int[] list of employee's education level. Example: [1, 6]
     * @bodyParam gender int[] list of employee's gender.
     * @bodyParam marital_status int[] list of employee's marital status. Example: [1,4]
     * @bodyParam max_age int maximum age of employees. Example: 50
     * @bodyParam min_age int minimum age of employees. Example: 50
     * @bodyParam deputy int[] list of deputy ids. Example: [1,2]
     * @authenticated
     */
    public function zipDetailEmployee(ExportZipEmployeesRequest $request)
    {

        $user = DB::table('users');
        $user->leftJoin('echelons', 'echelons.id', '=', 'users.echelon_id');
        $user->leftJoin('user_educations', 'user_educations.user_id', '=', 'users.id');
        $user->leftJoin('grades', 'grades.id', '=', 'users.grade_id');
        $user->leftJoin('position_history_users', 'position_history_users.user_id', '=', 'users.id');
        if (isset($request->employee_type)) {
            $user->whereIn('users.type', $request->employee_type);
        }
        if (isset($request->echelons)) {
            $user->whereIn('echelons.name', $request->echelons);
        }
        if (isset($request->grades)) {
            $user->whereIn('users.grade_id', $request->grades);
        }
        if (isset($request->organization)) {
            $user->whereIn('users.organization_id', $request->organization);
        }
        if (isset($request->job_description)) {
            $user->whereIn('users.description', $request->job_description);
        }
        if (isset($request->education)) {
            $user->whereIn('user_educations.level', $request->education);
        }
        if (isset($request->gender)) {
            $user->whereIn('users.gender', $request->gender);
        }
        if (isset($request->min_age)) {
            $minAge = $request->input('min_age');
            $now = Carbon::now();

            $minDate = $now->copy()->subYears($minAge + 1)->addDay()->toDateString();

            $user->where('users.date_of_birth', '<=', $minDate);
        }

        if (isset($request->max_age)) {
            $maxAge = $request->input('max_age');
            $now = Carbon::now();

            $maxDate = $now->copy()->subYears($maxAge)->toDateString();

            $user->where('users.date_of_birth', '>=', $maxDate);
        }
        if (isset($request->marital_status)) {
            $user->whereIn('users.marital_status', $request->marital_status);
        }

        if (isset($request->grade_range)) {
            $gradeRanges = $request->input('grade_range', []);
            $now = Carbon::now();

            $user->where(function ($query) use ($gradeRanges, $now) {
                foreach ($gradeRanges as $range) {
                    [$minYears, $maxYears] = explode('-', $range);

                    $maxDate = $now->copy()->subYears($minYears)->toDateString();
                    $minDate = $now->copy()->subYears($maxYears + 1)->addDay()->toDateString();

                    $query->orWhereBetween('users.grade_effective_date', [$minDate, $maxDate]);
                }
            });
        }
        if (isset($request->total_working_duration)) {
            $gradeRanges = $request->input('total_working_duration', []);
            $now = Carbon::now();

            $user->where(function ($query) use ($gradeRanges, $now) {
                foreach ($gradeRanges as $range) {
                    [$minYears, $maxYears] = explode('-', $range);

                    $maxDate = $now->copy()->subYears($minYears)->toDateString();
                    $minDate = $now->copy()->subYears($maxYears + 1)->addDay()->toDateString();

                    $query->orWhereBetween('users.position_effective_date', [$minDate, $maxDate]);
                }
            });
        }
        if (isset($request->deputy)){
            $parentIds = DB::table('positions')->whereIn('id', $request->deputy)->pluck('parent_id')->toArray();
            $positionIds = array_merge($parentIds, $request->deputy);
            $user->whereIn('users.position_id', $positionIds);
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

    /**
     * Export List of Employees
     *
     * Export list of employees data to .CSV, .XLSX or .PDF
     * @group Export
     * @authenticated
     * @urlParam type string required file type of the document. Example: csv
     * @bodyParam deputy int[] List of employee's deputy. Example: [1,2]
     * @bodyParam employee int[] List of employee's type. Example: [1,2]
     * @bodyParam echelons int[] List of echelons' id. Example: [1,2]
     * @bodyParam grades int[] List of employee's grade. Example: [1,2]
     * @bodyParam position_status int[] List of employee's position status. Example: [1,2]
     * @bodyParam education int[] List of employee's education level. Example: [1, 6]
     * @bodyParam gender int[] List of employee's gender. Example: [1,0]
     * @bodyParam marital_status int[] List of employee's marital status. Example: [1,4]
     * @bodyParam min_age int filter minimum age of employees. Example: 50
     * @bodyParam max_age int filter minimum age of employees. Example: 60
     * @bodyParam grade_range string[] List of employee's grade duration in year. Example: ["30-40", "40-50"]
     * @bodyParam total_working_duration string[] List range of total employee's working duration in year. Example: ["30-40", "40-50"]
     * @bodyParam target_period string Filter which period employees target_histories. Example: Q1
     * @bodyParam target_year int Filter the year of employees target_histories. Example: 2024
     * @bodyParam work_behavior_rating int Filter work behavior rating of employees target. Example: 1 for 'Diatas Ekspektasi'
     * @bodyParam employee_performance_predicate int Filter performance predicate for employees target. Example: 1 for 'Sangat Baik'
     * @bodyParam organizational_performance_achievement int Filter organizational performance achievement. Example: 3 for 'Cukup'
     * @bodyParam credit_period int Filter period for employee last credit score. Example: 1
     * @bodyParam credit_year int Filter which year for employee last credit score. Example: 2024
     * @bodyParam isName int Indicates whether the name field is included in the output document. Example: 1
     * @bodyParam isPosition int Indicates whether the position field is included in the output document. Example: 1
     * @bodyParam isPositionDescription int Indicates whether the position description field is included in the output document. Example: 1
     * @bodyParam isEchelons int Indicates whether the echelons field is included in the output document. Example: 1
     * @bodyParam isGrade int Indicates whether the grade field is included in the output document. Example: 1
     * @bodyParam isNip int Indicates whether the NIP (National Identification Number) field is included in the output document. Example: 1
     * @bodyParam isBirthPlaceDate int Indicates whether the birth place and date field is included in the output document. Example: 1
     * @bodyParam isAge int Indicates whether the age field is included in the output document. Example: 1
     * @bodyParam isReligion int Indicates whether the religion field is included in the output document. Example: 1
     * @bodyParam isGender int Indicates whether the gender field is included in the output document. Example: 1
     * @bodyParam isMaritalStatus int Indicates whether the marital status field is included in the output document. Example: 1
     * @bodyParam isAgency int Indicates whether the agency field is included in the output document. Example: 1
     * @bodyParam isOrganization int Indicates whether the organization field is included in the output document. Example: 1
     * @bodyParam isWorkUnit int Indicates whether the work unit field is included in the output document. Example: 1
     * @bodyParam isNoWorker int Indicates whether the worker number field is included in the output document. Example: 1
     * @bodyParam isWorkDuration int Indicates the duration of work. Example: 1
     * @bodyParam isGradeDuration int Indicates whether the grade duration field is included in the output document. Example: 1
     * @bodyParam isNPWP int Indicates whether the NPWP field is included in the output document. Example: 1
     * @bodyParam isEmployeeStatus int Indicates whether the employee status field is included in the output document. Example: 1
     * @bodyParam isCurrentAddress int Indicates whether the current address field is included in the output document. Example: 1
     * @bodyParam isComplex int Indicates whether the complex field is included in the output document. Example: 1
     * @bodyParam isAssistanceType int Indicates whether the employee type assistance  field is included in the output document. Example: 1
     * @bodyParam isOutsourcingType int Indicates whether the employee type outsourcing  field is included in the output document. Example: 1
     * @bodyParam isHomeNumber int Indicates whether the home number field is included in the output document. Example: 1
     * @bodyParam isPhoneNumber int Indicates whether the phone number field is included in the output document. Example: 1
     * @bodyParam isOfficeAddress int Indicates whether the office address field is included in the output document. Example: 1
     * @bodyParam isOfficeNumber int Indicates whether the office number field is included in the output document. Example: 1
     * @bodyParam isEmail int Indicates whether the email field is included in the output document. Example: 1
     * @bodyParam isPensionCap int Indicates whether the pension cap field is included in the output document. Example: 1
     * @bodyParam isPositionHistory int Indicates whether the position history field is included in the output document. Example: 1
     * @bodyParam isGradeHistory int Indicates whether the grade history field is included in the output document. Example: 1
     * @bodyParam isTrainingStructural int Indicates whether the structural training field is included in the output document. Example: 1
     * @bodyParam isTrainingFunctional int Indicates whether the functional training field is included in the output document. Example: 1
     * @bodyParam isTrainingTechnique int Indicates whether the technique training field is included in the output document. Example: 1
     * @bodyParam isSKP int Indicates whether the SKP field is included in the output document. Example: 1
     * @bodyParam isRecognition int Indicates whether the recognition field is included in the output document. Example: 1
     * @bodyParam isNotes int Indicates whether the notes field is included in the output document. Example: 1
     * @bodyParam isEducationHistory int Indicates whether the education history field is included in the output document. Example: 1
     * @bodyParam isDisciplinary int Indicates whether the disciplinary field is included in the output document. Example: 1
     * @bodyParam isFamilyHistory int Indicates whether the family history field is included in the output document. Example: 1
     * @bodyParam isLeave int Indicates whether the leave field is included in the output document. Example: 1
     * @bodyParam isAssessment int Indicates whether the assessment field is included in the output document. Example: 1
     * @bodyParam isCompetency int Indicates whether the competency field is included in the output document. Example: 1
     * @bodyParam isTalentPool int Indicates whether the talent pool field is included in the output document. Example: 1
     */

    public function employees(ExportEmployeesRequest $request)
    {
        // filter user to get ids
        $users = DB::table('users')
            ->leftJoin('echelons', 'users.echelon_id', '=', 'echelons.id')
            ->leftJoin('user_educations', 'users.id', '=', 'user_educations.user_id')
            ->leftJoin('position_history_users', 'users.id', '=', 'position_history_users.user_id')
            ->leftJoin('user_credits', 'users.id', '=', 'user_credits.user_id')
            ->leftJoin('target_history_users', 'users.id', '=', 'target_history_users.user_id')
            ->leftJoin('target_histories', 'target_history_users.target_history_id', '=', 'target_histories.id')
            ->select('users.id');
        if (isset($request->min_age)) {
            $minAge = $request->input('min_age');
            $now = Carbon::now();

            $minDate = $now->copy()->subYears($minAge + 1)->addDay()->toDateString();

            $users->where('users.date_of_birth', '<=', $minDate);
        }
        if (isset($request->max_age)) {
            $maxAge = $request->input('max_age');
            $now = Carbon::now();

            $maxDate = $now->copy()->subYears($maxAge)->toDateString();

            $users->where('users.date_of_birth', '>=', $maxDate);
        }
        if (isset($request->grade_range)) {
            $gradeRanges = $request->input('grade_range', []);
            $now = Carbon::now();

            $users->where(function ($query) use ($gradeRanges, $now) {
                foreach ($gradeRanges as $range) {
                    [$minYears, $maxYears] = explode('-', $range);

                    $maxDate = $now->copy()->subYears($minYears)->toDateString();
                    $minDate = $now->copy()->subYears($maxYears + 1)->addDay()->toDateString();

                    $query->orWhereBetween('users.grade_effective_date', [$minDate, $maxDate]);
                }
            });
        }
        if (isset($request->total_working_duration)) {
            $gradeRanges = $request->input('total_working_duration', []);
            $now = Carbon::now();

            $users->where(function ($query) use ($gradeRanges, $now) {
                foreach ($gradeRanges as $range) {
                    [$minYears, $maxYears] = explode('-', $range);

                    $maxDate = $now->copy()->subYears($minYears)->toDateString();
                    $minDate = $now->copy()->subYears($maxYears + 1)->addDay()->toDateString();

                    $query->orWhereBetween('users.position_effective_date', [$minDate, $maxDate]);
                }
            });
        }
        if (isset($request->echelons)) {
            $users->whereIn('echelons.id', $request->echelons);
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
        if (isset($request->credit_period)) {
            $users->where('user_credits.period', $request->credit_period);
        }
        if (isset($request->credit_year)) {
            $users->where('user_credits.year', $request->credit_year);
        }
        if (isset($request->target_period)) {
            $users->where('target_histories.appraisal_period', $request->target_period);
        }
        if (isset($request->target_year)) {
            $users->where('target_histories.period_year', $request->target_year);
        }
        if (isset($request->work_behavior_rating)) {
            $users->where('target_history_users.work_behavior_rating', $request->work_behavior_rating);
        }
        if (isset($request->employee_performance_predicate)) {
            $users->where('target_history_users.employee_performance_predicate', $request->employee_performance_predicate);
        }
        if (isset($request->organizational_performance_achievement)) {
            $users->where('target_history_users.organizational_performance_achievement', $request->organizational_performance_achievement);
        }
        if (isset($request->marital_status)) {
            $users->whereIn('users.marital_status', $request->marital_status);
        }
        if (isset($request->employee)) {
            $users->whereIn('users.type', $request->employee);
        }
        if (isset($request->deputy)){
            $parentIds = DB::table('positions')->whereIn('id', $request->deputy)->pluck('parent_id')->toArray();
            $positionIds = array_merge($parentIds, $request->deputy);
            $users->whereIn('users.position_id', $positionIds);
        }

        $userIds = $users->pluck('users.id')->toArray();
        if (!$userIds) {
            return $this->response(400, 'Data pegawai tidak ditemukan');
        }

        // toggle field
        $toggleFieldBio = array();
        $toggleFieldBio['isName'] = $request->isName == 1;
        $toggleFieldBio['isNip'] = $request->isNip == 1;
        $toggleFieldBio['isBirthPlaceDate'] = $request->isBirthPlaceDate == 1;
        $toggleFieldBio['isAge'] = $request->isAge == 1;
        $toggleFieldBio['isReligion'] = $request->isReligion == 1;
        $toggleFieldBio['isGender'] = $request->isGender == 1;
        $toggleFieldBio['isMaritalStatus'] = $request->isMaritalStatus == 1;
        $toggleFieldBio['isEmployeeType'] = $request->isEmployeeType == 1;
        $toggleFieldBio['isAssistanceType'] = $request->isAssistanceType == 1;
        $toggleFieldBio['isOutsourcingType'] = $request->isOutsourcingType ==1;
        $toggleFieldBio['isDateCPNS'] = $request->isDateCPNS == 1;
        $toggleFieldBio['isStartDate'] = $request->isStartDate == 1;
        $toggleFieldBio['isEndDate'] = $request->isEndDate == 1;
        $toggleFieldBio['isWorkDuration'] = $request->isWorkDuration == 1; //note
        $toggleFieldBio['isGradeDuration'] = $request->isGradeDuration == 1;
        $toggleFieldBio['isPosition'] = $request->isPosition == 1;
        $toggleFieldBio['isDatePosition'] = $request->isDatePosition == 1;
        $toggleFieldBio['isEchelons'] = $request->isEchelons == 1;
        $toggleFieldBio['isEchelonDate'] = $request->isEchelonDate == 1;
        $toggleFieldBio['isPositionDescription'] = $request->isPositionDescription == 1; //keterangan
        $toggleFieldBio['isGrade'] = $request->isGrade == 1;
        $toggleFieldBio['isGradeDate'] = $request->isGradeDate == 1;
        $toggleFieldBio['isAgency'] = $request->isAgency == 1;
        $toggleFieldBio['isNoWorker'] = $request->isNoWorker == 1;
        $toggleFieldBio['isKarisu'] = $request->isKarisu == 1;
        $toggleFieldBio['isNPWP'] = $request->isNPWP == 1;
        $toggleFieldBio['isEmployeeStatus'] = $request->isEmployeeStatus == 1;
        $toggleFieldBio['isNoFamily'] = $request->isNoFamily == 1;
        $toggleFieldBio['isNIK'] = $request->isNIK == 1;
        $toggleFieldBio['isCurrentAddress'] = $request->isCurrentAddress == 1;
        $toggleFieldBio['isComplex'] = $request->isComplex == 1;
        $toggleFieldBio['isHomeNumber'] = $request->isHomeNumber == 1;
        $toggleFieldBio['isPhoneNumber'] = $request->isPhoneNumber == 1;
        $toggleFieldBio['isOfficeAddress'] = $request->isOfficeAddress == 1;
        $toggleFieldBio['isOfficeNumber'] = $request->isOfficeNumber == 1;
        $toggleFieldBio['isEmail'] = $request->isEmail == 1;
        $toggleFieldBio['isOfficeEmail'] = $request->isOfficeEmail == 1;
        $toggleFieldBio['isOrganization'] = $request->isOrganization == 1;
        $toggleFieldBio['isWorkUnit'] = $request->isWorkUnit == 1;
        $toggleFieldBio['isEmergencyContact'] = $request->isEmergencyContact ==1;
        $toggleFieldBio['isPensionCap'] = $request->isPensionCap == 1; //not ready
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
        if ($request->type == "csv") {
            return Excel::download(new employee($toggleFieldBio, $userIds), 'Employees-' . Carbon::now() . '.csv', \Maatwebsite\Excel\Excel::CSV);
        } else if ($request->type == "xlsx") {
            return Excel::download(new employee($toggleFieldBio, $userIds), 'Employees-' . Carbon::now() . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else if ($request->type == "pdf") {
            $tmp = sys_get_temp_dir();
            $usersData = DB::table('users');
            if ($toggleFieldBio['isName']) {
                $usersData->addSelect('users.name');
            }
            if ($toggleFieldBio['isPosition']) {
                $usersData->leftJoin('positions', 'users.position_id', '=', 'positions.id');
                $usersData->addSelect('positions.name as position_name');
            }
            if ($toggleFieldBio['isPositionDescription']){
                $usersData->addSelect('users.description');
            }
            if ($toggleFieldBio['isEchelons']) {
                $usersData->leftJoin('echelons', 'users.echelon_id', '=', 'echelons.id');
                $usersData->addSelect('echelons.name as echelons_name');
            }
            if ($toggleFieldBio['isGrade']) {
                $usersData->leftJoin('grades as g', 'users.grade_id', '=', 'g.id');
                $usersData->addSelect('g.name as grade_name');
            }
            if ($toggleFieldBio['isNip']) {
                $usersData->addSelect('users.employee_id_card_number', 'users.employee_registration_number');
            }
            if ($toggleFieldBio['isBirthPlaceDate']) {
                $usersData->addSelect('users.place_of_birth', 'users.date_of_birth');
            }
            if ($toggleFieldBio['isAge']) {
                $usersData->addSelect(DB::raw("TIMESTAMPDIFF(YEAR, users.date_of_birth, CURDATE()) AS age"));
            }
            if ($toggleFieldBio['isWorkUnit']) {
                $usersData->addSelect('users.employment_type_id  as work_unit');
            }
            if ($toggleFieldBio['isEmployeeStatus']) {
                $usersData->addSelect('users.employment_status');
            }
            if ($toggleFieldBio['isReligion']) {
                $usersData->addSelect('users.religion');
            }
            if ($toggleFieldBio['isGender']) {
                $usersData->addSelect('users.gender');
            }
            if ($toggleFieldBio['isMaritalStatus']) {
                $usersData->addSelect('users.marital_status');
            }
            if ($toggleFieldBio['isAgency']) {
                $usersData->leftJoin('institutions as i', 'users.institution_id', '=', 'i.id');
                $usersData->addSelect('i.name as institution_name');
            }
//            if ($toggleFieldBio['isOrganization']) {
//                $usersData->leftJoin('groups as o', 'users.organization_id', '=', 'o.id');
//                $usersData->addSelect('o.name as organization_name');
//            }
            if ($toggleFieldBio['isNoWorker']) {
                $usersData->addSelect('users.employee_id_number', 'users.employee_registration_number');
            }
            if ($toggleFieldBio['isGradeDuration']) {
                $usersData->addSelect(['users.grade_effective_date']);
            }
            if ($toggleFieldBio['isNPWP']) {
                $usersData->addSelect('users.id_tax');
            }
            if ($toggleFieldBio['isCurrentAddress']) {
                $usersData->addSelect('users.current_address');
            }
            if ($toggleFieldBio['isComplex']) {
                $usersData->leftJoin('residences as r', 'users.residence_id', '=', 'r.id');
                $usersData->addSelect('r.name as residence_name');
            }
            if ($toggleFieldBio['isHomeNumber']) {
                $usersData->addSelect('users.home_phone_number');
            }
            if ($toggleFieldBio['isPhoneNumber']) {
                $usersData->addSelect('users.mobile_phone');
            }
            if ($toggleFieldBio['isOfficeAddress']) {
                $usersData->addSelect('users.office_address');
            }
            if ($toggleFieldBio['isOfficeNumber']) {
                $usersData->addSelect('users.office_phone_number');
            }
            if ($toggleFieldBio['isEmail']) {
                $usersData->addSelect('users.email');
            }
            if ($toggleFieldBio['isPositionHistory']) {
                $gradeHistorySubquery = DB::table('grade_history_users as ghu');
                $gradeHistorySubquery->join('grades', 'grades.id', '=', 'ghu.grade_id');
                $gradeHistorySubquery->select('ghu.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>', grades.name, grades.code,
            ' (', ghu.decree_date, ',', ghu.decree_number, ')', '</li>') SEPARATOR ' ') as grade_history"));
                $gradeHistorySubquery->whereIn('ghu.user_id', $userIds);
                $gradeHistorySubquery->groupBy('ghu.user_id');
                $usersData->leftJoinSub($gradeHistorySubquery, 'grade_history', function ($join) {
                    $join->on('users.id', '=', 'grade_history.user_id');
                });
                $usersData->addSelect('grade_history.grade_history');
            }
            if ($toggleFieldBio['isGradeHistory']) {
                $positionHistorySubquery = DB::table('position_history_users as phu');
                $positionHistorySubquery->select('phu.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>', phu.position, '
            (', phu.decree_date, ',', phu.decree_number, ')' , '</li>') SEPARATOR ' ') as position_history"));
                $positionHistorySubquery->whereIn('phu.user_id', $userIds);
                $positionHistorySubquery->groupBy('phu.user_id');
                $usersData->leftJoinSub($positionHistorySubquery, 'position_history', function ($join) {
                    $join->on('users.id', '=', 'position_history.user_id');
                });
                $usersData->addSelect('position_history.position_history');
            }
            if ($toggleFieldBio['isTrainingStructural']) {
                $trainingStructuralSubquery = DB::table('training_histories as t');
                $trainingStructuralSubquery->join('training_history_users as ut', 't.id', '=', 'ut.training_history_id');
                $trainingStructuralSubquery->select('ut.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>', t.name, '
            (Periode: ', t.period_month, ' ', t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ',
            Organizer: ', t.organizer ,'</li>') SEPARATOR ' ') as structural_training_history"));
                $trainingStructuralSubquery->whereIn('ut.user_id', $userIds);
                $trainingStructuralSubquery->where('t.type', 1);
                $trainingStructuralSubquery->groupBy('ut.user_id');

                $usersData->leftJoinSub($trainingStructuralSubquery, 'structural_training_history', function ($join) {
                    $join->on('users.id', '=', 'structural_training_history.user_id');
                });

                $usersData->addSelect('structural_training_history.structural_training_history');
            }

            if ($toggleFieldBio['isTrainingFunctional']) {
                $trainingFunctionalSubquery = DB::table('training_histories as t');
                $trainingFunctionalSubquery->join('training_history_users as ut', 't.id', '=', 'ut.training_history_id');
                $trainingFunctionalSubquery->select('ut.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>', t.name, '
            (Periode: ', t.period_month, ' ', t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ',
            Organizer: ', t.organizer ,'</li>') SEPARATOR ' ' ) as functional_training_history "));
                $trainingFunctionalSubquery->whereIn('ut.user_id', $userIds);
                $trainingFunctionalSubquery->where('t.type', 2);
                $trainingFunctionalSubquery->groupBy('ut.user_id');

                $usersData->leftJoinSub($trainingFunctionalSubquery, 'functional_training_history', function ($join) {
                    $join->on('users.id', '=', 'functional_training_history.user_id');
                });

                $usersData->addSelect('functional_training_history.functional_training_history');
            }

            if ($toggleFieldBio['isTrainingTechnique']) {
                $trainingTechnicSubquery = DB::table('training_histories as t');
                $trainingTechnicSubquery->join('training_history_users as ut', 't.id', '=', 'ut.training_history_id');
                $trainingTechnicSubquery->select('ut.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>', t.name, '
            (Periode: ', t.period_month, ' ', t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ',
            Organizer: ', t.organizer, '</li>') SEPARATOR ' ') as technique_training_history"));
                $trainingTechnicSubquery->whereIn('ut.user_id', $userIds);
                $trainingTechnicSubquery->where('t.type', 3);
                $trainingTechnicSubquery->groupBy('ut.user_id');

                $usersData->leftJoinSub($trainingTechnicSubquery, 'technique_training_history', function ($join) {
                    $join->on('users.id', '=', 'technique_training_history.user_id');
                });

                $usersData->addSelect('technique_training_history.technique_training_history');
            }
            if ($toggleFieldBio['isRecognition']) {
                $recognitionSubquery = DB::table('recognition_histories as r');
                $recognitionSubquery->join('recognition_history_users as ur', 'r.id', '=', 'ur.recognition_history_id');
                $recognitionSubquery->join('recognitions', 'r.recognition_id', '=', 'recognitions.id');
                $recognitionSubquery->select('ur.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>',recognitions.name, '
            (Periode: ', r.period_month, ' ', r.period_year, ', Tanggal Terima: ', r.date_of_receipt, ') Decree: ',
            r.decree_number, ', Institusi: ', r.awarding_institution,'</li>') SEPARATOR ' ') as recognition_history"));
                $recognitionSubquery->whereIn('ur.user_id', $userIds);
                $recognitionSubquery->groupBy('ur.user_id');

                $usersData->leftJoinSub($recognitionSubquery, 'recognition_history', function ($join) {
                    $join->on('users.id', '=', 'recognition_history.user_id');
                });

                $usersData->addSelect('recognition_history.recognition_history');
            }
            if ($toggleFieldBio['isSKP']) {
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

                $usersData->leftJoinSub($skpSubquery, 'skp_history', function ($join) {
                    $join->on('users.id', '=', 'skp_history.user_id');
                });

                $usersData->addSelect('skp_history.skp_history');
            }
            if ($toggleFieldBio['isEducationHistory']) {
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

                $usersData->leftJoinSub($educationSubquery, 'education_history', function ($join) {
                    $join->on('users.id', '=', 'education_history.user_id');
                });

                $usersData->addSelect('education_history.education_history');
            }
            if ($toggleFieldBio['isDisciplinary']) {
                $disciplinarySubquery = DB::table('disciplinary_history_users as dhu')
                    ->join('disciplinary_histories as dh', 'dhu.disciplinary_history_id', '=', 'dh.id')
                    ->join('disciplinaries as d', 'dhu.disciplinary_id', '=', 'd.id')
                    ->select('dhu.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li> Golongan: ', dhu.grade, ' Posisi: ', dhu.position, ' (Periode: ', dh.period_month, ' ', dh.period_year, ', Tanggal Awal: ', dhu.start_date, ' Tanggal Akhir: ', dhu.end_date, ') Decree: ', dhu.decree_number, ', Kantor Otorisasi: ', dhu.authorizing_officer, ' Petugas: ', dhu.name_of_authorizing_officer, '</li>') SEPARATOR ' ') as disciplinary_history"))
                    ->whereIn('dhu.user_id', $userIds)
                    ->groupBy('dhu.user_id');

                $usersData->leftJoinSub($disciplinarySubquery, 'disciplinary_history', function ($join) {
                    $join->on('users.id', '=', 'disciplinary_history.user_id');
                });

                $usersData->addSelect('disciplinary_history.disciplinary_history');
            }
            if ($toggleFieldBio['isFamilyHistory']) {
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

                $usersData->leftJoinSub($familyHistory, 'family_history', function ($join) {
                    $join->on('users.id', '=', 'family_history.user_id');
                });

                $usersData->addSelect('family_history.family_history');
            }
            if ($toggleFieldBio['isLeave']) {
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

                $usersData->leftJoinSub($leaveSubquery, 'leave_history', function ($join) {
                    $join->on('users.id', '=', 'leave_history.user_id');
                });

                $usersData->addSelect('leave_history.leave_history');
            }
            if ($toggleFieldBio['isAssessment']) {
                $assessmentSubquery = DB::table('user_assessments as ua');
                $assessmentSubquery->select('ua.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Tanggal Assessment : ', ua.event_date, '
             Point: ', ua.point, ' Organizer : ', ua.organizer,'</li>') SEPARATOR ' ') as assessment_history"));
                $assessmentSubquery->whereIn('ua.user_id', $userIds);
                $assessmentSubquery->groupBy('ua.user_id');

                $usersData->leftJoinSub($assessmentSubquery, 'assessment_history', function ($join) {
                    $join->on('users.id', '=', 'assessment_history.user_id');
                });

                $usersData->addSelect('assessment_history.assessment_history');
            }
            if ($toggleFieldBio['isCompetency']) {
                $assessmentSubquery = DB::table('user_competencies as ua');
                $assessmentSubquery->select('ua.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Tanggal Assessment : ', ua.event_date, '
             Point: ', ua.point, ' Organizer : ', ua.organizer,'</li>') SEPARATOR ' ') as competency_history"));
                $assessmentSubquery->whereIn('ua.user_id', $userIds);
                $assessmentSubquery->groupBy('ua.user_id');

                $usersData->leftJoinSub($assessmentSubquery, 'competency_history', function ($join) {
                    $join->on('users.id', '=', 'competency_history.user_id');
                });

                $usersData->addSelect('competency_history.competency_history');
            }
            if ($toggleFieldBio['isTalentPool']) {
                $assessmentSubquery = DB::table('user_talents as ua');
                $assessmentSubquery->select('ua.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Tanggal Assessment : ', ua.event_date, '
             Point: ', ua.point, ' Organizer : ', ua.organizer,'</li>') SEPARATOR ' ') as talent_pool_history"));
                $assessmentSubquery->whereIn('ua.user_id', $userIds);
                $assessmentSubquery->groupBy('ua.user_id');

                $usersData->leftJoinSub($assessmentSubquery, 'talent_pool_history', function ($join) {
                    $join->on('users.id', '=', 'talent_pool_history.user_id');
                });

                $usersData->addSelect('talent_pool_history.talent_pool_history');
            }
            if ($toggleFieldBio['isNotes']) {
                $assessmentSubquery = DB::table('user_notes as un');
                $assessmentSubquery->join('users', 'un.giver_id', '=', 'users.id');
                $assessmentSubquery->select('un.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Catatan : ', un.description, '
             Pemberi catatan: ', users.name, ' Tanggal : ', un.created_at,'</li>') SEPARATOR ' ') as notes"));
                $assessmentSubquery->whereIn('un.user_id', $userIds);
                $assessmentSubquery->groupBy('un.user_id');

                $usersData->leftJoinSub($assessmentSubquery, 'notes', function ($join) {
                    $join->on('users.id', '=', 'notes.user_id');
                });

                $usersData->addSelect('notes.notes');
            }
            if ($toggleFieldBio['isEmployeeType'] == 1){
                $employmeeType = DB::table('employment_types as et');
                $employmeeType->join('users', 'et.id', '=', 'users.employment_type_id');
                $employmeeType->select('users.id as user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>',et.name,'</li>') SEPARATOR '') as employee_type"));
                $employmeeType->whereIn('users.id', $userIds);
                $employmeeType->groupBy('users.id');

                $usersData->leftJoinSub($employmeeType, 'employee_type', function ($join) {
                    $join->on('users.id', '=', 'employee_type.user_id');
                });

                $usersData->addSelect('employee_type.employee_type');
            }
            if ($toggleFieldBio['isEchelonDate'] == 1){
                $usersData->addSelect('users.echelon_effective_date');
            }
            if ($toggleFieldBio['isGradeDate'] == 1){
                $usersData->addSelect('users.grade_effective_date');
            }
            if ($toggleFieldBio['isNoFamily'] == 1){
                $usersData->addSelect('users.family_registration_number');
            }
            if ($toggleFieldBio['isNIK'] == 1){
                $usersData->addSelect('users.id_number');
            }
            if ($toggleFieldBio['isStartDate'] == 1){
                $usersData->addSelect('users.pns_effective_date');
            }
            if ($toggleFieldBio['isEndDate'] == 1){
                $usersData->addSelect('users.retirement_effective_date');
            }
            if ($toggleFieldBio['isDateCPNS'] == 1){
                $usersData->addSelect('users.cpns_effective_date');
            }
            if ($toggleFieldBio['isDatePosition'] == 1){
                $usersData->addSelect('users.position_effective_date');
            }
            if ($toggleFieldBio['isOutsourcingType'] == 1){
                $outsourcingSubquery = DB::table('employment_types as et');
                $outsourcingSubquery->join('users', 'et.id', '=', 'users.employment_type_id');
                $outsourcingSubquery->select('users.id as user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>',et.name,'</li>') SEPARATOR '') as outsource_type"));
                $outsourcingSubquery->where('et.type', 3);
                $outsourcingSubquery->whereIn('users.id', $userIds);
                $outsourcingSubquery->groupBy('users.id');

                $usersData->leftJoinSub($outsourcingSubquery, 'outsource_type', function ($join) {
                    $join->on('users.id', '=', 'outsource_type.user_id');
                });

                $usersData->addSelect('outsource_type.outsource_type');
            }
            if ($toggleFieldBio['isAssistanceType'] == 1){
                $assistanceSubquery = DB::table('employment_types as et');
                $assistanceSubquery->join('users', 'et.id', '=', 'users.employment_type_id');
                $assistanceSubquery->select('users.id as user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>',et.name,'</li>') SEPARATOR '') as assistance_type"));
                $assistanceSubquery->where('et.type', 3);
                $assistanceSubquery->whereIn('users.id', $userIds);
                $assistanceSubquery->groupBy('users.id');

                $usersData->leftJoinSub($assistanceSubquery, 'assistance_type', function ($join) {
                    $join->on('users.id', '=', 'assistance_type.user_id');
                });

                $usersData->addSelect('assistance_type.assistance_type');
            }
            if ($toggleFieldBio['isOfficeEmail'] == 1){
                $usersData->addSelect('users.office_email');
            }
            if ($toggleFieldBio['isKarisu'] == 1){
                $usersData->addSelect('users.karisu_number');
            }
            if ($toggleFieldBio['isEmergencyContact'] == 1){
                $usersData->addSelect('users.emergency_contact');
            }
            if ($toggleFieldBio['isWorkDuration'] == 1){
                $users->addSelect('users.position_effective_date');
            }
            $usersData->whereIn('users.id', $userIds);
            $usersData->groupBy('users.id');
            $usersData = $usersData->get();
            $usersDatas = $usersData->map(function ($item) {
                return (array) $item;
            })->toArray();
            $pdf = pdf::loadView('exports/employee-excel-pdf', [
                'userData' => $usersDatas,
                'toggleField' => $toggleFieldBio,
            ]);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setPaper("A4", "landscape");
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->set_option('fontDir', $tmp);
            $pdf->set_option('fontCache', $tmp);
            $pdf->set_option('tempDir', $tmp);
            return $pdf->download('Employees-' . Carbon::now() . '.pdf');
//            return Excel::download(new employee($toggleFieldBio, $userIds), 'Employees-' . Carbon::now(). '.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }
        return $this->response('400', 'File type provided is incorrect');
    }

    /**
     * Preview Export of Employees
     *
     * Preview Export of employees data preview
     * @group Export
     * @authenticated
     *
     * @bodyParam deputy int[] List of employee's deputy. Example: [1,2]
     * @bodyParam employee int[] List of employee's type. Example: [1,2]
     * @bodyParam echelons int[] List of echelons' id. Example: [1,2]
     * @bodyParam grades int[] List of employee's grade. Example: [1,2]
     * @bodyParam position_status int[] List of employee's position status. Example: [1,2]
     * @bodyParam education int[] List of employee's education level. Example: [1, 6]
     * @bodyParam gender int[] List of employee's gender. Example: [1,0]
     * @bodyParam marital_status int[] List of employee's marital status. Example: [1,4]
     * @bodyParam min_age int Filter minimum age of employee. Example: 30
     * @bodyParam max_age int Filter maximum age of employee. Example: 50
     * @bodyParam grade_range string[] List of employee's grade duration in year. Example: ["30-40", "40-50"]
     * @bodyParam total_working_duration string[] List range of total employee's working duration in year. Example: ["30-40", "40-50"]
     * @bodyParam target_period string Filter which period employees target_histories. Example: Q1
     * @bodyParam target_year int Filter the year of employees target_histories. Example: 2024
     * @bodyParam work_behavior_rating int Filter work behavior rating of employees target. Example: 1 for 'Diatas Ekspektasi'
     * @bodyParam employee_performance_predicate int Filter performance predicate for employees target. Example: 1 for 'Sangat Baik'
     * @bodyParam organizational_performance_achievement int Filter organizational performance achievement. Example: 3 for 'Cukup'
     * @bodyParam credit_period int Filter period for employee last credit score. Example: 1
     * @bodyParam credit_year int Filter which year for employee last credit score. Example: 2024
     * @bodyParam isName int Indicates whether the name field is included in the output document. Example: 1
     * @bodyParam isPosition int Indicates whether the position field is included in the output document. Example: 1
     * @bodyParam isPositionDescription int Indicates whether the position description field is included in the output document. Example: 1
     * @bodyParam isEchelons int Indicates whether the echelons field is included in the output document. Example: 1
     * @bodyParam isGrade int Indicates whether the grade field is included in the output document. Example: 1
     * @bodyParam isNip int Indicates whether the NIP (National Identification Number) field is included in the output document. Example: 1
     * @bodyParam isBirthPlaceDate int Indicates whether the birth place and date field is included in the output document. Example: 1
     * @bodyParam isAge int Indicates whether the age field is included in the output document. Example: 1
     * @bodyParam isReligion int Indicates whether the religion field is included in the output document. Example: 1
     * @bodyParam isGender int Indicates whether the gender field is included in the output document. Example: 1
     * @bodyParam isMaritalStatus int Indicates whether the marital status field is included in the output document. Example: 1
     * @bodyParam isAgency int Indicates whether the agency field is included in the output document. Example: 1
     * @bodyParam isOrganization int Indicates whether the organization field is included in the output document. Example: 1
     * @bodyParam isWorkUnit int Indicates whether the work unit field is included in the output document. Example: 1
     * @bodyParam isNoWorker int Indicates whether the worker number field is included in the output document. Example: 1
     * @bodyParam isWorkDuration int Indicates the duration of work. Example: 1
     * @bodyParam isGradeDuration int Indicates whether the grade duration field is included in the output document. Example: 1
     * @bodyParam isNPWP int Indicates whether the NPWP (Tax Identification Number) field is included in the output document. Example: 1
     * @bodyParam isEmployeeStatus int Indicates whether the employee status field is included in the output document. Example: 1
     * @bodyParam isEmployeeStatus int Indicates whether the employee type field is included in the output document. Example: 1
     * @bodyParam isCurrentAddress int Indicates whether the current address field is included in the output document. Example: 1
     * @bodyParam isComplex int Indicates whether the complex field is included in the output document. Example: 1
     * @bodyParam isHomeNumber int Indicates whether the home number field is included in the output document. Example: 1
     * @bodyParam isPhoneNumber int Indicates whether the phone number field is included in the output document. Example: 1
     * @bodyParam isOfficeAddress int Indicates whether the office address field is included in the output document. Example: 1
     * @bodyParam isOfficeNumber int Indicates whether the office number field is included in the output document. Example: 1
     * @bodyParam isEmail int Indicates whether the email field is included in the output document. Example: 1
     * @bodyParam isPensionCap int Indicates whether the pension cap field is included in the output document. Example: 1
     * @bodyParam isPositionHistory int Indicates whether the position history field is included in the output document. Example: 1
     * @bodyParam isGradeHistory int Indicates whether the grade history field is included in the output document. Example: 1
     * @bodyParam isTrainingStructural int Indicates whether the structural training field is included in the output document. Example: 1
     * @bodyParam isTrainingFunctional int Indicates whether the functional training field is included in the output document. Example: 1
     * @bodyParam isTrainingTechnique int Indicates whether the technique training field is included in the output document. Example: 1
     * @bodyParam isSKP int Indicates whether the SKP (Employee Performance Target) field is included in the output document. Example: 1
     * @bodyParam isRecognition int Indicates whether the recognition field is included in the output document. Example: 1
     * @bodyParam isNotes int Indicates whether the notes field is included in the output document. Example: 1
     * @bodyParam isEducationHistory int Indicates whether the education history field is included in the output document. Example: 1
     * @bodyParam isDisciplinary int Indicates whether the disciplinary field is included in the output document. Example: 1
     * @bodyParam isFamilyHistory int Indicates whether the family history field is included in the output document. Example: 1
     * @bodyParam isLeave int Indicates whether the leave field is included in the output document. Example: 1
     * @bodyParam isAssessment int Indicates whether the assessment field is included in the output document. Example: 1
     * @bodyParam isCompetency int Indicates whether the competency field is included in the output document. Example: 1
     * @bodyParam isTalentPool int Indicates whether the talent pool field is included in the output document. Example: 1
     */
    public function exportExcelsPreview(PreviewExportEmployeesRequest $request)
    {
        // filter user to get ids
        $users = DB::table('users')
            ->leftJoin('echelons', 'users.echelon_id', '=', 'echelons.id')
            ->leftJoin('user_educations', 'users.id', '=', 'user_educations.user_id')
            ->leftJoin('position_history_users', 'users.id', '=', 'position_history_users.user_id')
            ->leftJoin('user_credits', 'users.id', '=', 'user_credits.user_id')
            ->leftJoin('target_history_users', 'users.id', '=', 'target_history_users.user_id')
            ->leftJoin('target_histories', 'target_history_users.target_history_id', '=', 'target_histories.id')
            ->select('users.id');
        if (isset($request->employee_type)) {
            $users->whereIn('users.type', $request->employee_type);
        }
        if (isset($request->deputy)){
            $parentIds = DB::table('positions')->whereIn('id', $request->deputy)->pluck('parent_id')->toArray();
            $positionIds = array_merge($parentIds, $request->deputy);
            $users->whereIn('users.position_id', $positionIds);
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
        if (isset($request->gender)) {
            $users->whereIn('users.gender', $request->gender);
        }
//        if (isset($request->position_status)) {
//            $users->whereIn('position_history_users.position_status', $request->position_status);
//        }
        if (isset($request->min_age)) {
            $minAge = $request->input('min_age');
            $now = Carbon::now();
            $minDate = $now->copy()->subYears($minAge + 1)->addDay()->toDateString();
            $users->where('users.date_of_birth', '<=', $minDate);
        }

        if (isset($request->max_age)) {
            $maxAge = $request->input('max_age');
            $now = Carbon::now();
            $maxDate = $now->copy()->subYears($maxAge)->toDateString();
            $users->where('users.date_of_birth', '>=', $maxDate);
        }
        if (isset($request->marital_status)) {
            $users->whereIn('users.marital_status', $request->marital_status);
        }
        if (isset($request->grade_range)) {
            $gradeRanges = $request->input('grade_range', []);
            $now = Carbon::now();

            $users->where(function ($query) use ($gradeRanges, $now) {
                foreach ($gradeRanges as $range) {
                    [$minYears, $maxYears] = explode('-', $range);

                    $maxDate = $now->copy()->subYears($minYears)->toDateString();
                    $minDate = $now->copy()->subYears($maxYears + 1)->addDay()->toDateString();

                    $query->orWhereBetween('users.grade_effective_date', [$minDate, $maxDate]);
                }
            });
        }
        if (isset($request->total_working_duration)) {
            $gradeRanges = $request->input('total_working_duration', []);
            $now = Carbon::now();

            $users->where(function ($query) use ($gradeRanges, $now) {
                foreach ($gradeRanges as $range) {
                    [$minYears, $maxYears] = explode('-', $range);

                    $maxDate = $now->copy()->subYears($minYears)->toDateString();
                    $minDate = $now->copy()->subYears($maxYears + 1)->addDay()->toDateString();

                    $query->orWhereBetween('users.position_effective_date', [$minDate, $maxDate]);
                }
            });
        }
        if (isset($request->credit_period)) {
            $users->where('user_credits.period', $request->credit_period);
        }
        if (isset($request->credit_year)) {
            $users->where('user_credits.year', $request->credit_year);
        }
        if (isset($request->target_period)) {
            $users->where('target_histories.appraisal_period', $request->target_period);
        }
        if (isset($request->target_year)) {
            $users->where('target_histories.period_year', $request->target_year);
        }
        if (isset($request->work_behavior_rating)) {
            $users->where('target_history_users.work_behavior_rating', $request->work_behavior_rating);
        }
        if (isset($request->employee_performance_predicate)) {
            $users->where('target_history_users.employee_performance_predicate', $request->employee_performance_predicate);
        }
        if (isset($request->organizational_performance_achievement)) {
            $users->where('target_history_users.organizational_performance_achievement', $request->organizational_performance_achievement);
        }
        $userIds = $users->pluck('users.id')->toArray();
        if (!$userIds) {
            return $this->response(400, 'Data pegawai tidak ditemukan');
        }

        $usersPreview = DB::table('users');
        $toggleFieldBio = array();

        if ($this->request->isName == 1) {
            $usersPreview->addSelect('users.name');
        }
        if ($this->request->isNip == 1) {
            $usersPreview->addSelect('users.employee_id_card_number', 'users.employee_registration_number');
        }
        if ($this->request->isBirthPlaceDate == 1) {
            $usersPreview->addSelect('users.place_of_birth', 'users.date_of_birth');
        }
        if ($this->request->isAge == 1) {
            $usersPreview->addSelect(DB::raw("TIMESTAMPDIFF(YEAR, users.date_of_birth, CURDATE()) AS age"));
        }
        if ($this->request->isReligion == 1) {
            $usersPreview->addSelect('users.religion');
        }
        if ($this->request->isGender == 1) {
            $usersPreview->addSelect('users.gender');
        }
        if ($this->request->isMaritalStatus == 1) {
            $usersPreview->addSelect('users.marital_status');
        }
        if ($this->request->isPosition == 1) {
            $usersPreview->leftJoin('positions', 'users.position_id', '=', 'positions.id');
            $usersPreview->addSelect('positions.name as position_name');
        }
        if ($this->request->isPositionDescription == 1){
            $usersPreview->addSelect('users.description');
        }
        if ($this->request->isEchelons == 1) {
            $usersPreview->leftJoin('echelons', 'users.echelon_id', '=', 'echelons.id');
            $usersPreview->addSelect('echelons.name as echelons_name');
        }
        if ($this->request->isGrade == 1) {
            $usersPreview->leftJoin('grades as g', 'users.grade_id', '=', 'g.id');
            $usersPreview->addSelect('g.name as grade_name');
        }
        if ($this->request->isWorkUnit == 1) {
            $users->addSelect('users.employment_type_id  as work_unit');
        }
        if ($this->request->isEmployeeStatus == 1) {
            $usersPreview->addSelect('users.employment_status');
        }
        if ($this->request->isAgency == 1) {
            $usersPreview->leftJoin('institutions as i', 'users.institution_id', '=', 'i.id');
            $usersPreview->addSelect('i.name as institution_name');
        }
//        if ($this->request->isOrganization == 1) {
//            $usersPreview->leftJoin('groups as o', 'users.organization_id', '=', 'o.id');
//            $usersPreview->addSelect('o.name as organization_name');
//        }
        if ($this->request->isNoWorker == 1) {
            $users->addSelect('users.employee_registration_number');
        }
        //add full work duration later
        if ($this->request->isGradeDuration == 1) {
            $usersPreview->addSelect(['users.grade_effective_date']);
        }
        if ($this->request->isNPWP == 1) {
            $usersPreview->addSelect('users.id_tax');
        }
        if ($this->request->isCurrentAddress == 1) {
            $usersPreview->addSelect('users.current_address');
        }
        if ($this->request->isComplex == 1) {
            $usersPreview->leftJoin('residences as r', 'users.residence_id', '=', 'r.id');
            $usersPreview->addSelect('r.name as residence_name');
        }
        if ($this->request->isHomeNumber == 1) {
            $usersPreview->addSelect('users.home_phone_number');
        }
        if ($this->request->isPhoneNumber == 1) {
            $usersPreview->addSelect('users.mobile_phone');
        }
        if ($this->request->isOfficeAddress == 1) {
            $usersPreview->addSelect('users.office_address');
        }
        if ($this->request->isOfficeNumber == 1) {
            $usersPreview->addSelect('users.office_phone_number');
        }
        if ($this->request->isEmail == 1) {
            $usersPreview->addSelect('users.email');
        }
        if ($this->request->isPositionHistory == 1) {
            $gradeHistorySubquery = DB::table('grade_history_users as ghu');
            $gradeHistorySubquery->join('grades', 'grades.id', '=', 'ghu.grade_id');
            $gradeHistorySubquery->select('ghu.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>', grades.name, grades.code,
            ' (', ghu.decree_date, ',', ghu.decree_number, ')', '</li>') SEPARATOR ' ') as grade_history"));
            $gradeHistorySubquery->whereIn('ghu.user_id', $userIds);
            $gradeHistorySubquery->groupBy('ghu.user_id');
            $usersPreview->leftJoinSub($gradeHistorySubquery, 'grade_history', function ($join) {
                $join->on('users.id', '=', 'grade_history.user_id');
            });
            $usersPreview->addSelect('grade_history.grade_history');
        }
        if ($this->request->isGradeHistory == 1) {
            $positionHistorySubquery = DB::table('position_history_users as phu');
            $positionHistorySubquery->select('phu.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>', phu.position, '
            (', phu.decree_date, ',', phu.decree_number, ')' , '</li>') SEPARATOR ' ') as position_history"));
            $positionHistorySubquery->whereIn('phu.user_id', $userIds);
            $positionHistorySubquery->groupBy('phu.user_id');
            $usersPreview->leftJoinSub($positionHistorySubquery, 'position_history', function ($join) {
                $join->on('users.id', '=', 'position_history.user_id');
            });
            $usersPreview->addSelect('position_history.position_history');
        }
        if ($this->request->isTrainingStructural == 1) {
            $trainingStructuralSubquery = DB::table('training_histories as t');
            $trainingStructuralSubquery->join('training_history_users as ut', 't.id', '=', 'ut.training_history_id');
            $trainingStructuralSubquery->select('ut.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>', t.name, '
            (Periode: ', t.period_month, ' ', t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ',
            Organizer: ', t.organizer ,'</li>') SEPARATOR ' ') as structural_training_history"));
            $trainingStructuralSubquery->whereIn('ut.user_id', $userIds);
            $trainingStructuralSubquery->where('t.type', 1);
            $trainingStructuralSubquery->groupBy('ut.user_id');

            $usersPreview->leftJoinSub($trainingStructuralSubquery, 'structural_training_history', function ($join) {
                $join->on('users.id', '=', 'structural_training_history.user_id');
            });

            $usersPreview->addSelect('structural_training_history.structural_training_history');
        }

        if ($this->request->isTrainingFunctional == 1)  {
            $trainingFunctionalSubquery = DB::table('training_histories as t');
            $trainingFunctionalSubquery->join('training_history_users as ut', 't.id', '=', 'ut.training_history_id');
            $trainingFunctionalSubquery->select('ut.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>', t.name, '
            (Periode: ', t.period_month, ' ', t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ',
            Organizer: ', t.organizer ,'</li>') SEPARATOR ' ' ) as functional_training_history "));
            $trainingFunctionalSubquery->whereIn('ut.user_id', $userIds);
            $trainingFunctionalSubquery->where('t.type', 2);
            $trainingFunctionalSubquery->groupBy('ut.user_id');

            $usersPreview->leftJoinSub($trainingFunctionalSubquery, 'functional_training_history', function ($join) {
                $join->on('users.id', '=', 'functional_training_history.user_id');
            });

            $usersPreview->addSelect('functional_training_history.functional_training_history');
        }

        if ($this->request->isTrainingTechnique == 1) {
            $trainingTechnicSubquery = DB::table('training_histories as t');
            $trainingTechnicSubquery->join('training_history_users as ut', 't.id', '=', 'ut.training_history_id');
            $trainingTechnicSubquery->select('ut.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>', t.name, '
            (Periode: ', t.period_month, ' ', t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ',
            Organizer: ', t.organizer, '</li>') SEPARATOR ' ') as technique_training_history"));
            $trainingTechnicSubquery->whereIn('ut.user_id', $userIds);
            $trainingTechnicSubquery->where('t.type', 3);
            $trainingTechnicSubquery->groupBy('ut.user_id');

            $usersPreview->leftJoinSub($trainingTechnicSubquery, 'technique_training_history', function ($join) {
                $join->on('users.id', '=', 'technique_training_history.user_id');
            });

            $usersPreview->addSelect('technique_training_history.technique_training_history');
        }
        if ($this->request->isRecognition == 1) {
            $recognitionSubquery = DB::table('recognition_histories as r');
            $recognitionSubquery->join('recognition_history_users as ur', 'r.id', '=', 'ur.recognition_history_id');
            $recognitionSubquery->join('recognitions', 'r.recognition_id', '=', 'recognitions.id');
            $recognitionSubquery->select('ur.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li>',recognitions.name, '
            (Periode: ', r.period_month, ' ', r.period_year, ', Tanggal Terima: ', r.date_of_receipt, ') Decree: ',
            r.decree_number, ', Institusi: ', r.awarding_institution,'</li>') SEPARATOR ' ') as recognition_history"));
            $recognitionSubquery->whereIn('ur.user_id', $userIds);
            $recognitionSubquery->groupBy('ur.user_id');

            $usersPreview->leftJoinSub($recognitionSubquery, 'recognition_history', function ($join) {
                $join->on('users.id', '=', 'recognition_history.user_id');
            });

            $usersPreview->addSelect('recognition_history.recognition_history');
        }
        if ($this->request->isSKP == 1) {
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

            $usersPreview->leftJoinSub($skpSubquery, 'skp_history', function ($join) {
                $join->on('users.id', '=', 'skp_history.user_id');
            });

            $usersPreview->addSelect('skp_history.skp_history');
        }
        if ($this->request->isEducationHistory == 1) {
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

            $usersPreview->leftJoinSub($educationSubquery, 'education_history', function ($join) {
                $join->on('users.id', '=', 'education_history.user_id');
            });

            $usersPreview->addSelect('education_history.education_history');
        }
        if ($this->request->isDisciplinary == 1) {
            $disciplinarySubquery = DB::table('disciplinary_history_users as dhu')
                ->join('disciplinary_histories as dh', 'dhu.disciplinary_history_id', '=', 'dh.id')
                ->join('disciplinaries as d', 'dhu.disciplinary_id', '=', 'd.id')
                ->select('dhu.user_id', DB::raw("GROUP_CONCAT(CONCAT('<li> Golongan: ', dhu.grade, ' Posisi: ', dhu.position, ' (Periode: ', dh.period_month, ' ', dh.period_year, ', Tanggal Awal: ', dhu.start_date, ' Tanggal Akhir: ', dhu.end_date, ') Decree: ', dhu.decree_number, ', Kantor Otorisasi: ', dhu.authorizing_officer, ' Petugas: ', dhu.name_of_authorizing_officer, '</li>') SEPARATOR ' ') as disciplinary_history"))
                ->whereIn('dhu.user_id', $userIds)
                ->groupBy('dhu.user_id');

            $usersPreview->leftJoinSub($disciplinarySubquery, 'disciplinary_history', function ($join) {
                $join->on('users.id', '=', 'disciplinary_history.user_id');
            });

            $usersPreview->addSelect('disciplinary_history.disciplinary_history');
        }
        if ($this->request->isFamilyHistory == 1) {
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

            $usersPreview->leftJoinSub($familyHistory, 'family_history', function ($join) {
                $join->on('users.id', '=', 'family_history.user_id');
            });

            $usersPreview->addSelect('family_history.family_history');
        }
        if ($this->request->isLeave == 1) {
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

            $usersPreview->leftJoinSub($leaveSubquery, 'leave_history', function ($join) {
                $join->on('users.id', '=', 'leave_history.user_id');
            });

            $usersPreview->addSelect('leave_history.leave_history');
        }
        if ($this->request->isAssessment == 1) {
            $assessmentSubquery = DB::table('user_assessments as ua');
            $assessmentSubquery->select('ua.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Tanggal Assessment : ', ua.event_date, '
             Point: ', ua.point, ' Organizer : ', ua.organizer,'</li>') SEPARATOR ' ') as assessment_history"));
            $assessmentSubquery->whereIn('ua.user_id', $userIds);
            $assessmentSubquery->groupBy('ua.user_id');

            $usersPreview->leftJoinSub($assessmentSubquery, 'assessment_history', function ($join) {
                $join->on('users.id', '=', 'assessment_history.user_id');
            });

            $usersPreview->addSelect('assessment_history.assessment_history');
        }
        if ($this->request->isCompetency == 1) {
            $assessmentSubquery = DB::table('user_competencies as ua');
            $assessmentSubquery->select('ua.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Tanggal Assessment : ', ua.event_date, '
             Point: ', ua.point, ' Organizer : ', ua.organizer,'</li>') SEPARATOR ' ') as competency_history"));
            $assessmentSubquery->whereIn('ua.user_id', $userIds);
            $assessmentSubquery->groupBy('ua.user_id');

            $usersPreview->leftJoinSub($assessmentSubquery, 'competency_history', function ($join) {
                $join->on('users.id', '=', 'competency_history.user_id');
            });

            $usersPreview->addSelect('competency_history.competency_history');
        }
        if ($this->request->isTalentPool == 1) {
            $assessmentSubquery = DB::table('user_talents as ua');
            $assessmentSubquery->select('ua.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Tanggal Assessment : ', ua.event_date, '
             Point: ', ua.point, ' Organizer : ', ua.organizer,'</li>') SEPARATOR ' ') as talent_pool_history"));
            $assessmentSubquery->whereIn('ua.user_id', $userIds);
            $assessmentSubquery->groupBy('ua.user_id');

            $usersPreview->leftJoinSub($assessmentSubquery, 'talent_pool_history', function ($join) {
                $join->on('users.id', '=', 'talent_pool_history.user_id');
            });

            $usersPreview->addSelect('talent_pool_history.talent_pool_history');
        }
        if ($this->request->isNotes == 1) {
            $assessmentSubquery = DB::table('user_notes as un');
            $assessmentSubquery->join('users', 'un.giver_id', '=', 'users.id');
            $assessmentSubquery->select('un.user_id', DB::raw("GROUP_CONCAT( CONCAT('<li> Catatan : ', un.description, '
             Pemberi catatan: ', users.name, ' Tanggal : ', un.created_at,'</li>') SEPARATOR ' ') as notes"));
            $assessmentSubquery->whereIn('un.user_id', $userIds);
            $assessmentSubquery->groupBy('un.user_id');

            $usersPreview->leftJoinSub($assessmentSubquery, 'notes', function ($join) {
                $join->on('users.id', '=', 'notes.user_id');
            });

            $usersPreview->addSelect('notes.notes');
        }
        if ($this->request->isEmployeeType == 1){
            $employmeeType = DB::table('employment_types as et');
            $employmeeType->join('users', 'et.id', '=', 'users.employment_type_id');
            $employmeeType->select('users.id as user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>',et.name,'</li>') SEPARATOR '') as employee_type"));
            $employmeeType->whereIn('users.id', $userIds);
            $employmeeType->groupBy('users.id');

            $usersPreview->leftJoinSub($employmeeType, 'employee_type', function ($join) {
                $join->on('users.id', '=', 'employee_type.user_id');
            });

            $usersPreview->addSelect('employee_type.employee_type');
        }
        if ($this->request->isEchelonDate == 1){
            $usersPreview->addSelect('users.echelon_effective_date');
        }
        if ($this->request->isGradeDate == 1){
            $usersPreview->addSelect('users.grade_effective_date');
        }
        if ($this->request->isNoFamily == 1){
            $usersPreview->addSelect('users.family_registration_number');
        }
        if ($this->request->isNIK == 1){
            $usersPreview->addSelect('users.id_number');
        }
        if ($this->request->isStartDate == 1){
            $usersPreview->addSelect('users.pns_effective_date');
        }
        if ($this->request->isEndDate == 1){
            $usersPreview->addSelect('users.retirement_effective_date');
        }
        if ($this->request->isDateCPNS == 1){
            $usersPreview->addSelect('users.cpns_effective_date');
        }
        if ($this->request->isDatePosition == 1){
            $usersPreview->addSelect('users.position_effective_date');
        }
        if ($this->request->isOutsourcingType == 1){
            $outsourcingSubquery = DB::table('employment_types as et');
            $outsourcingSubquery->join('users', 'et.id', '=', 'users.employment_type_id');
            $outsourcingSubquery->select('users.id as user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>',et.name,'</li>') SEPARATOR '') as outsource_type"));
            $outsourcingSubquery->where('et.type', 3);
            $outsourcingSubquery->whereIn('users.id', $userIds);
            $outsourcingSubquery->groupBy('users.id');

            $usersPreview->leftJoinSub($outsourcingSubquery, 'outsource_type', function ($join) {
                $join->on('users.id', '=', 'outsource_type.user_id');
            });

            $usersPreview->addSelect('outsource_type.outsource_type');
        }
        if ($this->request->isAssistanceType == 1){
            $assistanceSubquery = DB::table('employment_types as et');
            $assistanceSubquery->join('users', 'et.id', '=', 'users.employment_type_id');
            $assistanceSubquery->select('users.id as user_id', DB::raw("GROUP_CONCAT( CONCAT('<li>',et.name,'</li>') SEPARATOR '') as assistance_type"));
            $assistanceSubquery->where('et.type', 3);
            $assistanceSubquery->whereIn('users.id', $userIds);
            $assistanceSubquery->groupBy('users.id');

            $usersPreview->leftJoinSub($assistanceSubquery, 'assistance_type', function ($join) {
                $join->on('users.id', '=', 'assistance_type.user_id');
            });

            $usersPreview->addSelect('assistance_type.assistance_type');
        }
        if ($this->request->isOfficeEmail == 1){
            $usersPreview->addSelect('users.office_email');
        }
        if ($this->request->isKarisu == 1){
            $usersPreview->addSelect('users.karisu_number');
        }
        if ($this->request->isEmergencyContact == 1){
            $usersPreview->addSelect('users.emergency_contact');
        }
        if ($this->request->isWorkDuration == 1 ){
            $usersPreview->addSelect(DB::raw("TIMESTAMPDIFF(YEAR, users.position_effective_date, CURDATE()) AS work_duration"));
        }
        $usersPreview->whereIn('users.id', $userIds);
        $usersPreview->groupBy('users.id');
        $usersPreview = $usersPreview->get();
        $usersPreviewData = $usersPreview->map(function ($item) {
            return (array) $item;
        })->toArray();
        $toggleFieldBio['isName'] = $request->isName == 1;
        $toggleFieldBio['isNip'] = $request->isNip == 1;
        $toggleFieldBio['isBirthPlaceDate'] = $request->isBirthPlaceDate == 1;
        $toggleFieldBio['isAge'] = $request->isAge == 1;
        $toggleFieldBio['isReligion'] = $request->isReligion == 1;
        $toggleFieldBio['isGender'] = $request->isGender == 1;
        $toggleFieldBio['isMaritalStatus'] = $request->isMaritalStatus == 1;
        $toggleFieldBio['isEmployeeType'] = $request->isEmployeeType == 1;
        $toggleFieldBio['isAssistanceType'] = $request->isAssistanceType == 1;
        $toggleFieldBio['isOutsourcingType'] = $request->isOutsourcingType ==1;
        $toggleFieldBio['isDateCPNS'] = $request->isDateCPNS == 1;
        $toggleFieldBio['isStartDate'] = $request->isStartDate == 1;
        $toggleFieldBio['isEndDate'] = $request->isEndDate == 1;
        $toggleFieldBio['isWorkDuration'] = $request->isWorkDuration == 1; //note
        $toggleFieldBio['isGradeDuration'] = $request->isGradeDuration == 1;
        $toggleFieldBio['isPosition'] = $request->isPosition == 1;
        $toggleFieldBio['isDatePosition'] = $request->isDatePosition == 1;
        $toggleFieldBio['isEchelons'] = $request->isEchelons == 1;
        $toggleFieldBio['isEchelonDate'] = $request->isEchelonDate == 1;
        $toggleFieldBio['isPositionDescription'] = $request->isPositionDescription == 1; //keterangan
        $toggleFieldBio['isGrade'] = $request->isGrade == 1;
        $toggleFieldBio['isGradeDate'] = $request->isGradeDate == 1;
        $toggleFieldBio['isAgency'] = $request->isAgency == 1;
        $toggleFieldBio['isNoWorker'] = $request->isNoWorker == 1;
        $toggleFieldBio['isKarisu'] = $request->isKarisu == 1;
        $toggleFieldBio['isNPWP'] = $request->isNPWP == 1;
        $toggleFieldBio['isEmployeeStatus'] = $request->isEmployeeStatus == 1;
        $toggleFieldBio['isNoFamily'] = $request->isNoFamily == 1;
        $toggleFieldBio['isNIK'] = $request->isNIK == 1;
        $toggleFieldBio['isCurrentAddress'] = $request->isCurrentAddress == 1;
        $toggleFieldBio['isComplex'] = $request->isComplex == 1;
        $toggleFieldBio['isHomeNumber'] = $request->isHomeNumber == 1;
        $toggleFieldBio['isPhoneNumber'] = $request->isPhoneNumber == 1;
        $toggleFieldBio['isOfficeAddress'] = $request->isOfficeAddress == 1;
        $toggleFieldBio['isOfficeNumber'] = $request->isOfficeNumber == 1;
        $toggleFieldBio['isEmail'] = $request->isEmail == 1;
        $toggleFieldBio['isOfficeEmail'] = $request->isOfficeEmail == 1;
        $toggleFieldBio['isOrganization'] = $request->isOrganization == 1;
        $toggleFieldBio['isWorkUnit'] = $request->isWorkUnit == 1;
        $toggleFieldBio['isEmergencyContact'] = $request->isEmergencyContact ==1;
        $toggleFieldBio['isPensionCap'] = $request->isPensionCap == 1; //not ready

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
