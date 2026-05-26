@extends('layouts.app')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-card-header">
            <div>
                <div class="page-title">School Admin Login</div>
                <p class="text-muted" style="margin-top:6px;">Login to access the school finance dashboard.</p>
            </div>
        </div>

        <div class="auth-card-body">
            @if($errors->any())
                <div class="mb-4 rounded border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required maxlength="254" autocomplete="email" placeholder="admin@example.com" class="form-control" />
                </div>

                <div class="form-group password-group">
                    <label class="form-label">Password</label>
                    <input id="passwordInput" type="password" name="password" required maxlength="128" autocomplete="current-password" placeholder="Enter your password" class="form-control" />
                    <button type="button" id="togglePassword" class="btn btn-outline btn-sm password-toggle">Show</button>
                </div>

                <div class="auth-actions">
                    <label class="auth-checkbox">
                        <input type="checkbox" name="remember" />
                        Remember me
                    </label>
                    <a href="#" class="auth-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-full">Login</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('passwordInput');
        const toggleButton = document.getElementById('togglePassword');

        if (!passwordInput || !toggleButton) {
            return;
        }

        toggleButton.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleButton.textContent = isPassword ? 'Hide' : 'Show';
        });
    });
</script>
</div>
@endsection
