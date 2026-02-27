@extends('layouts.guest')
@section('title', __('auth.register.title'))
@section('content')
<div class="auth-card">
    <h2>{{ __('auth.register.heading') }}</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">{{ __('auth.register.name') }}</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('auth.register.email') }}</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('auth.register.password') }}</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('auth.register.confirm_password') }}</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn-primary">{{ __('auth.register.submit') }}</button>
    </form>
</div>
<div class="auth-footer">
    {{ __('auth.register.has_account') }} <a href="{{ route('login') }}">{{ __('auth.register.sign_in') }}</a>
</div>
@endsection
