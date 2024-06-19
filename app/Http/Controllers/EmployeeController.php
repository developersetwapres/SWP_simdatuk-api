<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\CreateEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Repositories\AssessmentRepository;
use App\Repositories\CreditScoreRepository;
use App\Repositories\DisciplinaryRepository;
use App\Repositories\EducationRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\GradeRepository;
use App\Repositories\LeaveRepository;
use App\Repositories\NoteRepository;
use App\Repositories\PerformanceRepository;
use App\Repositories\PositionRepository;
use App\Repositories\RecognitionRepository;
use App\Repositories\TargetRepository;
use App\Repositories\TrainingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @group Employee
 */
class EmployeeController extends Controller
{
    protected $employeeRepository;
    protected $educationRepository;
    protected $positionRepository;
    protected $gradeRepository;
    protected $trainingRepository;
    protected $recognitionRepository;
    protected $targetRepository;
    protected $performanceRepository;
    protected $disciplinaryRepository;
    protected $leaveRepository;
    protected $noteRepository;
    protected $assessmentRepository;
    protected $creditscoreRepository;

    public function __construct(
        Request $request,
        EmployeeRepository $employeeRepository,
        EducationRepository $educationRepository,
        PositionRepository $positionRepository,
        GradeRepository $gradeRepository,
        TrainingRepository $trainingRepository,
        RecognitionRepository $recognitionRepository,
        TargetRepository $targetRepository,
        PerformanceRepository $performanceRepository,
        DisciplinaryRepository $disciplinaryRepository,
        LeaveRepository $leaveRepository,
        NoteRepository $noteRepository,
        AssessmentRepository $assessmentRepository,
        CreditScoreRepository $creditscoreRepository,
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
            'assessments'
        );
        $this->employeeRepository = $employeeRepository;
        $this->educationRepository = $educationRepository;
        $this->positionRepository = $positionRepository;
        $this->gradeRepository = $gradeRepository;
        $this->trainingRepository = $trainingRepository;
        $this->recognitionRepository = $recognitionRepository;
        $this->targetRepository = $targetRepository;
        $this->performanceRepository = $performanceRepository;
        $this->disciplinaryRepository = $disciplinaryRepository;
        $this->leaveRepository = $leaveRepository;
        $this->noteRepository = $noteRepository;
        $this->assessmentRepository = $assessmentRepository;
        $this->creditscoreRepository = $creditscoreRepository;
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
        $users->select(
            'u.id',
            'u.photo_profile',
            DB::raw("CONCAT(u.title_prefix, ' ', u.name, ' ', u.title_suffix) as name"),
            'u.employee_id_number',
            'u.employee_registration_number',
            'p.name as position_name',
            DB::raw("CONCAT(g.name, ' ', g.code) as grade_name"),
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
            $path = $this->uploadDocument($this->request->file('photo_profile'), 'photo_profile');
            $this->posted['photo_profile'] = $path;
        }

        if ($this->request->hasFile('employee_id_card')) {
            $path = $this->uploadDocument($this->request->file('employee_id_card'), 'employee_id_card');
            $this->posted['employee_id_card'] = $path;
        }

        try {
            DB::beginTransaction();
            $userId = DB::table('users')->insertGetIdTs($this->posted);

            // Insert Educations
            if (isset($this->request->educations)) {
                $educations = array();
                foreach ($this->request->educations as $education) {
                    if (is_file($education['degree_document'])) {
                        $education['degree_document'] = $this->uploadDocument($education['degree_document'], 'degree_document');
                    }
                    $education['user_id'] = $userId;
                    array_push($educations, $education);
                }
                DB::table('user_educations')->insertTs($educations);
            }

            // Insert Disciplinaries
            if (isset($this->request->disciplinaries)) {
                $disciplinaries = array();
                foreach ($this->request->disciplinaries as $discipline) {
                    $discipline['user_id'] = $userId;
                    array_push($disciplinaries, $discipline);
                }
                DB::table('user_disciplinaries')->insertTs($disciplinaries);
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
                    if (is_file($leave['leave_letter'])) {
                        $leave['leave_letter'] = $this->uploadDocument($leave['leave_letter'], 'leave_letter');
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

            // Insert Assessments
            if (isset($this->request->assessments)) {
                $assessments = array();
                foreach ($this->request->assessments as $assessment) {
                    if (is_file($assessment['assessment_document'])) {
                        $assessment['assessment_document'] = $this->uploadDocument($assessment['assessment_document'], 'assessment_document');
                    }
                    $assessment['user_id'] = $userId;
                    array_push($assessments, $assessment);
                }
                DB::table('user_assessments')->insertTs($assessments);
            }
            if (isset($this->request->credit_score)) {
                $credit_scores = array();
                foreach ($this->request->credit_score as $credit_score) {
                    $credit_score['user_id'] = $userId;
                    array_push($credit_scores, $credit_score);
                }
                DB::table('user_credit_score')->insertTs($credit_scores);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
        }

        return $this->response(200, 'Pegawai berhasil ditambah.');
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
        $positions = $this->positionRepository->getDetail($this->request->id);
        $grades = $this->gradeRepository->getDetail($this->request->id);
        $structurals = $this->trainingRepository->getDetail($this->request->id, 1);
        $functionals = $this->trainingRepository->getDetail($this->request->id, 2);
        $technicals = $this->trainingRepository->getDetail($this->request->id, 3);
        $recognitions = $this->recognitionRepository->getDetail($this->request->id);
        $targets = $this->targetRepository->getDetail($this->request->id);
        $performances = $this->performanceRepository->getDetail($this->request->id);
        $disciplinaries = $this->disciplinaryRepository->getDetail($this->request->id);
        $leaves = $this->leaveRepository->getDetail($this->request->id);
        $notes = $this->noteRepository->getDetail($this->request->id);
        $assessments = $this->assessmentRepository->getDetail($this->request->id, 1);
        $competencies = $this->assessmentRepository->getDetail($this->request->id, 2);
        $talents = $this->assessmentRepository->getDetail($this->request->id, 3);
        $creditScore = $this->creditscoreRepository->getDetail($this->request->id);

        $employee->educations = $educations;
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
        $employee->assessments = $assessments;
        $employee->competencies = $competencies;
        $employee->talents = $talents;
        $employee->creditScore = $creditScore;

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

    }
}
