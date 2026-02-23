@extends('layouts.app')
@section('title', 'Edit Trip')
@section('content')
<div style="max-width:600px;margin:0 auto">
    <a href="{{ route('trips.show', $trip) }}" class="text-muted text-sm">← Back to trip</a>
    <h1 class="page-title mt-2">Edit Trip</h1>
    <div class="card mt-2">
        <div class="card-body">
            <form method="POST" action="{{ route('trips.update', $trip) }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">Trip Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $trip->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control">{{ old('description', $trip->description) }}</textarea>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $trip->start_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $trip->end_date?->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="flex gap-1">
                    <a href="{{ route('trips.show', $trip) }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
            <hr class="divider">
            <h3 style="font-size:1rem;font-weight:600;color:var(--danger);margin-bottom:0.75rem">Danger Zone</h3>
            <form method="POST" action="{{ route('trips.destroy', $trip) }}" onsubmit="return confirm('Delete this trip permanently?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete Trip</button>
            </form>
        </div>
    </div>
</div>
@endsection
