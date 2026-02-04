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
            $request->ip()
        );
    }
}