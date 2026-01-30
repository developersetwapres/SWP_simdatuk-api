<?php

namespace App\Traits;

use App\Models\UserDeviceSession;
use Illuminate\Http\Request;
use Laravel\Sanctum\NewAccessToken;

trait SingleDeviceLogin
{
    /**
     * Handle single device login logic
     */
    public function handleSingleDeviceLogin($user, Request $request): NewAccessToken
    {
        $deviceIdentifier = $this->generateDeviceIdentifier($request);
        
        // Revoke all existing tokens and sessions for this user
        $user->revokeOtherDeviceSessions();
        
        // Create new token
        $tokenResult = $user->createToken('web');
        $token = $tokenResult->accessToken ?? $tokenResult;
        $tokenId = $token->accessToken->id ?? $tokenResult->accessToken->id;
        
        // Store device session
        UserDeviceSession::create([
            'user_id' => $user->id,
            'device_identifier' => $deviceIdentifier,
            'device_name' => $this->getDeviceName($request),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'sanctum_token_id' => $tokenId,
            'last_activity_at' => now()
        ]);
        
        return $tokenResult;
    }

    /**
     * Generate unique device identifier
     */
    private function generateDeviceIdentifier(Request $request): string
    {
        return UserDeviceSession::generateDeviceIdentifier(
            $request->userAgent(),
            $request->ip()
        );
    }

    /**
     * Extract device name from user agent
     */
    private function getDeviceName(Request $request): string
    {
        $userAgent = $request->userAgent();
        
        // Basic device detection
        if (str_contains($userAgent, 'Mobile')) {
            return 'Mobile Device';
        } elseif (str_contains($userAgent, 'Tablet')) {
            return 'Tablet Device';
        } else {
            return 'Desktop/Laptop';
        }
    }

    /**
     * Update device activity
     */
    public function updateDeviceActivity(Request $request, $tokenId)
    {
        UserDeviceSession::where('sanctum_token_id', $tokenId)
            ->update(['last_activity_at' => now()]);
    }

    /**
     * Check if device session is valid
     */
    public function isDeviceSessionValid($user, Request $request): bool
    {
        $deviceIdentifier = $this->generateDeviceIdentifier($request);
        
        return UserDeviceSession::where('user_id', $user->id)
            ->where('device_identifier', $deviceIdentifier)
            ->exists();
    }
}