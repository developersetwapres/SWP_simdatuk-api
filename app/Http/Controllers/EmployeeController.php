<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\CreateEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Repositories\AssessmentRepository;
use App\Repositories\DisciplineRepository;
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
 *
 * APIs for employee
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
    protected $disciplineRepository;
    protected $leaveRepository;
    protected $noteRepository;
    protected $assessmentRepository;

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
        DisciplineRepository $disciplineRepository,
        LeaveRepository $leaveRepository,
        NoteRepository $noteRepository,
        AssessmentRepository $assessmentRepository,
    ) {
        $this->request = $request;
        $this->posted = $request->except(
            '_token',
            '_method',
            'educations',
            'positions',
            'grades',
            'salaries',
            'trainings',
            'recognitions',
            'performances',
            'targets',
            'disciplinaries',
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
        $this->disciplineRepository = $disciplineRepository;
        $this->leaveRepository = $leaveRepository;
        $this->noteRepository = $noteRepository;
        $this->assessmentRepository = $assessmentRepository;
    }

    /**
     * Get List of Employee
     * @group Employee
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam keyword string The keyword search field for the name or employee id number. Example: administrator
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
        $this->request->limit = ($this->request->limit) ? $this->request->limit : 10;

        $users = DB::table('users');
        $users->select('users.id', 'users.photo_profile', 'users.name', 'users.employee_id_number', 'users.employee_registration_number');
        $users->where('users.name', 'like', '%' . $this->request->keyword . '%');
        $users->orWhere('users.employee_id_number', 'like', '%' . $this->request->keyword . '%');
        $users = $users->paginate($this->request->limit);
        if ($users->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $users);
        }
        foreach ($users->items() as $key => $item) {
            $item->status = ($item == true) ? 'Aktif' : 'Nonaktif';
        }
        return $this->paginateResponse(200, 'success', $users);
    }

    /**
     * Create a New Employee
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

        // try {
        //     DB::beginTransaction();
        //     DB::commit();
        // } catch (\Throwable $th) {
        //     DB::rollback();
        // }
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

        // Insert Positions
        if (isset($this->request->positions)) {
            $positions = array();
            foreach ($this->request->positions as $position) {
                if (is_file($position['decree_document'])) {
                    $position['decree_document'] = $this->uploadDocument($position['decree_document'], 'decree_document');
                }
                $position['user_id'] = $userId;
                array_push($positions, $position);
            }
            DB::table('user_positions')->insertTs($positions);
        }

        // Insert Grades
        if (isset($this->request->grades)) {
            $grades = array();
            foreach ($this->request->grades as $grade) {
                if (is_file($grade['decree_document'])) {
                    $grade['decree_document'] = $this->uploadDocument($grade['decree_document'], 'decree_document');
                }
                $grade['user_id'] = $userId;
                array_push($grades, $grade);
            }
            DB::table('user_grades')->insertTs($grades);
        }

        // Insert Trainings
        if (isset($this->request->trainings)) {
            $trainings = array();
            foreach ($this->request->trainings as $training) {
                if (is_file($training['certificate'])) {
                    $training['certificate'] = $this->uploadDocument($training['certificate'], 'certificate');
                }
                $training['user_id'] = $userId;
                array_push($trainings, $training);
            }
            DB::table('user_trainings')->insertTs($trainings);
        }

        // Insert Recognitions
        if (isset($this->request->recognitions)) {
            $recognitions = array();
            foreach ($this->request->recognitions as $recognition) {
                $recognition['user_id'] = $userId;
                array_push($recognitions, $recognition);
            }
            DB::table('user_recognitions')->insertTs($recognitions);
        }

        // Insert Targets
        if (isset($this->request->targets)) {
            $targets = array();
            foreach ($this->request->targets as $target) {
                $target['user_id'] = $userId;
                array_push($targets, $target);
            }
            DB::table('user_targets')->insertTs($targets);
        }

        // Insert Performances
        if (isset($this->request->performances)) {
            $performances = array();
            foreach ($this->request->performances as $performance) {
                $performance['user_id'] = $userId;
                array_push($performances, $performance);
            }
            DB::table('user_performances')->insertTs($performances);
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

        return $this->response(200, 'Pegawai berhasil ditambah.');
    }

    /**
     * Get Detail Employee by ID
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
        $disciplinaries = $this->disciplineRepository->getDetail($this->request->id);
        $leaves = $this->leaveRepository->getDetail($this->request->id);
        $assessments = $this->assessmentsRepository->getDetail($this->request->id, 1);
        $competencies = $this->assessmentsRepository->getDetail($this->request->id, 2);
        $talents = $this->assessmentsRepository->getDetail($this->request->id, 3);
        $notes = $this->notesRepository->getDetail($this->request->id);

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
        $employee->$competencies = $competencies;
        $employee->$talents = $talents;

        return $this->response(200, 'success', $employee);
    }

    /**
     * Update Employee by ID
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
