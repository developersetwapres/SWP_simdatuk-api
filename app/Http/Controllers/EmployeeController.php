<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\CreateEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Repositories\AssessmentRepository;
use App\Repositories\CompetencyRepository;
use App\Repositories\CreditRepository;
use App\Repositories\DisciplinaryRepository;
use App\Repositories\EducationRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\FamilyRepository;
use App\Repositories\GradeRepository;
use App\Repositories\LeaveRepository;
use App\Repositories\NoteRepository;
use App\Repositories\PerformanceRepository;
use App\Repositories\PositionRepository;
use App\Repositories\RecognitionRepository;
use App\Repositories\TalentRepository;
use App\Repositories\TargetRepository;
use App\Repositories\TrainingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

/**
 * @group Employee
 */
class EmployeeController extends Controller
{
    protected $employeeRepository;
    protected $educationRepository;
    protected $familyRepository;
    protected $positionRepository;
    protected $gradeRepository;
    protected $trainingRepository;
    protected $recognitionRepository;
    protected $targetRepository;
    protected $performanceRepository;
    protected $disciplinaryRepository;
    protected $leaveRepository;
    protected $noteRepository;
    protected $creditRepository;
    protected $assessmentRepository;
    protected $competencyRepository;
    protected $talentRepository;

    protected $request;
    protected $posted;

    public function __construct(
        Request $request,
        EmployeeRepository $employeeRepository,
        EducationRepository $educationRepository,
        FamilyRepository $familyRepository,
        PositionRepository $positionRepository,
        GradeRepository $gradeRepository,
        TrainingRepository $trainingRepository,
        RecognitionRepository $recognitionRepository,
        TargetRepository $targetRepository,
        PerformanceRepository $performanceRepository,
        DisciplinaryRepository $disciplinaryRepository,
        LeaveRepository $leaveRepository,
        NoteRepository $noteRepository,
        CreditRepository $creditRepository,
        AssessmentRepository $assessmentRepository,
        CompetencyRepository $competencyRepository,
        TalentRepository $talentRepository,
    ) {
        $this->request = $request;
        $this->posted = $request->except(
            '_token',
            '_method',
            'educations',
            'positions',
            'grades',
            'families',
            'leaves',
            'notes',
            'credits',
            'assessments',
            'competencies',
            'talents',
            'structurals',
            'functionals',
            'technicals',
            'targets',
            'performances',
            'disciplinaries',
        );
        $this->employeeRepository = $employeeRepository;
        $this->educationRepository = $educationRepository;
        $this->familyRepository = $familyRepository;
        $this->positionRepository = $positionRepository;
        $this->gradeRepository = $gradeRepository;
        $this->trainingRepository = $trainingRepository;
        $this->recognitionRepository = $recognitionRepository;
        $this->targetRepository = $targetRepository;
        $this->performanceRepository = $performanceRepository;
        $this->disciplinaryRepository = $disciplinaryRepository;
        $this->leaveRepository = $leaveRepository;
        $this->noteRepository = $noteRepository;
        $this->creditRepository = $creditRepository;
        $this->assessmentRepository = $assessmentRepository;
        $this->competencyRepository = $competencyRepository;
        $this->talentRepository = $talentRepository;
    }

