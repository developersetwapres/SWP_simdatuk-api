<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @group Master Data
 * These endpoints would allow you to track and manage the master data of position, grade, institution, employment type, decree type, and other pertinent events.
 * @subgroupDescription These endpoints allow you to perform CRUD operations on position data, enabling the retrieval, creation, updating and deleting of position records as needed.
 */
class PositionController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Positions
     *
     * Retrieve the position of master data.
     * @subgroup Position
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam keyword string The keyword search field for the name or code. Example: pembina utama
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"name": "Pembina Utama","code": "IV/e","type": "PNS"}],"pagination": {"total": 32,"count": 1,"per_page": 1,"current_page": 1,"total_pages": 32,"links": {"first_page": "http://localhost/api/grades?page=1","last_page": "http://localhost/api/grades?page=32","next_page": "http://localhost/api/grades?page=2","prev_page": null}}}
     *
     */
    public function index()
    {
    }
}
