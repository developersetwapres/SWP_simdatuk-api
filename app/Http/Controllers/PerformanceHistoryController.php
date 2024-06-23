<?php

namespace App\Http\Controllers;

use App\Http\Requests\Performance;
use App\Http\Requests\PerformanceHistory\CreatePerformanceHistoryRequest;
use App\Http\Requests\PerformanceHistory\UpdatePerformanceHistoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @group History
 * @subgroupDescription These endpoints allow you to perform CRUD operations on performance history data, enabling the retrieval, creation, and updating of performance history records as needed.
 */
class PerformanceHistoryController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Performance Histories
     *
     * Retrieve the performance histories.
     * @subgroup Performance
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name. Example: PPK Mei 2024
     * @response 200 {"code": 200,"message": "success","data": [{"id": 56,"created_at": "2024-06-20 09:26:03","name": "01 Jan 2017 s.d 31 Des 2017","period_month": 5,"period_year": "2023","performance_period": "01 Jan 2017 s.d 31 Des 2017","total": 221}],"pagination": {"total": 55,"count": 10,"per_page": 10,"current_page": 1,"total_pages": 6,"links": {"first_page": "http://localhost/api/performance-histories?page=1","last_page": "http://localhost/api/performance-histories?page=6","next_page": "http://localhost/api/performance-histories?page=2","prev_page": null}}}
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

        $performanceHistories = DB::table('performance_histories as ph');
        $performanceHistories->leftjoin('performance_history_users as phu', 'ph.id', '=', 'phu.performance_history_id');
        $performanceHistories->select('ph.id', 'ph.created_at', 'ph.name', 'ph.period_month', 'ph.period_year', 'ph.performance_period', DB::raw("COUNT(phu.id) AS total"));
        $performanceHistories->where('ph.name', 'like', '%' . $this->request->search . '%');
        $performanceHistories->orderBy('ph.updated_at', 'desc');
        $performanceHistories->orderBy('ph.created_at', 'desc');
        $performanceHistories->groupby('ph.id');
        $performanceHistories = $performanceHistories->paginate($this->request->limit);
        if ($performanceHistories->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $performanceHistories);
        }
        return $this->paginateResponse(200, 'success', $performanceHistories);
    }

    /**
     * Create a New Performance History
     *
     * Add a new performance history entry.
     * @subgroup Performance
     * @authenticated
     * @response 200 {"code": 200,"message": "PPK berhasil ditambah.","data": null}
     */
    public function create(CreatePerformanceHistoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $performanceHistoryId = DB::table('performance_histories')->insertGetIdTs($this->request->except('users'));
            if (isset($this->request->users)) {
                $users = array();
                foreach ($this->request->users as $user) {
                    $user['performance_history_id'] = $performanceHistoryId;
                    array_push($users, $user);
                }
                DB::table('performance_history_users')->insertTs($users);
            }
            DB::commit();
            return $this->response(200, 'PPK berhasil ditambahkan.');
        } catch (\Throwable $th) {
            \Log::warning($th);
            DB::rollBack();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Get Detail Performance History by ID
     *
     * Retrieve performance history for specific ID.
     * @subgroup Performance
     * @authenticated
     * @urlParam id Refers to the ID of Performance History. Example: 1
     * @response 404 {"code": 404,"message": "PPK tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 80,"name": "01 Jan 2017 s.d 28 Feb 2017","performance_period": "01 Jan 2017 s.d 28 Feb 2017","period_year": "2023","period_month": 5,"users": [{"id": 390,"user_id": 9470,"name": "Aldi Yarman","employee_id_number": "197804172005011002","work_performance_score": 86.8,"description": 4,"created_at": "2024-06-20 09:26:03"}]}}
     */
    public function show()
    {
        $performanceHistory = DB::table('performance_histories');
        $performanceHistory->where('id', $this->request->id);
        $performanceHistory->select('id', 'name', 'performance_period', 'period_year', 'period_month');
        $performanceHistory = $performanceHistory->first();
        if (!$performanceHistory) {
            return $this->response(404, 'PPK tidak ditemukan.');
        }

        $users = DB::table('performance_history_users as phu');
        $users->join('users as u', 'u.id', '=', 'phu.user_id');
        $users->where('performance_history_id', $performanceHistory->id);
        $users->select(
            'phu.id',
            'phu.user_id',
            'u.name',
            'u.employee_id_number',
            'phu.work_performance_score',
            'phu.description',
            'phu.created_at'
        );
        $users = $users->get();
        $performanceHistory->users = $users;
        return $this->response(200, 'success', $performanceHistory);
    }

    /**
     * Update Performance History by ID
     *
     * Update an existing performance history entry.
     * @subgroup Performance
     * @authenticated
     * @urlParam id Refers to the ID of Performance History. Example: 1
     * @response 404 {"code": 404,"message": "PPK tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "PPK berhasil diupdate.","data": null}
     */
    public function update(UpdatePerformanceHistoryRequest $request)
    {
        $performanceHistory = DB::table('performance_histories');
        $performanceHistory->where('id', $this->request->id);
        $performanceHistory->select('id');
        $performanceHistory = $performanceHistory->first();

        if (!$performanceHistory) {
            return $this->response(404, 'PPK tidak ditemukan.');
        }

        $performanceHistory = DB::table('performance_histories');
        $performanceHistory->where('id', $this->request->id);
        $performanceHistory = $performanceHistory->updateTs($this->request->except('users'));

        $users = array();

        if (isset($this->request->users)) {

            // Get existing data
            $performanceHistoryUsers = DB::table('performance_history_users');
            $performanceHistoryUsers->where('performance_history_id', $this->request->id);
            $performanceHistoryUsers->select('id');
            $performanceHistoryUsers = $performanceHistoryUsers->get();

            // Delete data
            $array1 = Arr::pluck($performanceHistoryUsers, 'id');
            $array2 = Arr::pluck($this->request->users, 'id');
            $result = array_diff($array1, $array2);
            DB::table('performance_history_users')->whereIn('id', $result)->delete();

            foreach ($this->request->users as $user) {
                if (!is_null($user['id'])) {
                    // Update existing data
                    DB::table('performance_history_users')->where('id', $user['id'])->updateTs($user);
                } else {
                    // Insert new item
                    $user['performance_history_id'] = $this->request->id;
                    array_push($users, $user);
                }
            }
            if (count($users) > 0) {
                DB::table('performance_history_users')->insertTs($users);
            }
        }
        return $this->response(200, 'PPK berhasil diupdate.');
    }
}
