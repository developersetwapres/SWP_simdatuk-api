<?php

namespace App\Http\Controllers;

use App\Http\Requests\TargetHistory\CreateTargetHistoryRequest;
use App\Http\Requests\TargetHistory\UpdateTargetHistoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @group History
 * @subgroupDescription These endpoints allow you to perform CRUD operations on target history data, enabling the retrieval, creation, and updating of position history records as needed.
 */
class TargetHistoryController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Target Histories
     *
     * Retrieve the target histories.
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

        $targetHistories = DB::table('target_histories as th');
        $targetHistories->leftjoin('target_history_users as thu', 'th.id', '=', 'thu.target_history_id');
        $targetHistories->select('th.id', 'th.created_at', 'th.name', 'th.period_month', 'th.period_year', 'th.appraisal_period', DB::raw("COUNT(thu.id) AS total"));
        $targetHistories->where('th.name', 'like', '%' . $this->request->search . '%');
        $targetHistories->orderBy('th.updated_at', 'desc');
        $targetHistories->orderBy('th.created_at', 'desc');
        $targetHistories->groupby('th.id');
        $targetHistories = $targetHistories->paginate($this->request->limit);
        if ($targetHistories->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $targetHistories);
        }
        return $this->paginateResponse(200, 'success', $targetHistories);
    }

    /**
     * Create a New Target History
     *
     * Add a new target history entry.
     * @subgroup Target
     * @authenticated
     * @response 200 {"code": 200,"message": "SKP berhasil ditambah.","data": null}
     */
    public function create(CreateTargetHistoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $targetHistoryId = DB::table('target_histories')->insertGetIdTs($this->request->except('users'));
            //insert Users
            if (isset($this->request->users)) {
                $users = array();
                foreach ($this->request->users as $user) {
                    $user['target_history_id'] = $targetHistoryId;
                    array_push($users, $user);
                }
                DB::table('target_history_users')->insertTs($users);
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
     * Get Detail Target History by ID
     *
     * Retrieve target history for specific ID.
     * @subgroup Target
     * @authenticated
     * @urlParam id Refers to the ID of Target History. Example: 1
     * @response 404 {"code": 404,"message": "SKP tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 4,"period_month": 3,"period_year": "2020","name": "PPK December 2020","appraisal_period": "Q1","year": "2020","users": [{"id": 4,"employee_performance_predicate": 1,"organizational_performance_achievement": 1,"work_behavior_rating": 1,"name": "Stanislaus Widjanarto","employee_id_number": "020002268","created_at": "2024-06-21 10:08:34"}]}}
     */
    public function show()
    {
        $targetHistory = DB::table('target_histories');
        $targetHistory->where('id', $this->request->id);
        $targetHistory->select('id', 'period_month', 'period_year', 'name', 'appraisal_period', 'year');
        $targetHistory = $targetHistory->first();

        if (!$targetHistory) {
            return $this->response(404, 'SKP tidak ditemukan.');
        }

        $users = DB::table('target_history_users as thu');
        $users->join('users as u', 'u.id', '=', 'thu.user_id');
        $users->where('target_history_id', $targetHistory->id);
        $users->select('thu.id', 'thu.employee_performance_predicate', 'thu.organizational_performance_achievement', 'thu.work_behavior_rating', 'u.name', 'u.employee_id_number', 'thu.created_at');
        $users = $users->get();

        $targetHistory->users = $users;

        return $this->response(200, 'success', $targetHistory);
    }

    /**
     * Update Target History by ID
     *
     * Update an existing target history entry.
     * @subgroup Target
     * @authenticated
     * @urlParam id Refers to the ID of Target History. Example: 1
     * @response 404 {"code": 404,"message": "SKP tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "SKP berhasil diupdate.","data": null}
     */
    public function update(UpdateTargetHistoryRequest $request)
    {
        $targetHistory = DB::table('target_histories');
        $targetHistory->where('id', $this->request->id);
        $targetHistory->select('id');
        $targetHistory = $targetHistory->first();

        if (!$targetHistory) {
            return $this->response(404, 'SKP tidak ditemukan.');
        }

        $targetHistory = DB::table('target_histories');
        $targetHistory->where('id', $this->request->id);
        $targetHistory = $targetHistory->updateTs($this->request->except('users'));

        $users = array();

        if (isset($this->request->users)) {

            // Get existing data
            $targetHistoryUsers = DB::table('target_history_users');
            $targetHistoryUsers->where('target_history_id', $this->request->id);
            $targetHistoryUsers->select('id');
            $targetHistoryUsers = $targetHistoryUsers->get();

            // Delete data
            $array1 = Arr::pluck($targetHistoryUsers, 'id');
            $array2 = Arr::pluck($this->request->users, 'id');
            $result = array_diff($array1, $array2);
            DB::table('target_history_users')->whereIn('id', $result)->delete();

            foreach ($this->request->users as $user) {
                if (!is_null($user['id'])) {
                    // Update existing data
                    DB::table('target_history_users')->where('id', $user['id'])->updateTs($user);
                } else {
                    // Insert new item
                    $user['target_history_id'] = $this->request->id;
                    array_push($users, $user);
                }
            }
            if (count($users) > 0) {
                DB::table('target_history_users')->insertTs($users);
            }
        }
        return $this->response(200, 'SKP berhasil diupdate.');
    }
}
