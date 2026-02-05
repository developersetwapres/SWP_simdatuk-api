<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleBasedAccess
{
    /**
     * Handle an incoming request.
     * 
     * This middleware provides flexible permission checking based on route patterns
     * and HTTP methods, automatically mapping to CRUD operations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $permission  Optional specific permission name
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $permission = null)
    {
        $user = $request->user();
        
        if (!$user || !$user->role_id) {
            return response()->json([
                'code' => 403,
                'message' => 'Access denied. Authentication required.',
                'data' => null
            ], 403);
        }

        // Determine permission name and required action
        $permissionName = $permission ?? $this->getPermissionFromRoute($request);
        $requiredAction = $this->getRequiredAction($request);

        if (!$permissionName) {
            // Log untuk debugging
            Log::warning('No permission mapping found for route', [
                'route' => $request->route()->getName(),
                'uri' => $request->getRequestUri(),
                'method' => $request->getMethod()
            ]);
            
            return $next($request); // Allow if no specific permission required
        }

        // Check user permission
        $hasPermission = $this->checkUserPermission($user->role_id, $permissionName, $requiredAction);

        if (!$hasPermission) {
            return response()->json([
                'code' => 403,
                'message' => "Access denied. You don't have permission to {$requiredAction} {$permissionName}.",
                'data' => null
            ], 403);
        }

        return $next($request);
    }

    /**
     * Get permission name from route pattern
     */
    private function getPermissionFromRoute(Request $request): ?string
    {
        $uri = $request->getRequestUri();
        $path = parse_url($uri, PHP_URL_PATH);
        
        // Route mapping untuk endpoint permissions
        $routePermissionMap = [
            // Rekapitulasi endpoints
            'api/recapitulations' => 'Rekapitulasi - Komposisi Pegawai',
            'api/recapitulations-asn' => 'Rekapitulasi - Pegawai ASN', 
            'api/recapitulations-nonasn' => 'Rekapitulasi - Pegawai Non ASN',
            'api/recapitulations-outsource' => 'Rekapitulasi - Pegawai Outsourcing',
            'api/recapitulations-employee' => 'Rekapitulasi - Komposisi Pegawai',
            'api/diagrams' => 'Rekapitulasi - Peta Jabatan',
            'api/comparisons' => 'Rekapitulasi - Bandingkan Pegawai',
            'api/promotions' => 'Rekapitulasi - Promosi Pegawai',
            
            // Data Pegawai endpoints
            'api/employees' => 'Data Pegawai - ASN', // Default to ASN, can be refined
            'api/import-employees' => 'Data Pegawai - ASN',
            
            // Master Data endpoints
            'api/users' => 'Master Data - Data Pengguna',
            'api/roles' => 'Master Data - Data Role Pengguna', 
            'api/permissions' => 'Master Data - Data Role Pengguna',
            'api/positions' => 'Master Data - Data Jabatan',
            'api/institutions' => 'Master Data - Data Instansi',
            'api/grades' => 'Master Data - Data Golongan',
            'api/employment-types' => 'Master Data - Jenis Pegawai',
            
            // Riwayat endpoints
            'api/position-histories' => 'Data Riwayat - Jabatan',
            'api/grade-histories' => 'Data Riwayat - Golongan', 
            'api/training-histories' => 'Data Riwayat - Pelatihan Struktural', // Default
            'api/recognition-histories' => 'Data Riwayat - Penghargaan',
            'api/target-histories' => 'Data Riwayat - SKP',
            'api/performance-histories' => 'Data Riwayat - Penilaian Prestasi Kerja',
            'api/disciplinary-histories' => 'Data Riwayat - Hukuman Disiplin',
            
            // Export endpoints
            'api/exports' => 'Export',
            'api/export-comparisons' => 'Export',
            'api/export-recapitulations' => 'Export',
            
            // Notes and Talent Pool
            'api/notes' => 'Catatan',
            'api/talents' => 'Hasil Talent Pool',
        ];

        // Check for exact match first
        $cleanPath = rtrim($path, '/');
        if (isset($routePermissionMap[$cleanPath])) {
            return $routePermissionMap[$cleanPath];
        }

        // Check for pattern matches (with parameters)
        foreach ($routePermissionMap as $pattern => $permission) {
            if (str_starts_with($cleanPath, $pattern)) {
                return $permission;
            }
        }

        return null;
    }

    /**
     * Determine required action based on HTTP method
     */
    private function getRequiredAction(Request $request): string
    {
        return match($request->getMethod()) {
            'GET' => 'read',
            'POST' => 'create', 
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'read'
        };
    }

    /**
     * Check if user has specific permission and action
     */
    private function checkUserPermission(int $roleId, string $permissionName, string $action): bool
    {
        return DB::table('permissions as p')
            ->join('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
            ->where('rp.role_id', $roleId)
            ->where('p.name', $permissionName)
            ->where("rp.{$action}", true)
            ->exists();
    }
}