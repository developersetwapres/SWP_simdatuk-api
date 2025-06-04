<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Master Data
 * @subgroupDescription These endpoints allow you to perform CRUD operations on recognition data, enabling the retrieval, creation, updating and deleting of recognition records as needed.
 */
class RecognitionController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Recognitions
     *
     * Retrieve the recognition of master data.
     * @subgroup Recognition
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name of recognition. Example: Satyalancana
     * @response 200 {"code": 200,"message": "success","data": [{"id": 1,"name": "Satyalancana Karya Satya 10th","description": "ASN yang telah berbakti selama 10 tahun","created_at": "2024-06-20 09:25:53"}],"pagination": {"total": 5,"count": 5,"per_page": 10,"current_page": 1,"total_pages": 1,"links": {"first_page": "http://localhost/api/recognitions?page=1","last_page": "http://localhost/api/recognitions?page=1","next_page": null,"prev_page": null}}}
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

        $recognitions = DB::table('recognitions');
        $recognitions->select('id', 'name', 'description', 'created_at');
        $recognitions->where('name', 'like', '%' . $this->request->search . '%');
        $recognitions->orderBy('id', 'asc');

        if (is_null($this->request->limit)) {
            $recognitions = $recognitions->get();
            $message = (count($recognitions) < 1) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            return $this->response(200, $message, $recognitions);
        } else {
            $recognitions = $recognitions->paginate($this->request->limit);
            $message = ($recognitions->isEmpty()) ? 'Mohon maaf, data tidak ditemukan.' : 'success';
            return $this->paginateResponse(200, $message, $recognitions);
        }
    }
}
