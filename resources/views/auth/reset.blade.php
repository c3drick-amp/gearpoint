@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div style="max-width: 420px; margin: 2rem auto;">
        <div class="card">
            <div class="card-header">Reset Password</div>
            <div class="card-body">
                @if ($errors->any())
                    <div style="margin-bottom: 1rem; color: red;">{{ $errors->first() }}</div>
                @endif
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" autocomplete="email" required autofocus readonly>
                        @if(!empty($email))
                            <div style="font-size: .9rem; color:#555; margin-top: .5rem;">Resetting password for: <strong>{{ $email }}</strong></div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
