<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmploymentType\CreateEmploymentTypeRequest;
use App\Http\Requests\EmploymentType\UpdateEmploymentTypeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Master Data
 * @subgroupDescription These endpoints allow you to perform CRUD operations on employment type data, enabling the retrieval, creation, updating and deleting of employment type records as needed.
 */
class EmploymentTypeController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Employment Types
     *
     * Retrieve the employment type of master data.
     * @subgroup Employment Type
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam status boolean Refers to the status display of Employment Type. Defaults is null. Example: null
     * @queryParam type integer Refers to the type of Employment Type. 1=ASN, 2=NON-ASN or 3=OUTSOURCE Defaults is null. Example: null
     * @queryParam keyword string The keyword search field for the name of employment type. Example: ORGANIK
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"name": "TNI/POLRI","status": 1,"type": 1}],"pagination": {"total": 20,"count": 1,"per_page": 1,"current_page": 1,"total_pages": 20,"links": {"first_page": "http://localhost/api/employment-types?page=1","last_page": "http://localhost/api/employment-types?page=20","next_page": "http://localhost/api/employment-types?page=2","prev_page": null}}}
     */
    public function index()
    {
        $messages = [
            'page.numeric' => 'Page harus berupa angka.',
            'page.min' => 'Page minimal harus 1 atau lebih.',
            'limit.numeric' => 'Limit harus berupa angka.',
            'limit.min' => 'Limit minimal harus 1 atau lebih.',
            'status.boolean' => 'Status harus berupa boolean. ',
            'type.in' => 'Type harus diantara 1, 2 atau 3. ',
        ];

        $validatedData = $this->request->validate([
            'page' => 'nullable|numeric|min:1',
            'limit' => 'nullable|numeric|min:1',
            'status' => 'nullable|boolean',
            'type' => 'nullable|in:1,2,3',
        ], $messages);

        $this->request->limit = ($this->request->limit) ? $this->request->limit : 10;

        $employmentTypes = DB::table('employment_types');
        $employmentTypes->select('id', 'name', 'status', 'type');
        $employmentTypes->where('name', 'like', '%' . $this->request->keyword . '%');
        if ($this->request->status) {
            $employmentTypes->where('status', $this->request->status);
        }
        if ($this->request->type) {
            $employmentTypes->where('type', $this->request->type);
        }
        $employmentTypes = $employmentTypes->paginate($this->request->limit);

        if ($employmentTypes->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $employmentTypes);
        }

        return $this->paginateResponse(200, 'success', $employmentTypes);
    }

    /**
     * Create a New Employment Type
     *
     * Add a new employment type entry for an master data of employment type.
     * @subgroup Employment Type
     * @authenticated
     * @response 200 {"code": 200,"message": "Jenis pegawai berhasil ditambah.","data": null}
     */
    public function create(CreateEmploymentTypeRequest $request)
    {
        DB::table('employment_types')->insertTs($this->posted);
        return $this->response(200, 'Jenis pegawai berhasil ditambah.');
    }

    /**
     * Get Detail Employment Type by ID
     *
     * Retrieve employment type for a specific an master data of employment type.
     * @subgroup Employment Type
     * @authenticated
     * @urlParam id Refers to the ID of Employment Type. Example: 1
     * @response 404 {"code": 404,"message": "Jenis pegawai tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 3,"name": "Sekretariat Negara Wakil Presiden"}}
     */
    public function show()
    {
        $employmentType = DB::table('employment_types');
        $employmentType->select('id', 'name', 'status', 'type');
        $employmentType->where('id', $this->request->id);
        $employmentType = $employmentType->first();

        if (!$employmentType) {
            return $this->response(404, 'Jenis pegawai tidak ditemukan.');
        }

        return $this->response(200, 'success', $employmentType);
    }

    /**
     * Update Employment Type by ID
     *
     * Update an existing employment type entry.
     * @subgroup Employment Type
     * @authenticated
     * @urlParam id Refers to the ID of Employment Type. Example: 1
     * @response 404 {"code": 404,"message": "Jenis pegawai tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Jenis pegawai berhasil diupdate.","data": null}
     */
    public function update(UpdateEmploymentTypeRequest $request)
    {
        $employmentType = DB::table('employment_types');
        $employmentType->where('id', $this->request->id);
        $employmentType->select('id');
        $employmentType = $employmentType->first();

        if (!$employmentType) {
            return $this->response(404, 'Jenis pegawai tidak ditemukan.');
        }

        $employmentType = DB::table('employment_types');
        $employmentType->where('id', $this->request->id);
        $employmentType = $employmentType->updateTs($this->posted);

        return $this->response(200, 'Jenis pegawai berhasil diupdate.');
    }

    /**
     * Delete Employment Type by ID
     *
     * Delete a specific employment type entry.
     * @subgroup Employment Type
     * @authenticated
     * @urlParam id Refers to the ID of Employment Type. Example: 1
     * @response 404 {"code": 404,"message": "Jenis pegawai tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Jenis pegawai berhasil dihapus.","data": null}
     */
    public function delete()
    {
        $employmentType = DB::table('employment_types');
        $employmentType->where('id', $this->request->id);
        $employmentType->select('id');
        $employmentType = $employmentType->first();

        if (!$employmentType) {
            return $this->response(404, 'Jenis pegawai tidak ditemukan.');
        }

        $employmentType = DB::table('employment_types');
        $employmentType->where('id', $this->request->id);
        $employmentType = $employmentType->delete();

        return $this->response(200, 'Jenis pegawai berhasil dihapus.');
    }
}
