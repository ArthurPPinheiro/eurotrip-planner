@extends('layouts.app')
@section('title', __('trips.edit.title'))
@section('content')
<div style="max-width:600px;margin:0 auto">
    <a href="{{ route('trips.show', $trip) }}" class="text-muted text-sm">{{ __('trips.edit.back') }}</a>
    <h1 class="page-title mt-2">{{ __('trips.edit.title') }}</h1>
    <div class="card mt-2">
        <div class="card-body">
            <form method="POST" action="{{ route('trips.update', $trip) }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">{{ __('trips.create.trip_name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $trip->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('general.label.description') }}</label>
                    <textarea name="description" class="form-control">{{ old('description', $trip->description) }}</textarea>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">{{ __('trips.create.start_date') }}</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $trip->start_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('trips.create.end_date') }}</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $trip->end_date?->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="flex gap-1">
                    <a href="{{ route('trips.show', $trip) }}" class="btn btn-outline">{{ __('general.btn.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('general.btn.save_changes') }}</button>
                </div>
            </form>
            <hr class="divider">
            <h3 style="font-size:1rem;font-weight:600;color:var(--danger);margin-bottom:0.75rem">{{ __('general.danger_zone') }}</h3>
            <form method="POST" action="{{ route('trips.destroy', $trip) }}" data-confirm="{{ __('trips.edit.confirm_delete') }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">{{ __('trips.edit.delete_trip') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
