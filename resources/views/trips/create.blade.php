@extends('layouts.app')
@section('title', __('trips.create.title'))
@section('content')
<div style="max-width:600px;margin:0 auto">
    <a href="{{ route('trips.index') }}" class="text-muted text-sm">{{ __('trips.create.back') }}</a>
    <h1 class="page-title mt-2">{{ __('trips.create.title') }}</h1>
    <p class="page-subtitle mb-3">{{ __('trips.create.subtitle') }}</p>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('trips.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">{{ __('trips.create.trip_name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="{{ __('trips.create.name_placeholder') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('general.label.description') }}</label>
                    <textarea name="description" class="form-control" placeholder="{{ __('trips.create.description_placeholder') }}">{{ old('description') }}</textarea>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">{{ __('trips.create.start_date') }}</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('trips.create.end_date') }}</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                    </div>
                </div>
                <p class="text-sm text-muted mb-3">{{ __('trips.create.dates_hint') }}</p>
                <div class="flex gap-1">
                    <a href="{{ route('trips.index') }}" class="btn btn-outline">{{ __('general.btn.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('trips.create.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
