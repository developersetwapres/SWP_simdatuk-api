<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Master Data
 * @subgroupDescription These endpoints allow you to perform CRUD operations on echelon data, enabling the retrieval, creation, updating and deleting of echelon records as needed.
 */
class EchelonController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Echelons
     *
     * Retrieve the echelon of master data.
     * @subgroup Echelon
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name. Example: Eselon I
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"name": "Eselon I"},{"id": 2,"name": "Eselon II"}],"pagination": {"total": 4,"count": 4,"per_page": 10,"current_page": 1,"total_pages": 1,"links": {"first_page": "http://localhost/api/echelons?page=1","last_page": "http://localhost/api/echelons?page=1","next_page": null,"prev_page": null}}}
     *
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

        $echelons = DB::table('echelons');
        $echelons->select('echelons.id', 'echelons.name');
        $echelons->where('echelons.name', 'like', '%' . $this->request->search . '%');
        $echelons = $echelons->paginate($this->request->limit);
        if ($echelons->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $echelons);
        }
        return $this->paginateResponse(200, 'success', $echelons);
    }
}
