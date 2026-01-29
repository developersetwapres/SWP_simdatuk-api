<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiRateLimit
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $maxAttempts
     * @param  int  $decayMinutes
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $maxAttempts = null, $decayMinutes = null)
    {
        // Use environment variables if parameters not provided
        $maxAttempts = $maxAttempts ?? config('app.rate_limit_api_attempts', 60);
        $decayMinutes = $decayMinutes ?? config('app.rate_limit_api_decay_minutes', 1);
        
        // Get real IP from headers set by nginx proxy
        $ip = $this->getRealIpAddress($request);
        
        $key = $this->resolveRequestSignature($request, $ip);
        
        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return $this->buildException($key, $maxAttempts);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }

    /**
     * Get the real IP address from request headers
     */
    protected function getRealIpAddress(Request $request): string
    {
        // Check for IP from cloudflare
        if ($request->header('CF-Connecting-IP')) {
            return $request->header('CF-Connecting-IP');
        }
        // Check for IP from nginx real IP
        elseif ($request->header('X-Real-IP')) {
            return $request->header('X-Real-IP');
        }
        // Check for IP from remote address
        elseif ($request->header('X-Forwarded-For')) {
            // Get first IP from comma separated list
            $forwardedFor = $request->header('X-Forwarded-For');
            return trim(explode(',', $forwardedFor)[0]);
        }
        // Check for shared internet/proxy
        elseif ($request->header('Client-IP')) {
            return $request->header('Client-IP');
        }
        // Return standard IP
        else {
            return $request->ip();
        }
    }

    /**
     * Resolve request signature.
     */
    protected function resolveRequestSignature(Request $request, string $ip): string
    {
        return sha1(
            $request->method() .
            '|' . $request->server('SERVER_NAME') .
            '|' . $ip
        );
    }

    /**
     * Create a 'too many attempts' exception.
     */
    protected function buildException(string $key, int $maxAttempts): Response
    {
        $retryAfter = $this->limiter->availableIn($key);

        return response()->json([
            'code' => 429,
            'message' => 'Terlalu banyak permintaan. Silakan coba lagi dalam ' . $retryAfter . ' detik.',
            'data' => null
        ], 429, [
            'Retry-After' => $retryAfter,
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => 0,
        ]);
    }

    /**
     * Add the limit header information to the given response.
     */
    protected function addHeaders($response, int $maxAttempts, int $remainingAttempts)
    {
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => max(0, $remainingAttempts),
        ]);

        return $response;
    }

    /**
     * Calculate the number of remaining attempts.
     */
    protected function calculateRemainingAttempts(string $key, int $maxAttempts): int
    {
        return $this->limiter->retriesLeft($key, $maxAttempts);
    }
}