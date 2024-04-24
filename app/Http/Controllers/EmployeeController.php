<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\CreateEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
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
    public function __construct(Request $request)
    {
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
            'leaves'
        );
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
        // try {
        //     DB::beginTransaction();
        //     DB::commit();
        // } catch (\Throwable $th) {
        //     DB::rollback();
        // }
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
            $fileExtension = '.' . $this->request->file('photo_profile')->getClientOriginalExtension();
            $fileName = Str::random(32) . $fileExtension;
            Storage::disk('public')->putFileAs('photo_profile/', $this->request->file('photo_profile'), $fileName);
            $this->posted['photo_profile'] = 'photo_profile/' . $fileName;
        }

        if ($this->request->hasFile('employee_id_card')) {
            $fileExtension = '.' . $this->request->file('employee_id_card')->getClientOriginalExtension();
            $fileName = Str::random(32) . $fileExtension;
            Storage::disk('public')->putFileAs('employee_id_card/', $this->request->file('employee_id_card'), $fileName);
            $this->posted['employee_id_card'] = 'employee_id_card/' . $fileName;
        }

        $userId = DB::table('users')->insertGetIdTs($this->posted);

        // Insert Educations
        if (isset($this->request->educations)) {
            $educations = array();
            foreach ($this->request->educations as $education) {
                $education['user_id'] = $userId;
                array_push($educations, $education);
            }
            DB::table('user_educations')->insertTs($educations);
        }

        // Insert Positions
        if (isset($this->request->positions)) {
            $positions = array();
            foreach ($this->request->positions as $position) {
                $position['user_id'] = $userId;
                array_push($positions, $position);
            }
            DB::table('user_positions')->insertTs($positions);
        }

        // Insert Grades
        if (isset($this->request->grades)) {
            $grades = array();
            foreach ($this->request->grades as $grade) {
                $grade['user_id'] = $userId;
                array_push($grades, $grade);
            }
            DB::table('user_grades')->insertTs($grades);
        }

        // Insert Salaries
        if (isset($this->request->salaries)) {
            $salaries = array();
            foreach ($this->request->salaries as $salary) {
                $salary['user_id'] = $userId;
                array_push($salaries, $salary);
            }
            DB::table('user_sallaries')->insertTs($sallaries);
        }

        // Insert Trainings
        if (isset($this->request->trainings)) {
            $trainings = array();
            foreach ($this->request->trainings as $training) {
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
            foreach ($this->request->targets as $recognition) {
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
                $leave['user_id'] = $userId;
                array_push($leaves, $leave);
            }
            DB::table('user_leaves')->insertTs($leaves);
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
        $user = DB::table('users');
        $user->where('id', $this->request->id);
        $user->select('type');
        $user = $user->first();
        if (!$user) {
            return $this->response(404, 'Pegawai tidak ditemukan.');
        }
        return $this->response(200, 'success', $user);
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
