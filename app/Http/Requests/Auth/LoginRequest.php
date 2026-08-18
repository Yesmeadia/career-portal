<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Rate limit configuration
     *
     * - Per email+IP : max 5 attempts  → decays in 5 minutes
     * - Per IP only  : max 15 attempts → decays in 10 minutes  (brute-force guard)
     * - Global wide  : max 60 attempts → decays in 1 minute    (server-level throttle)
     */
    protected const MAX_PER_EMAIL_IP = 5;
    protected const DECAY_PER_EMAIL_IP = 300;   // 5 minutes

    protected const MAX_PER_IP = 15;
    protected const DECAY_PER_IP = 600;          // 10 minutes

    protected const MAX_GLOBAL = 60;
    protected const DECAY_GLOBAL = 60;           // 1 minute

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt authentication, incrementing all throttle buckets on failure
     * and clearing them all on success.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Increment all three throttle buckets
            RateLimiter::hit($this->throttleKeyEmailIp(), self::DECAY_PER_EMAIL_IP);
            RateLimiter::hit($this->throttleKeyIp(),      self::DECAY_PER_IP);
            RateLimiter::hit($this->throttleKeyGlobal(),  self::DECAY_GLOBAL);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Clear per-email+IP and per-IP buckets on successful login
        RateLimiter::clear($this->throttleKeyEmailIp());
        RateLimiter::clear($this->throttleKeyIp());
    }

    /**
     * Throw if any throttle bucket is exhausted.
     */
    public function ensureIsNotRateLimited(): void
    {
        // 1. Check per-email+IP bucket (most specific, fires first)
        if (RateLimiter::tooManyAttempts($this->throttleKeyEmailIp(), self::MAX_PER_EMAIL_IP)) {
            event(new Lockout($this));
            $seconds = RateLimiter::availableIn($this->throttleKeyEmailIp());
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // 2. Check per-IP bucket (catches credential stuffing from one IP)
        if (RateLimiter::tooManyAttempts($this->throttleKeyIp(), self::MAX_PER_IP)) {
            event(new Lockout($this));
            $seconds = RateLimiter::availableIn($this->throttleKeyIp());
            throw ValidationException::withMessages([
                'email' => __('Too many login attempts from your IP address. Please wait :minutes minute(s) before trying again.', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // 3. Check global bucket (protects the server during mass attacks)
        if (RateLimiter::tooManyAttempts($this->throttleKeyGlobal(), self::MAX_GLOBAL)) {
            throw ValidationException::withMessages([
                'email' => __('The login service is temporarily unavailable due to high traffic. Please try again in a moment.'),
            ]);
        }
    }

    // ─── Throttle Keys ───────────────────────────────────────────────────────

    /**
     * Per email + IP — targets a specific credential pair.
     * e.g. "login|email|user@example.com|192.168.1.1"
     */
    public function throttleKeyEmailIp(): string
    {
        return 'login|email|' . Str::lower($this->string('email')) . '|' . $this->ip();
    }

    /**
     * Per IP only — catches an attacker cycling through many usernames.
     * e.g. "login|ip|192.168.1.1"
     */
    public function throttleKeyIp(): string
    {
        return 'login|ip|' . $this->ip();
    }

    /**
     * Global server-wide bucket.
     * e.g. "login|global"
     */
    public function throttleKeyGlobal(): string
    {
        return 'login|global';
    }

    /**
     * Legacy alias kept so callers that reference throttleKey() still work.
     */
    public function throttleKey(): string
    {
        return $this->throttleKeyEmailIp();
    }
}
