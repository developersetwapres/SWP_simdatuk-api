<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\CreateEmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Employee
 *
 * APIs for employee
 */
class EmployeeController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Employee
     * @group Employee
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam keyword string The keyword search field for the name or employee id number. Example: administrator
     * @response 200 {"code": 200, "message": "success", "data": [{"id": 32, "username": "admin", "employee_id_number": "0000000000000", "employee_registration_number": "0000000000000", "role_name": "administrator", "status": "Aktif"}], "pagination": {"total": 1, "count": 1, "per_page": 1, "current_page": 1, "total_pages": 1, "links": {"first_page": "http://localhost/api/users?page=1", "last_page": "http://localhost/api/users?page=1", "next_page": null, "prev_page": null}}}
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

        $users = DB::table('users');
        $users->select('users.id', 'users.photo_profile', 'users.name', 'users.employee_id_number', 'users.employee_registration_number');
        $users->where('users.name', 'like', '%' . $this->request->keyword . '%');
        $users->orWhere('users.employee_id_number', 'like', '%' . $this->request->keyword . '%');
        $users = $users->paginate($this->request->limit);
        if ($users->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $users);
        }
        foreach ($users->items() as $key => $item) {
            $item->status = ($item == true) ? 'Aktif' : 'Nonaktif';
        }
        return $this->paginateResponse(200, 'success', $users);
    }

    /**
     * Create a New Employee
     * @group Employee
     * @authenticated
     * @response 200 {"code": 200,"message": "Perguruan tinggi berhasil ditambah.","data": null}
     */
    public function create(CreateEmployeeRequest $request)
    {
        if ($this->request->hasFile('photo_profile')) {
            $fileExtension = '.' . $this->request->file('photo_profile')->getClientOriginalExtension();
            $fileName = Str::random(32) . $fileExtension;
            Storage::disk('public')->putFileAs('photo_profile/', $this->request->file('photo_profile'), $fileName);
            $this->posted['photo_profile'] = 'photo_profile/' . $fileName;
        }

        DB::table('employees')->insertTs($this->posted);

        return $this->response(200, 'Pegawai berhasil ditambah.');
    }

    /**
     * Get Detail Employee by ID
     * @group Employee
     * @authenticated
     * @urlParam id Refers to the ID of Employee. Example: 1
     * @response 404 {"code": 404,"message": "Perguruan tinggi tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 1,"name": "Universitas Gadjah Mada","region": "Dalam negeri","address": "Daerah Istimewa Yogyakarta", "accreditation": "A", "photo_profile": "http://localhost/storage/avatars/8X1kJJ0kP0pg08dC0xTKLzfH88Doaegm.png"}}
     */
    public function show()
    {

    }

    /**
     * Update Employee by ID
     * @group Employee
     * @authenticated
     * @urlParam id Refers to the ID of College. Example: 1
     * @response 404 {"code": 404,"message": "Perguruan tinggi tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Perguruan tinggi berhasil diupdate.","data": null}
     */
    public function update()
    {

    }
}