    /**
     * Get List of Employee
     *
     * Retrieve all ASN/NON-ASN/OUTSOURCE employees.
     * @group Employee
     * Below are the CRUD API endpoints for managing employees categorized as ASN (Civil Servants), NON ASN (Non-Civil Servants), and OUTSOURCE (Outsourced Personnel):
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name or employee id number. Example: administrator
     * @queryParam type integer Refers to the type of employee 1=ASN 2=NON ASN 3=OUTSOURCE. Example: 1
     * @queryParam position_id integer Refers to the position of employee. Example: 1
     * @queryParam grade_id integer Refers to the grade of employee. Example: 1
     * @queryParam employment_type_id integer Refers to the employment type of employee. Example: 1
     * @queryParam religion integer Refers to the religion of employee 1=Islam 2=Kristen 3=Katolik 4=Hindu 5=Buddha 6=Konghucu. Example: 1
     * @queryParam month_of_birth integer Refers to the month of birth of employee 1-12. Example: 1
     * @queryParam employment_status integer Refers to the employment status 1=Aktif, 2=Pensiun, 3=Berhenti, 4=Meninggal, 5=Alih Status, 6=Aktif PS, 7=CLTN, 8=TBL, 9=Non Aktif. Example: 1
     * @response 200 {"code": 200, "message": "success", "data": [{"id": 32, "username": "admin", "employee_id_number": "0000000000000", "employee_registration_number": "0000000000000", "role_name": "administrator", "status": "Aktif"}], "pagination": {"total": 1, "count": 1, "per_page": 1, "current_page": 1, "total_pages": 1, "links": {"first_page": "http://localhost/api/users?page=1", "last_page": "http://localhost/api/users?page=1", "next_page": null, "prev_page": null}}}
     */
    public function index()
    {
        $messages = [
            'page.numeric' => 'Page harus berupa angka.',
            'page.min' => 'Page minimal harus 1 atau lebih.',
            'limit.numeric' => 'Limit harus berupa angka.',
            'limit.min' => 'Limit minimal harus 1 atau lebih.',
        ];

        $validatedData = $this->request->validate([
            'page' => 'nullable|numeric|min:1',
            'limit' => 'nullable|numeric|min:1',
        ], $messages);

        $users = DB::table('users as u');
        $users->leftJoin('positions as p', 'u.position_id', '=', 'p.id');
        $users->leftJoin('grades as g', 'u.grade_id', '=', 'g.id');
        $users->leftJoin('employment_types as et', 'u.employment_type_id', '=', 'et.id');
        $users->leftJoin('echelons as e', 'u.echelon_id', '=', 'e.id');
        $users->select(
            'u.id',
            'u.photo_profile',
            DB::raw("
                CASE
                    WHEN u.title_prefix IS NULL && u.title_suffix IS NULL THEN u.name
                    WHEN u.title_prefix IS NOT NULL && u.title_suffix IS NULL THEN CONCAT(u.title_prefix, ' ', u.name)
                    WHEN u.title_prefix IS NULL && u.title_suffix IS NOT NULL THEN CONCAT(u.name, ' ', u.title_suffix)
                    ELSE CONCAT(u.title_prefix, ' ',u.name, ' ',u.title_suffix)
                END AS name
            "),
            'u.employee_id_number',
            'u.employee_registration_number',
            'p.name as position_name',
            'e.name as echelon_name',
            'u.echelon_effective_date',
            DB::raw("CONCAT(g.name, ' ', g.code) as grade_name"),
            'u.grade_effective_date',
            'et.name as employment_type',
            'u.description'
        );
        $users->where(function ($query) {
            $query->where('u.name', 'like', '%' . $this->request->search . '%')
                ->orWhere('u.employee_id_number', 'like', '%' . $this->request->search . '%');
        });
        if (!is_null($this->request->type)) {
            $users->where('u.type', $this->request->type);
        }
        if (!is_null($this->request->position_id)) {
            $users->where('u.position_id', $this->request->position_id);
        }
        if (!is_null($this->request->grade_id)) {
            $users->where('u.grade_id', $this->request->grade_id);
        }
        if (!is_null($this->request->echelon_id)) {
            $users->where('u.echelon_id', $this->request->echelon_id);
        }
        if (!is_null($this->request->employment_type_id)) {
            $users->where('u.employment_type_id', $this->request->employment_type_id);
        }
        if (!is_null($this->request->religion)) {
            $users->where('u.religion', $this->request->religion);
        }
        if (!is_null($this->request->employment_status)) {
            $users->where('u.employment_status', $this->request->employment_status);
        }
        if (!is_null($this->request->month_of_birth)) {
            $users->whereMonth('u.date_of_birth', $this->request->month_of_birth);
        }
        if (!is_null($this->request->gender)) {
            $users->where('u.gender', $this->request->gender);
        }

        $users->orderBy('u.employment_status', 'asc');
        $users->orderBy('u.echelon_id', 'asc');
        $users->orderBy('u.position_id', 'asc');

        if (is_null($this->request->limit)) {
            $users = $users->get();
            $message = (count($users) < 1) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            foreach ($users as $key => $item) {
                $item->photo_profile = $this->getDocument($item->photo_profile, true);
            }
            return $this->response(200, $message, $users);
        } else {
            $users = $users->paginate($this->request->limit);
            $message = ($users->isEmpty()) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            foreach ($users->items() as $key => $item) {
                $item->photo_profile = $this->getDocument($item->photo_profile, true);
            }
            return $this->paginateResponse(200, $message, $users);
        }
    }

    /**
     * Create a New Employee
     *
     * Create a new ASN/NON-ASN/OUTSOURCE employee.
     * @group Employee
     * @authenticated
     * @response 200 {"code": 200,"message": "Pegawai berhasil ditambah.","data": null}
     */
    public function create(CreateEmployeeRequest $request)
    {
        $employmentType = DB::table('employment_types');
        $employmentType->where('id', $this->request->employment_type_id);
        $employmentType->where('status', true);
        $employmentType->where('type', $this->request->type);
        $employmentType = $employmentType->first();
        if (!$employmentType) {
            return $this->response(404, 'Jenis pegawai tidak ditemukan.');
        }

        $institution = DB::table('institutions');
        $institution->where('id', $this->request->institution_id);
        $institution = $institution->first();
        if (!$institution) {
            return $this->response(404, 'Institusi tidak ditemukan.');
        }

        if ($this->request->hasFile('photo_profile')) {
            $path = $this->uploadDocument($this->request->file('photo_profile'), 'photo_profile', $this->request->employee_id_number);
            $this->posted['photo_profile'] = $path;
        }

        if ($this->request->hasFile('employee_id_card')) {
            $path = $this->uploadDocument($this->request->file('employee_id_card'), 'employee_id_card', $this->request->employee_id_number);
            $this->posted['employee_id_card'] = $path;
        }

        try {
            DB::beginTransaction();
            $userId = DB::table('users')->insertGetIdTs($this->posted);

            // Insert Educations
            if (isset($this->request->educations)) {
                $educations = array();
                foreach ($this->request->educations as $education) {
                    if (isset($education['degree_document']) && is_file($education['degree_document'])) {
                        $education['degree_document'] = $this->uploadDocument($education['degree_document'], 'degree_document');
                    }
                    $education['user_id'] = $userId;
                    array_push($educations, $education);
                }
                DB::table('user_educations')->insertTs($educations);
            }

            // Insert Families
            if (isset($this->request->families)) {
                $families = array();
                foreach ($this->request->families as $family) {
                    $family['user_id'] = $userId;
                    array_push($families, $family);
                }
                DB::table('user_families')->insertTs($families);
            }

            // Insert Leaves
            if (isset($this->request->leaves)) {
                $leaves = array();
                foreach ($this->request->leaves as $leave) {
                    if (isset($leave['letter']) && is_file($leave['letter'])) {
                        $leave['letter'] = $this->uploadDocument($leave['letter'], 'letter');
                    }
                    $leave['user_id'] = $userId;
                    array_push($leaves, $leave);
                }
                DB::table('user_leaves')->insertTs($leaves);
            }

            // Insert Notes
            if (isset($this->request->notes)) {
                $notes = array();
                foreach ($this->request->notes as $note) {
                    $note['user_id'] = $userId;
                    $note['giver_id'] = $this->request->user()->id;
                    array_push($notes, $note);
                }
                DB::table('user_notes')->insertTs($notes);
            }

            // Insert Scores
            if (isset($this->request->credits)) {
                $credits = array();
                foreach ($this->request->credits as $credit) {
                    $credit['user_id'] = $userId;
                    array_push($credits, $credit);
                }
                DB::table('user_credits')->insertTs($credits);
            }

            // Insert Assessments
            if (isset($this->request->assessments)) {
                $assessments = array();
                foreach ($this->request->assessments as $assessment) {
                    if (isset($assessment['assessment_document']) && is_file($assessment['assessment_document'])) {
                        $assessment['assessment_document'] = $this->uploadDocument($assessment['assessment_document'], 'assessment_document');
                    }
                    $assessment['user_id'] = $userId;
                    array_push($assessments, $assessment);
                }
                DB::table('user_assessments')->insertTs($assessments);
            }

            // Insert Competencies
            if (isset($this->request->competencies)) {
                $competencies = array();
                foreach ($this->request->competencies as $competency) {
                    if (isset($competency['competency_document']) && is_file($competency['competency_document'])) {
                        $competency['competency_document'] = $this->uploadDocument($competency['competency_document'], 'competency_document');
                    }
                    $competency['user_id'] = $userId;
                    array_push($competencies, $competency);
                }
                DB::table('user_competencies')->insertTs($competencies);
            }

            // Insert Talents
            if (isset($this->request->talents)) {
                $talents = array();
                foreach ($this->request->talents as $talent) {
                    if (isset($talent['talent_document']) && is_file($talent['talent_document'])) {
                        $talent['talent_document'] = $this->uploadDocument($talent['talent_document'], 'talent_document');
                    }
                    $talent['user_id'] = $userId;
                    array_push($talents, $talent);
                }
                DB::table('user_talents')->insertTs($talents);
            }

            DB::commit();
            return $this->response(200, 'Pegawai berhasil ditambah.');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::warning($th);
            return $this->response(500, 'Pegawai gagal ditambah.');
        }
    }

    /**
     * Get Detail Employee by ID
     *
     * Retrieve details of a specific ASN/NON-ASN/OUTSOURCE employee.
     * @group Employee
     * @authenticated
     * @urlParam id Refers to the ID of Employee. Example: 1
     * @response 404 {"code": 404,"message": "Pegawai tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 1,"name": "Universitas Gadjah Mada","region": "Dalam negeri","address": "Daerah Istimewa Yogyakarta", "accreditation": "A", "photo_profile": "http://localhost/storage/avatars/8X1kJJ0kP0pg08dC0xTKLzfH88Doaegm.png"}}
     */
    public function show()
    {
        $employee = $this->employeeRepository->getDetail($this->request->id);
        if (!$employee) {
            return $this->response(404, 'Pegawai tidak ditemukan.');
        }

        $educations = $this->educationRepository->getDetail($this->request->id);
        $families = $this->familyRepository->getDetail($this->request->id);
        $positions = $this->positionRepository->getDetail($this->request->id);
        $grades = $this->gradeRepository->getDetail($this->request->id);
        $structurals = $this->trainingRepository->getDetail($this->request->id, 1);
        $functionals = $this->trainingRepository->getDetail($this->request->id, 2);
        $technicals = $this->trainingRepository->getDetail($this->request->id, 3);
        $recognitions = $this->recognitionRepository->getDetail($this->request->id); //penghargaan
        $targets = $this->targetRepository->getDetail($this->request->id); //SKP
        $performances = $this->performanceRepository->getDetail($this->request->id); //prestasi kerja
        $disciplinaries = $this->disciplinaryRepository->getDetail($this->request->id); //hukdis
        $leaves = $this->leaveRepository->getDetail($this->request->id);
        $notes = $this->noteRepository->getDetail($this->request->id);
        $credits = $this->creditRepository->getDetail($this->request->id);
        $assessments = $this->assessmentRepository->getDetail($this->request->id);
        $competencies = $this->competencyRepository->getDetail($this->request->id);
        $talents = $this->talentRepository->getDetail($this->request->id);
        $position = array_reverse($this->positionRepository->getRecursivePosition($employee->position_id));

        $employee->position = $position;
        $employee->educations = $educations;
        $employee->families = $families;
        $employee->positions = $positions;
        $employee->grades = $grades;
        $employee->structurals = $structurals;
        $employee->functionals = $functionals;
        $employee->technicals = $technicals;
        $employee->recognitions = $recognitions;
        $employee->targets = $targets;
        $employee->performances = $performances;
        $employee->disciplinaries = $disciplinaries;
        $employee->leaves = $leaves;
        $employee->notes = $notes;
        $employee->credits = $credits;
        $employee->assessments = $assessments;
        $employee->competencies = $competencies;
        $employee->talents = $talents;

        return $this->response(200, 'success', $employee);
    }

    /**
     * Update Employee by ID
     *
     * Update details of a specific ASN/NON-ASN/OUTSOURCE employee.
     * @group Employee
     * @authenticated
     * @urlParam id Refers to the ID of Employee. Example: 1
     * @response 404 {"code": 404,"message": "Pegawai tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Pegawai berhasil diupdate.","data": null}
     */
    public function update(UpdateEmployeeRequest $request)
    {
        $user = DB::table('users')
            ->where('id', $this->request->id)
            ->first();;

        if (!$user) {
            return $this->response(404, 'Pegawai tidak ditemukan.');
        }

        $user = DB::table('users');
        $user->where('id', $this->request->id);
        $user = $user->updateTs($this->posted);

        // 'educations',
        // 'positions',
        // 'grades',
        // 'families',
        // 'leaves',
        // 'notes',
        // 'credits',
        // 'assessments',
        // 'competencies',
        // 'talents',
        // 'structurals',
        // 'functionals',
        // 'technicals',
        // 'targets',
        // 'performances',
        // 'disciplinaries',

        if (isset($this->request->educations)) {
            // Get existing data
            $userEducations = DB::table('user_educations')
                ->where('user_id', $this->request->id)
                ->select('id');
            $userEducations = $userEducations->get();

            // Delete data
            $array1 = Arr::pluck($userEducations, 'id');
            $array2 = Arr::pluck($this->request->educations, 'id');
            $result = array_diff($array1, $array2);
            DB::table('user_educations')->whereIn('id', $result)->delete();

            foreach ($this->request->users as $user) {
                if (!is_null($user['id'])) {
                    // Update existing data
                    DB::table('user_educations')->where('id', $user['id'])->updateTs($user);
                } else {
                    // Insert new item
                    $user['disciplinary_history_id'] = $this->request->id;
                    array_push($users, $user);
                }
            }
            if (count($users) > 0) {
                DB::table('user_educations')->insertTs($users);
            }
        }

        dd($user, $request);
    }
}
