<?php

namespace App\Http\Controllers;

use App\Http\Requests\College\CreateCollegeRequest;
use App\Http\Requests\College\UpdateCollegeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group College
 *
 * APIs for college
 */
class CollegeController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Colleges
     * @group College
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam keyword string The keyword search field for the name of college. Example: indonesia
     * @response 200 {"code": 200, "message": "success", "data": [{"id": 32, "username": "admin", "nip": "0000000000000", "nrp": "0000000000000", "role_name": "administrator", "status": "Aktif"}], "pagination": {"total": 1, "count": 1, "per_page": 1, "current_page": 1, "total_pages": 1, "links": {"first_page": "http://localhost/api/users?page=1", "last_page": "http://localhost/api/users?page=1", "next_page": null, "prev_page": null}}}
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

        $users = DB::table('colleges');
        $users->select('id', 'name', 'region', 'address');
        $users->where('name', 'like', '%' . $this->request->keyword . '%');
        $users = $users->paginate($this->request->limit);
        if ($users->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $users);
        }
        foreach ($users->items() as $key => $item) {
            $item->region = ($item == true) ? 'Luar Negeri' : 'Dalam Negeri';
        }
        return $this->paginateResponse(200, 'success', $users);
    }

    /**
     * Create a New College
     * @group College
     * @authenticated
     * @response 200 {"code": 200,"message": "Perguruan tinggi berhasil ditambah.","data": null}
     */
    public function create(CreateCollegeRequest $request)
    {
        DB::table('colleges')->insertTs($this->posted);
        return $this->response(200, 'Perguruan tinggi berhasil ditambah.');
    }

    /**
     * Get Detail College by ID
     * @group College
     * @authenticated
     * @urlParam id Refers to the ID of College. Example: 1
     * @response 404 {"code": 404,"message": "Perguruan tinggi tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 1,"name": "Universitas Gadjah Mada","region": "Dalam negeri","address": "Daerah Istimewa Yogyakarta"}}
     */
    public function show()
    {
        $college = DB::table('colleges');
        $college->select('id', 'name', 'region', 'address');
        $college->where('id', $this->request->id);
        $college = $college->first();
        if (!$college) {
            return $this->response(404, 'Perguruan tinggi tidak ditemukan.');
        }
        $college->region = ($college->region == true) ? 'Luar negeri' : 'Dalam negeri';
        return $this->response(200, 'success', $college);
    }

    /**
     * Update College by ID
     * @group College
     * @authenticated
     * @urlParam id Refers to the ID of College. Example: 1
     * @response 404 {"code": 404,"message": "Perguruan tinggi tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Perguruan tinggi berhasil diupdate.","data": null}
     */
    public function update(UpdateCollegeRequest $request)
    {
        $college = DB::table('colleges');
        $college->where('id', $this->request->id);
        $college = $college->updateTs($this->posted);
        if (!$college) {
            return $this->response(404, 'Perguruan tinggi tidak ditemukan.');
        }
        return $this->response(200, 'Perguruan berhasil diupdate.');
    }

    /**
     * Delete College by ID
     * @group College
     * @authenticated
     * @urlParam id Refers to the ID of College. Example: 1
     * @response 404 {"code": 404,"message": "Perguruan tinggi tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Perguruan tinggi berhasil dihapus.","data": null}
     */
    public function delete()
    {
        $college = DB::table('colleges');
        $college->where('id', $this->request->id);
        $college = $college->delete();
        if (!$college) {
            return $this->response(404, 'Perguruan tinggi tidak ditemukan.');
        }
        return $this->response(200, 'Perguruan tinggi berhasil dihapus.');
    }
}
