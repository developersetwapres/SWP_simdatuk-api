<?php

namespace App\Http\Middleware;

use App\Models\UserDeviceSession;
use App\Traits\SingleDeviceLogin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateDeviceSession
{
    use SingleDeviceLogin;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized',
                'data' => null
            ], 401);
        }

        $currentToken = $user->currentAccessToken();
        
        // Check if device session exists for this token
        $deviceSession = UserDeviceSession::where('sanctum_token_id', $currentToken->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$deviceSession) {
            // Token exists but no device session - invalid state
            $currentToken->delete();
            
            return response()->json([
                'code' => 401,
                'message' => 'Anda login di perangkat lain. Silakan login kembali.',
                'data' => null,
                'error_code' => 'SESSION_INVALID'
            ], 401);
        }

        // Validate device identifier
        $currentDeviceId = $this->generateDeviceIdentifier($request);
        if ($deviceSession->device_identifier !== $currentDeviceId) {
            // Device changed - force logout
            $deviceSession->delete();
            $currentToken->delete();
            
            return response()->json([
                'code' => 401,
                'message' => 'Anda login di perangkat lain. Silakan login kembali.',
                'data' => null,
                'error_code' => 'DEVICE_MISMATCH'
            ], 401);
        }

        // Update last activity
        $deviceSession->update(['last_activity_at' => now()]);

        return $next($request);
    }

    /**
     * Generate device identifier from request
     */
    private function generateDeviceIdentifier(Request $request): string
    {
        return UserDeviceSession::generateDeviceIdentifier(
            $request->userAgent(),
            $this->getRealClientIp($request)
        );
    }

    /**
     * Get real client IP address considering multiple proxy layers
     * Cloudflare → Nginx Global → Nginx Docker → Laravel
     */
    private function getRealClientIp(Request $request): string
    {
        // Priority 1: Cloudflare real IP (most reliable)
        if ($request->header('CF-Connecting-IP')) {
            return $request->header('CF-Connecting-IP');
        }
        
        // Priority 2: Original forwarded header before nginx global
        if ($request->header('X-Original-Forwarded-For')) {
            $ips = explode(',', $request->header('X-Original-Forwarded-For'));
            $firstIp = trim($ips[0]);
            if ($this->isValidPublicIP($firstIp)) {
                return $firstIp;
            }
        }
        
        // Priority 3: X-Real-IP from nginx
        if ($request->header('X-Real-IP')) {
            $realIp = $request->header('X-Real-IP');
            if ($this->isValidPublicIP($realIp)) {
                return $realIp;
            }
        }
        
        // Priority 4: Parse X-Forwarded-For chain
        if ($request->header('X-Forwarded-For')) {
            $forwardedFor = $request->header('X-Forwarded-For');
            $ips = explode(',', $forwardedFor);
            
            // Get the first public IP from the chain
            foreach ($ips as $ip) {
                $cleanIp = trim($ip);
                if ($this->isValidPublicIP($cleanIp)) {
                    return $cleanIp;
                }
            }
        }
        
        // Fallback to Laravel's ip() method
        return $request->ip();
    }
    
    /**
     * Check if IP is a valid public IP address
     */
    private function isValidPublicIP(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, 
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) !== false;
    }
}