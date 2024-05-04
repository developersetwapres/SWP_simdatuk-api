<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Master Data
 * @subgroupDescription These endpoints allow you to perform CRUD operations on decree type data, enabling the retrieval, creation, updating and deleting of decree type records as needed.
 */
class DecreeTypeController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Decree Types
     *
     * Retrieve the decree type of master data.
     * @subgroup Decree Type
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
}
