<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasPermissions
{
    /**
     * Check if current authenticated user has permission with specific action
     */
    protected function hasPermission(string $permissionName, string $action = 'read'): bool
    {
        $user = auth()->user();
        
        if (!$user || !$user->role_id) {
            return false;
        }

        return DB::table('permissions as p')
            ->join('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
            ->where('rp.role_id', $user->role_id)
            ->where('p.name', $permissionName)
            ->where("rp.{$action}", true)
            ->exists();
    }

    /**
     * Get current user's permissions for a specific permission name
     */
    protected function getPermissionActions(string $permissionName): array
    {
        $user = auth()->user();
        
        if (!$user || !$user->role_id) {
            return ['create' => false, 'read' => false, 'update' => false, 'delete' => false];
        }

        $permission = DB::table('permissions as p')
            ->join('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
            ->where('rp.role_id', $user->role_id)
            ->where('p.name', $permissionName)
            ->select('rp.create', 'rp.read', 'rp.update', 'rp.delete')
            ->first();

        if (!$permission) {
            return ['create' => false, 'read' => false, 'update' => false, 'delete' => false];
        }

        return [
            'create' => (bool) $permission->create,
            'read' => (bool) $permission->read,
            'update' => (bool) $permission->update,
            'delete' => (bool) $permission->delete,
        ];
    }

    /**
     * Return permission denied response
     */
    protected function permissionDenied(string $action, string $resource): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'code' => 403,
            'message' => "Access denied. You don't have permission to {$action} {$resource}.",
            'data' => null
        ], 403);
    }

    /**
     * Check permission and return error response if denied
     */
    protected function checkPermissionOrFail(string $permissionName, string $action = 'read'): ?\Illuminate\Http\JsonResponse
    {
        if (!$this->hasPermission($permissionName, $action)) {
            return $this->permissionDenied($action, $permissionName);
        }
        
        return null;
    }
}