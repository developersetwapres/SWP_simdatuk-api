<?php

namespace App\Http\Controllers;

use App\Http\Requests\College\CreateCollegeRequest;
use App\Http\Requests\College\UpdateCollegeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $colleges = DB::table('colleges');
        $colleges->select('id', 'name', 'region', 'address');
        $colleges->where('name', 'like', '%' . $this->request->keyword . '%');
        $colleges = $colleges->paginate($this->request->limit);

        if ($colleges->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $colleges);
        }

        foreach ($colleges->items() as $key => $item) {
            $item->region = ($item == true) ? 'Luar Negeri' : 'Dalam Negeri';
        }

        return $this->paginateResponse(200, 'success', $colleges);
    }

    /**
     * Create a New College
     * @group College
     * @authenticated
     * @response 200 {"code": 200,"message": "Perguruan tinggi berhasil ditambah.","data": null}
     */
    public function create(CreateCollegeRequest $request)
    {
        if ($this->request->hasFile('accreditation_certificate')) {
            $fileExtension = '.' . $this->request->file('accreditation_certificate')->getClientOriginalExtension();
            $fileName = Str::random(32) . $fileExtension;
            Storage::disk('public')->putFileAs('accreditation_certificate/', $this->request->file('accreditation_certificate'), $fileName);
            $this->posted['accreditation_certificate'] = 'accreditation_certificate/' . $fileName;
        }

        DB::table('colleges')->insertTs($this->posted);

        return $this->response(200, 'Perguruan tinggi berhasil ditambah.');
    }

    /**
     * Get Detail College by ID
     * @group College
     * @authenticated
     * @urlParam id Refers to the ID of College. Example: 1
     * @response 404 {"code": 404,"message": "Perguruan tinggi tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 1,"name": "Universitas Gadjah Mada","region": "Dalam negeri","address": "Daerah Istimewa Yogyakarta", "accreditation": "A", "accreditation_certificate": "http://localhost/storage/avatars/8X1kJJ0kP0pg08dC0xTKLzfH88Doaegm.png"}}
     */
    public function show()
    {
        $college = DB::table('colleges');
        $college->select('id', 'name', 'region', 'address', 'accreditation', 'accreditation_certificate');
        $college->where('id', $this->request->id);
        $college = $college->first();

        if (!$college) {
            return $this->response(404, 'Perguruan tinggi tidak ditemukan.');
        }

        $college->region = ($college->region == true) ? 'Luar negeri' : 'Dalam negeri';
        $college->accreditation_certificate = (is_null($college->accreditation_certificate)) ? null : Storage::disk('public')->url($college->accreditation_certificate);

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
        $college->select('id', 'accreditation_certificate');
        $college = $college->first();

        if (!$college) {
            return $this->response(404, 'Perguruan tinggi tidak ditemukan.');
        }

        if ($this->request->hasFile('accreditation_certificate')) {
            $fileExtension = '.' . $this->request->file('accreditation_certificate')->getClientOriginalExtension();
            $fileName = Str::random(32) . $fileExtension;
            Storage::disk('public')->putFileAs('accreditation_certificate/', $this->request->file('accreditation_certificate'), $fileName);
            $this->posted['accreditation_certificate'] = 'accreditation_certificate/' . $fileName;

            if (!is_null($college->accreditation_certificate)) {
                Storage::disk('public')->delete($college->accreditation_certificate);
            }
        }

        $college = DB::table('colleges');
        $college->where('id', $this->request->id);
        $college = $college->updateTs($this->posted);

        return $this->response(200, 'Perguruan tinggi berhasil diupdate.');
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
        $college->select('id', 'accreditation_certificate');
        $college = $college->first();

        if (!$college) {
            return $this->response(404, 'Perguruan tinggi tidak ditemukan.');
        }

        if (!is_null($college->accreditation_certificate)) {
            Storage::disk('public')->delete($college->accreditation_certificate);
        }

        $college = DB::table('colleges');
        $college->where('id', $this->request->id);
        $college = $college->delete();

        return $this->response(200, 'Perguruan tinggi berhasil dihapus.');
    }
}
