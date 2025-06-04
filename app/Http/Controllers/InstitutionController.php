<?php

namespace App\Http\Controllers;

use App\Http\Requests\Institution\CreateInstitutionRequest;
use App\Http\Requests\Institution\UpdateInstitutionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Master Data
 * @subgroupDescription These endpoints allow you to perform CRUD operations on institution data, enabling the retrieval, creation, updating and deleting of institution records as needed.
 */
class InstitutionController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Institutions
     *
     * Retrieve the institution of master data.
     * @subgroup Institution
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name of institution. Example: kementerian
     * @response 200 {"code": 200,"message": "success","data": [{"id": 2,"name": "Sekretariat Negara Wakil Presiden"}],"pagination": {"total": 1,"count": 1,"per_page": 10,"current_page": 1,"total_pages": 1,"links": {"first_page": "http://localhost/api/institutions?page=1","last_page": "http://localhost/api/institutions?page=1","next_page": null,"prev_page": null}}}
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

        $institutions = DB::table('institutions');
        $institutions->select('id', 'name');
        $institutions->where('name', 'like', '%' . $this->request->search . '%');
        $institutions->orderBy('id', 'asc');

        if (is_null($this->request->limit)) {
            $institutions = $institutions->get();
            $message = (count($institutions) < 1) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            return $this->response(200, $message, $institutions);
        } else {
            $institutions = $institutions->paginate($this->request->limit);
            $message = ($institutions->isEmpty()) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            return $this->paginateResponse(200, $message, $institutions);
        }
    }

    /**
     * Create a New Institution
     *
     * Add a new institution entry for an master data of institution.
     * @subgroup Institution
     * @authenticated
     * @response 200 {"code": 200,"message": "Institusi berhasil ditambah.","data": null}
     */
    public function create(CreateInstitutionRequest $request)
    {
        DB::table('institutions')->insertTs($this->posted);
        return $this->response(200, 'Institusi berhasil ditambah.');
    }

    /**
     * Get Detail Institution by ID
     *
     * Retrieve institution for a specific an master data of institution.
     * @subgroup Institution
     * @authenticated
     * @urlParam id Refers to the ID of Institution. Example: 1
     * @response 404 {"code": 404,"message": "Institusi tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 3,"name": "Sekretariat Negara Wakil Presiden"}}
     */
    public function show()
    {
        $institution = DB::table('institutions');
        $institution->select('id', 'name');
        $institution->where('id', $this->request->id);
        $institution = $institution->first();

        if (!$institution) {
            return $this->response(404, 'Institusi tidak ditemukan.');
        }

        return $this->response(200, 'success', $institution);
    }

    /**
     * Update Institution by ID
     *
     * Update an existing institution entry.
     * @subgroup Institution
     * @authenticated
     * @urlParam id Refers to the ID of Institution. Example: 1
     * @response 404 {"code": 404,"message": "Institusi tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Institusi berhasil diupdate.","data": null}
     */
    public function update(UpdateInstitutionRequest $request)
    {
        $institution = DB::table('institutions');
        $institution->where('id', $this->request->id);
        $institution->select('id');
        $institution = $institution->first();

        if (!$institution) {
            return $this->response(404, 'Institusi tidak ditemukan.');
        }

        $institution = DB::table('institutions');
        $institution->where('id', $this->request->id);
        $institution = $institution->updateTs($this->posted);

        return $this->response(200, 'Institusi berhasil diupdate.');
    }

    /**
     * Delete Institution by ID
     *
     * Delete a specific institution entry.
     * @subgroup Institution
     * @authenticated
     * @urlParam id Refers to the ID of Institution. Example: 1
     * @response 404 {"code": 404,"message": "Institusi tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Institusi berhasil dihapus.","data": null}
     */
    public function delete()
    {
        $institution = DB::table('institutions');
        $institution->where('id', $this->request->id);
        $institution->select('id');
        $institution = $institution->first();

        if (!$institution) {
            return $this->response(404, 'Institusi tidak ditemukan.');
        }

        $institution = DB::table('institutions');
        $institution->where('id', $this->request->id);
        $institution = $institution->delete();

        return $this->response(200, 'Institusi berhasil dihapus.');
    }
}
