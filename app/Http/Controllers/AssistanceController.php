<?php

namespace App\Http\Controllers;

use App\Http\Requests\Assistance\CreateAssistanceRequest;
use App\Http\Requests\Assistance\UpdateAssistanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Master Data
 *
 * APIs for assistance
 */
class AssistanceController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Asistances
     * @group Master Data
     * @subgroup Assistance
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam keyword string The keyword search field for the name of assistance. Example: staff khusus
     * @response 200 {"code": 200,"message": "success","data": [{"id": 3,"name": "Anggota Tim Ahli"}],"pagination": {"total": 15,"count": 1,"per_page": 1,"current_page": 1,"total_pages": 15,"links": {"first_page": "http://localhost/api/assistances?page=1","last_page": "http://localhost/api/assistances?page=15","next_page": "http://localhost/api/assistances?page=2","prev_page": null}}}
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

        $assistances = DB::table('assistances');
        $assistances->select('id', 'name');
        $assistances->where('name', 'like', '%' . $this->request->keyword . '%');
        $assistances = $assistances->paginate($this->request->limit);

        if ($assistances->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $assistances);
        }

        return $this->paginateResponse(200, 'success', $assistances);
    }

    /**
     * Create a New Assistance
     * @group Master Data
     * @subgroup Assistance
     * @authenticated
     * @response 200 {"code": 200,"message": "Jenis perbantuan berhasil ditambah.","data": null}
     */
    public function create(CreateAssistanceRequest $request)
    {
        DB::table('assistances')->insertTs($this->posted);
        return $this->response(200, 'Jenis perbantuan berhasil ditambah.');
    }

    /**
     * Get Detail Assistance by ID
     * @group Master Data
     * @subgroup Assistance
     * @authenticated
     * @urlParam id Refers to the ID of Institution. Example: 1
     * @response 404 {"code": 404,"message": "Jenis perbantuan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 3,"name": "Sekretariat Negara Wakil Presiden"}}
     */
    public function show()
    {
        $assistance = DB::table('assistances');
        $assistance->select('id', 'name');
        $assistance->where('id', $this->request->id);
        $assistance = $assistance->first();

        if (!$assistance) {
            return $this->response(404, 'Jenis perbantuan tidak ditemukan.');
        }

        return $this->response(200, 'success', $assistance);
    }

    /**
     * Update Assistance by ID
     * @group Master Data
     * @subgroup Assistance
     * @authenticated
     * @urlParam id Refers to the ID of Institution. Example: 1
     * @response 404 {"code": 404,"message": "Jenis perbantuan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Jenis perbantuan berhasil diupdate.","data": null}
     */
    public function update(UpdateAssistanceRequest $request)
    {
        $assistance = DB::table('assistances');
        $assistance->where('id', $this->request->id);
        $assistance->select('id');
        $assistance = $assistance->first();

        if (!$assistance) {
            return $this->response(404, 'Jenis perbantuan tidak ditemukan.');
        }

        $assistance = DB::table('assistances');
        $assistance->where('id', $this->request->id);
        $assistance = $assistance->updateTs($this->posted);

        return $this->response(200, 'Jenis perbantuan berhasil diupdate.');
    }

    /**
     * Delete Assistance by ID
     * @group Master Data
     * @subgroup Assistance
     * @authenticated
     * @urlParam id Refers to the ID of Assistance. Example: 1
     * @response 404 {"code": 404,"message": "Jenis perbantuan tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Jenis perbantuan berhasil dihapus.","data": null}
     */
    public function delete()
    {
        $assistance = DB::table('assistances');
        $assistance->where('id', $this->request->id);
        $assistance->select('id');
        $assistance = $assistance->first();

        if (!$assistance) {
            return $this->response(404, 'Jenis perbantuan tidak ditemukan.');
        }

        $assistance = DB::table('assistances');
        $assistance->where('id', $this->request->id);
        $assistance = $assistance->delete();

        return $this->response(200, 'Jenis perbantuan berhasil dihapus.');
    }
}
