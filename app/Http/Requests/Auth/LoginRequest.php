<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => ['required'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $query = \App\Models\Employee::query();

        if (is_numeric($this->employee_id)) {
            $query->where('employee_id', $this->employee_id)
                  ->orWhere('username', $this->employee_id)
                  ->orWhere('email', $this->employee_id);
        } else {
            $query->where('username', $this->employee_id)
                  ->orWhere('email', $this->employee_id);
        }

        $employee = $query->first();

        $authenticated = false;

        if ($employee) {
            $isMd5 = (strlen($employee->password) === 32 && !str_starts_with($employee->password, '$'));
            $passwordMatches = $isMd5 
                ? md5($this->password) === $employee->password 
                : \Illuminate\Support\Facades\Hash::check($this->password, $employee->password);

            if ($passwordMatches) {
                Auth::login($employee, $this->boolean('remember'));
                $authenticated = true;
            }
        }

        if (! $authenticated) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'employee_id' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'employee_id' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('employee_id')).'|'.$this->ip());
    }
}
