<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Master Data
 * @subgroupDescription These endpoints allow you to perform CRUD operations on disciplinary data, enabling the retrieval, creation, updating and deleting of disciplinary records as needed.
 */
class DisciplinaryController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Disciplinaries
     *
     * Retrieve the disciplinary of master data.
     * @subgroup Disciplinary
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam keyword string The keyword search field for the name of employment type. Example: Teguran Tertulis
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"name": "Teguran Lisan","description": "Hukuman Disiplin Tingkat Ringan 1","performance_allowance_deduction": 0.25,"performance_allowance_duration": 2}],"pagination": {"total": 11,"count": 10,"per_page": 10,"current_page": 1,"total_pages": 2,"links": {"first_page": "http://localhost/api/disciplinary-types?page=1","last_page": "http://localhost/api/disciplinary-types?page=2","next_page": "http://localhost/api/disciplinary-types?page=2","prev_page": null}}}
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

        $disciplinaryTypes = DB::table('disciplinary_types');
        $disciplinaryTypes->select('id', 'name', 'description', 'performance_allowance_deduction', 'performance_allowance_duration');
        $disciplinaryTypes->where('name', 'like', '%' . $this->request->keyword . '%');
        $disciplinaryTypes = $disciplinaryTypes->paginate($this->request->limit);

        if ($disciplinaryTypes->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $disciplinaryTypes);
        }

        return $this->paginateResponse(200, 'success', $disciplinaryTypes);
    }
}
