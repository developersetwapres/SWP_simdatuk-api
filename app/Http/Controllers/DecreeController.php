<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Master Data
 * @subgroupDescription These endpoints allow you to perform CRUD operations on decree data, enabling the retrieval, creation, updating and deleting of decree records as needed.
 */
class DecreeController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Decrees
     *
     * Retrieve the decree of master data.
     * @subgroup Decree
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam keyword string The keyword search field for the name of decree type. Example: Keputusan Presiden
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"name": "Keputusan Presiden","acronym": "Keppres","created_at": "2024-05-05 10:44:27"}],"pagination": {"total": 14,"count": 10,"per_page": 10,"current_page": 1,"total_pages": 2,"links": {"first_page": "http://localhost/api/decree-types?page=1","last_page": "http://localhost/api/decree-types?page=2","next_page": "http://localhost/api/decree-types?page=2","prev_page": null}}}

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

        $decrees = DB::table('decrees');
        $decrees->select('id', 'name', 'acronym', 'created_at');
        $decrees->where('name', 'like', '%' . $this->request->keyword . '%');
        $decrees = $decrees->paginate($this->request->limit);

        if ($decrees->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $decrees);
        }

        return $this->paginateResponse(200, 'success', $decrees);
    }
}
