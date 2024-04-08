<?php

namespace App\Http\Controllers;

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
     * @queryParam keyword string The keyword search field for the name or nip. Example: administrator
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

        $users = DB::table('users');
        $users->select('users.id', 'users.file_foto_profil', 'users.name', 'users.nip', 'users.nrp');
        $users->where('users.name', 'like', '%' . $this->request->keyword . '%');
        $users->orWhere('users.nip', 'like', '%' . $this->request->keyword . '%');
        $users = $users->paginate($this->request->limit);
        if ($users->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $users);
        }
        foreach ($users->items() as $key => $item) {
            $item->status = ($item == true) ? 'Aktif' : 'Nonaktif';
        }
        return $this->paginateResponse(200, 'success', $users);
    }
}
