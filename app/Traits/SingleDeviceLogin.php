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
            'ip_address' => $this->getRealClientIp($request),
            'user_agent' => $request->userAgent(),
            'sanctum_token_id' => $tokenId,
            'last_activity_at' => now()
        ]);
        
        return $tokenResult;
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
    
    /**
     * Debug method to log all IP-related headers
     * Useful for troubleshooting multi-proxy setup
     */
    public function debugIPHeaders(Request $request): array
    {
        return [
            'request_ip' => $request->ip(),
            'cf_connecting_ip' => $request->header('CF-Connecting-IP'),
            'x_real_ip' => $request->header('X-Real-IP'),
            'x_forwarded_for' => $request->header('X-Forwarded-For'),
            'x_original_forwarded_for' => $request->header('X-Original-Forwarded-For'),
            'remote_addr' => $request->server('REMOTE_ADDR'),
            'http_client_ip' => $request->server('HTTP_CLIENT_IP'),
            'detected_real_ip' => $this->getRealClientIp($request),
        ];
    }

    /**
     * Generate unique device identifier
     */
    private function generateDeviceIdentifier(Request $request): string
    {
        return UserDeviceSession::generateDeviceIdentifier(
            $request->userAgent(),
            $this->getRealClientIp($request)
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