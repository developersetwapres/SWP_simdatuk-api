<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisciplinaryHistory\CreateDisciplinaryHistoryRequest;
use App\Http\Requests\DisciplinaryHistory\UpdateDisciplinaryHistoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group History
 * @subgroupDescription These endpoints allow you to perform CRUD operations on disciplinary data, enabling the retrieval, creation, and updating of disciplinary records as needed.
 */
class DisciplinaryHistoryController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Disciplinaries
     *
     * Retrieve the history of employee disciplinaries.
     * @subgroup Disciplinary
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam name string The keyword search field for the name. Example: Hukuman Disiplin
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"created_at": "2024-05-14 08:51:39","name": "Hukuman Disiplin Desember 2024","period_month": 3,"period_year": "2020","total": 1}],"pagination": {"total": 1,"count": 1,"per_page": 10,"current_page": 1,"total_pages": 1,"links": {"first_page": "http://localhost/api/disciplinaries?page=1","last_page": "http://localhost/api/disciplinaries?page=1","next_page": null,"prev_page": null}}}
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

        $disciplinaries = DB::table('disciplinaries as d');
        $disciplinaries->leftjoin('user_disciplinaries as ud', 'd.id', '=', 'ud.disciplinary_id');
        $disciplinaries->select('d.id', 'd.created_at', 'd.name', 'd.period_month', 'd.period_year', DB::raw("COUNT(ud.id) AS total"));
        $disciplinaries->where('d.name', 'like', '%' . $this->request->name . '%');
        $disciplinaries->groupby('d.id');
        $disciplinaries = $disciplinaries->paginate($this->request->limit);
        if ($disciplinaries->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $disciplinaries);
        }
        return $this->paginateResponse(200, 'success', $disciplinaries);
    }

    /**
     * Create a New Disciplinary
     *
     * Add a new disciplinary entry for an employee.
     * @subgroup Disciplinary
     * @authenticated
     * @response 200 {"code": 200,"message": "Hukuman disiplin berhasil ditambah.","data": null}
     */
    public function create(CreateDisciplinaryHistoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $disciplinaryId = DB::table('disciplinaries')->insertGetIdTs($this->request->except('users'));

            // Insert Users
            if (isset($this->request->users)) {
                $users = array();
                foreach ($this->request->users as $user) {
                    $user['disciplinary_id'] = $disciplinaryId;
                    array_push($users, $user);
                }
                DB::table('user_disciplinaries')->insertTs($users);
            }
            DB::commit();
            return $this->response(200, 'Hukuman disiplin berhasil ditambah.');
        } catch (\Throwable $th) {
            \Log::warning($th);
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Get Detail Disciplinary by ID
     *
     * Retrieve disciplinary history for a specific employee.
     * @subgroup Disciplinary
     * @authenticated
     * @urlParam id Refers to the ID of Disciplinary. Example: 1
     * @response 404 {"code": 404,"message": "Hukuman disiplin tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 1,"period_month": 4,"period_year": "2020","name": "Hukuman Disiplin Desember 2024","created_at": "2024-05-14 08:51:39","users": [{"id": 2,"user_id": 1,"name": "administrator","employee_id_number": "0000000000000","grade": "Penata (III/c)","position": "Kepala Subbagian Administrasi","disciplinary_type_id": 1,"disciplinary_type_name": "Teguran Lisan","disciplinary_type_description": "Hukuman Disiplin Tingkat Ringan 1","performance_allowance_deduction": 0.25,"performance_allowance_duration": 2,"decree_number": "Nomor 112 Tahun 2023","date_of_decree": "2023-10-22","start_date": "2023-10-22","end_date": "2024-10-22","authorizing_officer": "Deputi Bidang Administrasi","name_of_authorizing_officer": "Sapto Harjono Wahjoe Sedjati, S.Sos., M.A.","description": "Tidak masuk ke kantor selama 10 hari","created_at": "2024-05-14 11:05:51"}]}}
     */
    public function show()
    {
        $disciplinary = DB::table('disciplinaries');
        $disciplinary->where('id', $this->request->id);
        $disciplinary->select('id', 'period_month', 'period_year', 'name', 'created_at');
        $disciplinary = $disciplinary->first();

        if (!$disciplinary) {
            return $this->response(404, 'Hukuman disiplin tidak ditemukan.');
        }

        $users = DB::table('user_disciplinaries as ud');
        $users->join('users as u', 'u.id', '=', 'ud.user_id');
        $users->join('disciplinary_types as dt', 'ud.disciplinary_id', '=', 'dt.id');
        $users->where('ud.disciplinary_id', $disciplinary->id);
        $users->select('ud.id', 'ud.user_id', 'u.name', 'u.employee_id_number', 'ud.grade', 'ud.position', 'dt.id as disciplinary_type_id', 'dt.name as disciplinary_type_name', 'dt.description as disciplinary_type_description', 'dt.performance_allowance_deduction', 'dt.performance_allowance_duration', 'ud.decree_number', 'ud.date_of_decree', 'ud.start_date', 'ud.end_date', 'ud.authorizing_officer', 'ud.name_of_authorizing_officer', 'ud.description', 'ud.created_at');
        $users = $users->get();

        $disciplinary->users = $users;

        return $this->response(200, 'success', $disciplinary);
    }

    /**
     * Update Disciplinary by ID
     *
     * Update an existing disciplinary entry.
     * @subgroup Disciplinary
     * @authenticated
     * @urlParam id Refers to the ID of Disciplinary. Example: 1
     * @response 404 {"code": 404,"message": "Hukuman disiplin tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Hukuman disiplin berhasil diupdate.","data": null}
     */
    public function update(UpdateDisciplinaryHistoryRequest $request)
    {
        $disciplinary = DB::table('disciplinaries');
        $disciplinary->where('id', $this->request->id);
        $disciplinary->select('id');
        $disciplinary = $disciplinary->first();

        if (!$disciplinary) {
            return $this->response(404, 'Hukuman disiplin tidak ditemukan.');
        }

        $disciplinary = DB::table('disciplinaries');
        $disciplinary->where('id', $this->request->id);
        $disciplinary = $disciplinary->updateTs($this->request->except('users'));

        $users = array();

        if (isset($this->request->users)) {

            // Delete user disciplinary
            DB::table('user_disciplinaries')->where('disciplinary_id', $this->request->id)->delete();

            foreach ($this->request->users as $user) {
                $user['disciplinary_id'] = $this->request->id;
                // Collect user to bulk insert
                array_push($users, $user);
            }
            DB::table('user_disciplinaries')->insertTs($users);
        }
        return $this->response(200, 'Hukuman disiplin berhasil diupdate.');
    }
}
