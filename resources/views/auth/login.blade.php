@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <h4 class="fw-bold text-center text-body-emphasis mb-4">Welcome Back</h4>

    @if (session('status'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Employee ID -->
        <div class="mb-3">
            <label for="employee_id" class="form-label fw-semibold text-body-secondary">Employee ID</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-user-tie text-muted"></i></span>
                <input type="text" name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" value="{{ old('employee_id') }}" required autofocus placeholder="Enter your employee ID">
            </div>
            @error('employee_id')
                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold text-body-secondary">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-lock text-muted"></i></span>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Enter password">
            </div>
            @error('password')
                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label text-body-secondary" for="remember_me">Remember me</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none text-primary fw-medium small">Forgot password?</a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3">
            <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Log In
        </button>
    </form>
@endsection
