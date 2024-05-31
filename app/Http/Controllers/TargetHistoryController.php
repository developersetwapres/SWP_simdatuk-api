<?php

namespace App\Http\Controllers;

use App\Http\Requests\TargetHistory\CreateTargetHistoryRequest;
use App\Http\Requests\TargetHistory\UpdateTargetHistoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @group History
 * @subgroupDescription These endpoints allow you to perform CRUD operations on Target data, enabling the retrieval, creation, and updating of Target records as needed.
 */
class TargetHistoryController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Target
     *
     * Retrieve the history of employee Targets.
     * @subgroup Target
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name. Example: SKP Desember 2023
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"created_at": "2024-05-03 05:29:30","name": "SKP Desember 2023","period_month": 3,"period_year": "2020","start_date": "2020-10-22", "appraisal_period": "Q1" ,"total": 2}],"pagination": {"total": 4,"count": 4,"per_page": 10,"current_page": 1,"total_pages": 1,"links": {"first_page": "http://localhost/api/target?page=1","last_page": "http://localhost/api/target?page=1","next_page": null,"prev_page": null}}}
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

        $targets = DB::table('targets as t');
        $targets->leftjoin('user_targets as ut', 't.id', '=', 'ut.target_id');
        $targets->select('t.id', 't.created_at', 't.name', 't.period_month', 't.period_year', 't.appraisal_period', DB::raw("COUNT(ut.id) AS total"));
        $targets->where('t.name', 'like', '%' . $this->request->search . '%');
        $targets->groupby('t.id');
        $targets = $targets->paginate($this->request->limit);
        if ($targets->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $targets);
        }
        return $this->paginateResponse(200, 'success', $targets);
    }

    /**
     * Create a New Target
     *
     * Add a new Target entry for employees.
     * @subgroup Target
     * @authenticated
     * @response 200 {"code": 200,"message": "SKP berhasil ditambah.","data": null}
     */
    public function create(CreateTargetHistoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $targetId = DB::table('targets')->insertGetIdTs($this->request->except('users'));
            //insert Users
            if (isset($this->request->users)) {
                $users = array();
                foreach ($this->request->users as $user) {
                    $user['target_id'] = $targetId;
                    array_push($users, $user);
                }
                DB::table('user_targets')->insertTs($users);
            }

            DB::commit();
            return $this->response(200, 'SKP berhasil ditambah.');
        } catch (\Throwable $th) {
            \Log::warning($th);
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Get Detail Target by ID
     *
     * Retrieve target history for a specific employee.
     * @subgroup Target
     * @authenticated
     * @urlParam id Refers to the ID of Target. Example: 1
     * @response 404 {"code": 404,"message": "SKP tidak ditemukan.","data": null}
     * @response 200 {"code":200,"message":"success","data":{"id":24,"period_month":1,"period_year":"2024","name":"\"test 2\"","appraisal_period":"Q1","year":"2024","users":[{"id":47,"created_at":"2024-05-07 07:03:38","employee_performance_predicate":1,"organizational_performance_achievement":1,"work_behavior_rating":1}]}}
     */
    public function show()
    {
        $target = DB::table('targets');
        $target->where('id', $this->request->id);
        $target->select('id', 'period_month', 'period_year', 'name', 'appraisal_period', 'year');
        $target = $target->first();

        if (!$target) {
            return $this->response(404, 'SKP tidak ditemukan.');
        }

        $users = DB::table('user_targets as ut');
        $users->join('users as u', 'u.id', '=', 'ut.user_id');
        $users->where('target_id', $target->id);
        $users->select('ut.id', 'ut.employee_performance_predicate', 'ut.organizational_performance_achievement', 'ut.work_behavior_rating', 'u.name', 'u.employee_id_number', 'ut.created_at');
        $users = $users->get();

        $target->users = $users;

        return $this->response(200, 'success', $target);
    }

    /**
     * Update Target by ID
     *
     * Update an existing Target entry.
     * @subgroup Target
     * @authenticated
     * @urlParam id Refers to the ID of Target. Example: 1
     * @response 404 {"code": 404,"message": "SKP tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "SKP berhasil diupdate.","data": null}
     */
    public function update(UpdateTargetHistoryRequest $request)
    {
        $target = DB::table('targets');
        $target->where('id', $this->request->id);
        $target->select('id');
        $target = $target->first();

        if (!$target) {
            return $this->response(404, 'SKP tidak ditemukan.');
        }

        $target = DB::table('targets');
        $target->where('id', $this->request->id);
        $target = $target->updateTs($this->request->except('users'));

        $users = array();

        if (isset($this->request->users)) {

            // Get existing data
            $userTargets = DB::table('user_targets');
            $userTargets->where('target_id', $this->request->id);
            $userTargets->select('id');
            $userTargets = $userTargets->get();

            // Delete data
            $array1 = Arr::pluck($userTargets, 'id');
            $array2 = Arr::pluck($this->request->users, 'id');
            $result = array_diff($array1, $array2);
            DB::table('user_trainings')->whereIn('id', $result)->delete();

            foreach ($this->request->users as $user) {
                if (!is_null($user['id'])) {
                    // Update existing data
                    DB::table('user_targets')->where('id', $user['id'])->updateTs($user);
                } else {
                    // Insert new item
                    $user['target_id'] = $this->request->id;
                    array_push($users, $user);
                }
            }
            if (count($users) > 0) {
                DB::table('user_targets')->insertTs($users);
            }
        }
        return $this->response(200, 'SKP berhasil diupdate.');
    }
}
