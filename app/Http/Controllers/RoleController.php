<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Access Control List
 * @subgroupDescription These endpoints allow you to perform CRUD operations on role data, enabling the retrieval, creation, updating and deleting of role records as needed.
 */
class RoleController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Roles
     *
     * Retrieve the role of Access Control List.
     * @subgroup Role
     * @authenticated
     * @queryParam page integer Refers to the current page of results being displayed. Default is '1'. Example: 1
     * @queryParam limit integer Refers to the maximum number of items to be displayed per page. Defaults is '10'. Example: 10
     * @queryParam search string The keyword search field for the name. Example: administrator
     * @response 200 {"code": 200,"message": "success","data": [{"id": 2,"name": "administrator"}],"pagination": {"total": 1,"count": 1,"per_page": 10,"current_page": 1,"total_pages": 1,"links": {"first_page": "http://localhost/api/roles?page=1","last_page": "http://localhost/api/roles?page=1","next_page": null,"prev_page": null}}}
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

        $roles = DB::table('roles');
        $roles->select('id', 'name');
        $roles->where('name', 'like', '%' . $this->request->search . '%');
        $roles->orderBy('created_at', 'desc');
        $roles = $roles->paginate($this->request->limit);
        if ($roles->isEmpty()) {
            return $this->paginateResponse(200, 'Mohon maaf, data tidak ditemukan.', $roles);
        }
        return $this->paginateResponse(200, 'success', $roles);
    }

    /**
     * Create a New Role
     *
     * Add a new role entry for Access Control List of role.
     * @subgroup Role
     * @authenticated
     * @response 200 {"code": 200,"message": "Role berhasil ditambah.","data": null}
     */
    public function create(CreateRoleRequest $request)
    {
        try {
            DB::beginTransaction();

            $role = DB::table('roles');
            $role = $role->insertGetIdTs([
                'name' => $this->request->name,
            ]);

            $data = [];
            foreach ($this->request->permissions as $item) {
                $array = [
                    'role_id' => $role,
                    'permission_id' => $item['id'],
                    'create' => (str_contains($item['permitted_actions'], 'c')) ? true : false,
                    'read' => (str_contains($item['permitted_actions'], 'r')) ? true : false,
                    'update' => (str_contains($item['permitted_actions'], 'u')) ? true : false,
                    'delete' => (str_contains($item['permitted_actions'], 'd')) ? true : false,
                ];
                array_push($data, $array);
            }

            $permissionRole = DB::table('role_permissions');
            $permissionRole->insertTs($data);

            DB::commit();
            return $this->response(200, 'Role berhasil ditambah.');
        } catch (\Throwable $th) {
            \Log::error($th);
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Get Detail Role by ID
     *
     * Retrieve role for a specific Access Control List of role.
     * @subgroup Role
     * @authenticated
     * @urlParam id Refers to the ID of Role. Example: 1
     * @response 404 {"code": 404,"message": "Role tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "success","data": {"id": 1,"name": "administrator","permissions": [{"id": 1,"name": "Rekapitulasi - Komposisi Pegawai","create": 0,"read": 1,"update": 0,"delete": 0}]}}
     */
    public function show()
    {
        $role = DB::table('roles');
        $role->where('id', $this->request->id);
        $role->select('id', 'name');
        $role = $role->first();
        if (!$role) {
            return $this->response(404, 'Role tidak ditemukan.');
        }

        $permissions = DB::table('role_permissions');
        $permissions->join('permissions', 'role_permissions.permission_id', 'permissions.id');
        $permissions->select('permissions.id', 'role_permissions.create', 'role_permissions.read', 'role_permissions.update', 'role_permissions.delete');
        $permissions->where('role_permissions.role_id', $role->id);
        $permissions = $permissions->get();

        foreach ($permissions as $permission) {
            $permittedActions = '';
            $permittedActions .= ($permission->create == true) ? 'c' : '';
            $permittedActions .= ($permission->read == true) ? 'r' : '';
            $permittedActions .= ($permission->update == true) ? 'u' : '';
            $permittedActions .= ($permission->delete == true) ? 'd' : '';
            $permission->permitted_actions = $permittedActions;
            unset($permission->create);
            unset($permission->read);
            unset($permission->update);
            unset($permission->delete);
        }

        $role->permissions = $permissions;

        return $this->response(200, 'success', $role);
    }

    /**
     * Update Role by ID
     *
     * Update an existing role entry.
     * @subgroup Role
     * @authenticated
     * @urlParam id Refers to the ID of Role. Example: 1
     * @response 404 {"code": 404,"message": "Role tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Role berhasil diupdate.","data": null}
     */
    public function update(UpdateRoleRequest $request)
    {
        $role = DB::table('roles');
        $role->where('id', $this->request->id);
        $role = $role->first();
        if (!$role) {
            return $this->response(404, 'Role tidak ditemukan.');
        }

        try {
            DB::beginTransaction();
            $role = DB::table('roles');
            $role->where('id', $this->request->id);
            $role->updateTs([
                'name' => $this->request->name,
            ]);

            // Delete Role Permission
            $rolePermission = DB::table('role_permissions');
            $rolePermission->where('role_id', $this->request->id);
            $rolePermission->delete();

            $data = [];
            foreach ($this->request->permissions as $item) {
                $array = [
                    'role_id' => $this->request->id,
                    'permission_id' => $item['id'],
                    'create' => (str_contains($item['permitted_actions'], 'c')) ? true : false,
                    'read' => (str_contains($item['permitted_actions'], 'r')) ? true : false,
                    'update' => (str_contains($item['permitted_actions'], 'u')) ? true : false,
                    'delete' => (str_contains($item['permitted_actions'], 'd')) ? true : false,
                ];
                array_push($data, $array);
            }

            $permissionRole = DB::table('role_permissions');
            $permissionRole->insertTs($data);

            DB::commit();
            return $this->response(200, 'Role telah berhasil diupdate.');
        } catch (\Throwable $th) {
            \Log::error($th);
            DB::rollback();
            return $this->response(500, 'Mohon maaf, fitur dalam kendala harap hubungi Tim IT!');
        }
    }

    /**
     * Delete Role by ID
     *
     * Delete a specific role entry.
     * @subgroup Role
     * @authenticated
     * @urlParam id Refers to the ID of Role. Example: 1
     * @response 200 {"code": 200,"message": "Role berhasil dihapus.","data": null}
     */
    public function delete()
    {
        $role = DB::table('roles');
        $role->where('id', $this->request->id);
        $role = $role->delete();
        if (!$role) {
            return $this->response(404, 'Role tidak ditemukan.');
        }
        return $this->response(200, 'Role berhasil dihapus.');
    }
}
