@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div style="max-width: 420px; margin: 2rem auto;">
        <div class="card">
            <div class="card-header">Forgot Password</div>
            <div class="card-body">
                @if (session('success'))
                    <div style="margin-bottom: 1rem; color: green">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div style="margin-bottom: 1rem; color: red;">{{ $errors->first() }}</div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" pattern="^[A-Za-z0-9_]+$" title="Enter username (no spaces)" class="form-control" value="{{ old('name') }}" oninput="this.value=this.value.replace(/\s/g,'')" autocomplete="username" autocapitalize="off" autocorrect="off" required autofocus>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Send Reset Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
