<?php
namespace App\Services;

use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class AdminLoginSecurity
{
    public static function cache()
    {
        // File storage works without adding a cache table to existing installations.
        return Cache::store(app()->environment('testing') ? 'array' : 'file');
    }

    public static function limiter(): RateLimiter
    {
        return new RateLimiter(self::cache());
    }

    public static function guard(string $key, int $maximum = 5): void
    {
        if (self::limiter()->tooManyAttempts($key, $maximum)) {
            throw ValidationException::withMessages(['email' => 'Too many attempts. Please try again in '.self::limiter()->availableIn($key).' seconds.']);
        }
    }

    public static function key(string $purpose, string $identity, string $ip): string
    {
        return 'admin-auth:'.$purpose.':'.hash('sha256', strtolower(trim($identity)).'|'.$ip);
    }
}
