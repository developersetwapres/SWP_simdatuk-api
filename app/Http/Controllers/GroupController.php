<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Master Data
 * @subgroupDescription These endpoints allow you to perform CRUD operations on group data, enabling the retrieval, creation, updating and deleting of group records as needed.
 */
class GroupController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Groups
     *
     * Retrieve the group of master data.
     * @subgroup Group
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name of group. Example: Arsip
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

        $groups = DB::table('groups');
        $groups->select('id', 'name', 'created_at');
        $groups->where('name', 'like', '%' . $this->request->search . '%');

        if (is_null($this->request->limit)) {
            $groups = $groups->get();
            $message = (count($groups) < 1) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            return $this->response(200, $message, $groups);
        } else {
            $groups = $groups->paginate($this->request->limit);
            $message = ($groups->isEmpty()) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            return $this->paginateResponse(200, $message, $groups);
        }
    }
}
