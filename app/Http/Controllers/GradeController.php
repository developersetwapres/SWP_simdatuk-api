<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Master Data
 * @subgroupDescription These endpoints allow you to perform CRUD operations on grade data, enabling the retrieval, creation, updating and deleting of grade records as needed.
 */
class GradeController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Grades
     *
     * Retrieve the grade of master data.
     * @subgroup Grade
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name or code. Example: pembina utama
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"name": "Pembina Utama","code": "IV/e","type": "PNS"}],"pagination": {"total": 32,"count": 1,"per_page": 1,"current_page": 1,"total_pages": 32,"links": {"first_page": "http://localhost/api/grades?page=1","last_page": "http://localhost/api/grades?page=32","next_page": "http://localhost/api/grades?page=2","prev_page": null}}}
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

        $grades = DB::table('grades');
        $grades->select('grades.id', 'grades.name', 'grades.code', 'grades.type');
        $grades->where('grades.name', 'like', '%' . $this->request->search . '%');
        $grades->orWhere('grades.code', 'like', '%' . $this->request->search . '%');
        $grades->orderBy('id', 'asc');

        if (is_null($this->request->limit)) {
            $grades = $grades->get();
            $message = (count($grades) < 1) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            foreach ($grades as $key => $item) {
                $item->type = ($item->type == 1) ? 'PNS' : 'PPPK';
            }
            return $this->response(200, $message, $grades);
        } else {
            $grades = $grades->paginate($this->request->limit);
            $message = ($grades->isEmpty()) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            foreach ($grades->items() as $key => $item) {
                $item->type = ($item->type == 1) ? 'PNS' : 'PPPK';
            }
            return $this->paginateResponse(200, $message, $grades);
        }
    }
}
