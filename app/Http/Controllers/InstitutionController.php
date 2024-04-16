<?php

namespace App\Http\Controllers;

use App\Http\Requests\College\CreateCollegeRequest;
use App\Http\Requests\College\UpdateCollegeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @group Institution
 *
 * APIs for institution
 */
class InstitutionController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Institutions
     * @group Institution
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam keyword string The keyword search field for the name of institution. Example: kementerian
     * @response 200 {"code": 200,"message": "success","data": [{"id": 2,"name": "Universitas Indonesia","region": "Luar Negeri","address": "Jawa Barat"}],"pagination": {"total": 10,"count": 1,"per_page": 1,"current_page": 1,"total_pages": 10,"links": {"first_page": "http://localhost/api/colleges?page=1","last_page": "http://localhost/api/colleges?page=10","next_page": "http://localhost/api/colleges?page=2","prev_page": null}}}
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

        $institutions = DB::table('institutions');
        $institutions->select('id', 'name');
        $institutions->where('name', 'like', '%' . $this->request->keyword . '%');
        $institutions = $institutions->paginate($this->request->limit);

        if ($institutions->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $institutions);
        }

        return $this->paginateResponse(200, 'success', $institutions);
    }

    /**
     * Create a New Institution
     * @group Institution
     * @authenticated
     * @response 200 {"code": 200,"message": "Institusi berhasil ditambah.","data": null}
     */
    public function create(CreateCollegeRequest $request)
    {
        DB::table('institutions')->insertTs($this->posted);
        return $this->response(200, 'Institusi berhasil ditambah.');
    }

    /**
     * Get Detail Institution by ID
     * @group Institution
     * @authenticated
     * @urlParam id Refers to the ID of Institution. Example: 1
     * @response 404 {"code": 404,"message": "Institusi tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 1,"name": "Universitas Gadjah Mada","region": "Dalam negeri","address": "Daerah Istimewa Yogyakarta", "accreditation": "A", "accreditation_certificate": "http://localhost/storage/avatars/8X1kJJ0kP0pg08dC0xTKLzfH88Doaegm.png"}}
     */
    public function show()
    {
        $institution = DB::table('institutions');
        $institution->select('id', 'name');
        $institution->where('id', $this->request->id);
        $institution = $institution->first();

        if (!$institution) {
            return $this->response(404, 'Institusi tidak ditemukan.');
        }

        return $this->response(200, 'success', $institution);
    }

    /**
     * Update Institution by ID
     * @group Institution
     * @authenticated
     * @urlParam id Refers to the ID of Institution. Example: 1
     * @response 404 {"code": 404,"message": "Institusi tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Institusi berhasil diupdate.","data": null}
     */
    public function update(UpdateCollegeRequest $request)
    {
        $institution = DB::table('institutions');
        $institution->where('id', $this->request->id);
        $institution->select('id');
        $institution = $institution->first();

        if (!$institution) {
            return $this->response(404, 'Institusi tidak ditemukan.');
        }

        $institution = DB::table('institutions');
        $institution->where('id', $this->request->id);
        $institution = $institution->updateTs($this->posted);

        return $this->response(200, 'Institusi berhasil diupdate.');
    }

    /**
     * Delete Institution by ID
     * @group Institution
     * @authenticated
     * @urlParam id Refers to the ID of Institution. Example: 1
     * @response 404 {"code": 404,"message": "Institusi tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Institusi berhasil dihapus.","data": null}
     */
    public function delete()
    {
        $institution = DB::table('institutions');
        $institution->where('id', $this->request->id);
        $institution->select('id');
        $institution = $institution->first();

        if (!$institution) {
            return $this->response(404, 'Institusi tidak ditemukan.');
        }

        $institution = DB::table('institutions');
        $institution->where('id', $this->request->id);
        $institution = $institution->delete();

        return $this->response(200, 'Institusi berhasil dihapus.');
    }
}
