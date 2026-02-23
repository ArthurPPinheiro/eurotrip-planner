@extends('layouts.guest')
@section('title', 'Login')
@section('content')
<div class="auth-card">
    <h2>Welcome back</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn-primary">Sign In</button>
    </form>
</div>
<div class="auth-footer">
    No account yet? <a href="{{ route('register') }}">Create one</a>
</div>
@endsection
