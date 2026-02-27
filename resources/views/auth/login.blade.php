@extends('layouts.guest')
@section('title', __('auth.login.title'))
@section('content')
<div class="auth-card">
    <h2>{{ __('auth.login.heading') }}</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">{{ __('auth.login.email') }}</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('auth.login.password') }}</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn-primary">{{ __('auth.login.submit') }}</button>
    </form>
</div>
<div class="auth-footer">
    {{ __('auth.login.no_account') }} <a href="{{ route('register') }}">{{ __('auth.login.create_one') }}</a>
</div>
@endsection
