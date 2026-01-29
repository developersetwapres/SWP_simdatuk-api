<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission  Permission name
     * @param  string  $action  Required action (create|read|update|delete)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $permission, string $action = 'read')
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized. Please login first.',
                'data' => null
            ], 401);
        }

        // Check if user has role
        if (!$user->role_id) {
            return response()->json([
                'code' => 403,
                'message' => 'Access denied. No role assigned to user.',
                'data' => null
            ], 403);
        }

        // Get user permissions for the specific permission and action
        $hasPermission = DB::table('permissions as p')
            ->join('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
            ->where('rp.role_id', $user->role_id)
            ->where('p.name', $permission)
            ->where("rp.{$action}", true)
            ->exists();

        if (!$hasPermission) {
            return response()->json([
                'code' => 403,
                'message' => "Access denied. You don't have permission to {$action} {$permission}.",
                'data' => null
            ], 403);
        }

        return $next($request);
    }
}