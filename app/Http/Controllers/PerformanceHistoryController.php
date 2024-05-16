<?php

namespace App\Http\Controllers;

use App\Http\Requests\Performance;
use App\Http\Requests\Performance\CreatePerformanceRequest;
use App\Http\Requests\Performance\UpdatePerformanceRequest;
use App\Repositories\PerformanceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group History
 * @subgroupDescription These endpoints allow you to perform CRUD operations on Performance data, enabling the retrieval, creation, and updating of Performance records as needed.
 */
class PerformanceHistoryController extends Controller
{
    protected $PerformanceRepository;

    public function __construct(
        Request $request,
        PerformanceRepository $PerformanceRepository,
    ) {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
        $this->TargetRepository = $PerformanceRepository;
    }

    /**
     * Get List of Performance
     *
     * Retrieve the history of employee Performances.
     * @subgroup Performance
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam type integer Refers to the types of items to be displayed per page. Example: 1
     * @queryParam name string The keyword search field for the name. Example: PPK Mei 2024
     * @response 200 {"code":200,"message":"success","data":[{"id":1,"created_at":"2024-05-10 04:36:41","name":"PPK Mei 2024","period_month":5,"period_year":"2024","performance_period":"PPK Mei 2024","total":1}],"pagination":{"total":1,"count":1,"per_page":10,"current_page":1,"total_pages":1,"links":{"first_page":"http://localhost:8000/api/performances?page=1","last_page":"http://localhost:8000/api/performances?page=1","next_page":null,"prev_page":null}}}
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

        $performances = DB::table('performances as p');
        $performances->leftjoin('user_performances as up', 'p.id', '=', 'up.performance_id');
        $performances->select('p.id', 'p.created_at', 'p.name', 'p.period_month', 'p.period_year', 'p.performance_period', DB::raw("COUNT(up.id) AS total"));
        $performances->where('p.name', 'like', '%' . $this->request->name . '%');
        $performances->groupby('p.id');
        $performances = $performances->paginate($this->request->limit);
        if ($performances->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $performances);
        }
        return $this->paginateResponse(200, 'success', $performances);
    }

    /**
     * Create a New Performance
     *
     * Add a new Performance entry for employees.
     * @subgroup Performance
     * @authenticated
     * @response 200 {"code": 200,"message": "PPK berhasil ditambah.","data": null}
     */
    public function create(CreatePerformanceRequest $request)
    {
        try {
            DB::beginTransaction();
            $performanceId = DB::table('performances')->insertGetIdTs($this->request->except('users'));

            if (isset($this->request->users)) {
                $users = array();
                foreach ($this->request->users as $user) {
                    $user['performance_id'] = $performanceId;
                    array_push($users, $user);
                }
                DB::table('user_performances')->insertTs($users);
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
     * Get Detail Performance by ID
     *
     * Retrieve performance history for a specific employee.
     * @subgroup Performance
     * @authenticated
     * @urlParam id Refers to the ID of Performance. Example: 1
     * @response 404 {"code": 404,"message": "PPK tidak ditemukan.","data": null}
     * @response 200 {"code":200,"message":"success","data":{"id":2,"name":"PPK November 2025","performance_period":"PPK November 2025","period_year":"2025","period_month":10,"description":"Penilaian Bulanan","users":[{"id":2,"created_at":"2024-05-10 04:42:35","user_id":1,"work_performance_score":80}]}}
     */
    public function show()
    {
        $performance = DB::table('performances');
        $performance->where('id', $this->request->id);
        $performance->select('id', 'name', 'performance_period', 'period_year', 'period_month', 'description');
        $performance = $performance->first();

        if (!$performance) {
            return $this->response(404, 'PPK tidak ditemukan.');
        }

        $users = DB::table('user_performances as up');
        $users->join('users as u', 'u.id', '=', 'up.user_id');
        $users->where('performance_id', $performance->id);
        $users->select('up.id', 'up.created_at', 'up.user_id', 'up.work_performance_score', 'u.name', 'u.employee_id_number');
        $users = $users->get();

        $performance->users = $users;

        return $this->response(200, 'success', $performance);
    }

    /**
     * Update Performance by ID
     *
     * Update an existing Performance entry.
     * @subgroup Performance
     * @authenticated
     * @urlParam id Refers to the ID of Performance. Example: 1
     * @response 404 {"code": 404,"message": "PPK tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "PPK berhasil diupdate.","data": null}
     */
    public function update(UpdatePerformanceRequest $request)
    {
        $performance = DB::table('performances');
        $performance->where('id', $this->request->id);
        $performance->select('id');
        $performance = $performance->first();

        if (!$performance) {
            return $this->response(404, 'PPK tidak ditemukan.');
        }

        $performance = DB::table('performances');
        $performance->where('id', $this->request->id);
        $performance = $performance->updateTs($this->request->except('users'));

        $users = array();

        if (isset($this->request->users)) {

            DB::table('user_performances')->where('performance_id', $this->request->id)->delete();

            foreach ($this->request->users as $user) {
                $user['performance_id'] = $this->request->id;
                // Collect user to bulk insert
                array_push($users, $user);
            }

            DB::table('user_performances')->insertTs($users);
        }
        return $this->response(200, 'PPK berhasil diupdate.');

    }
}
