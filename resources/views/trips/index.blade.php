@extends('layouts.app')
@section('title', __('trips.index.title'))
@section('content')
<div class="flex-between mb-3">
    <div>
        <h1 class="page-title">{{ __('trips.index.title') }} ✈</h1>
        <p class="page-subtitle">{{ __('trips.index.subtitle') }}</p>
    </div>
    <div class="flex gap-1">
        <button onclick="openModal('joinModal')" class="btn btn-outline">{{ __('trips.index.join_trip') }}</button>
        <a href="{{ route('trips.create') }}" class="btn btn-primary">{{ __('trips.index.new_trip') }}</a>
    </div>
</div>

@if($trips->isEmpty())
    <div class="card">
        <div class="empty-state">
            <span class="emoji">🗺️</span>
            <h3 style="font-family:'Playfair Display',serif;font-size:1.5rem;margin-bottom:0.5rem">{{ __('trips.index.no_trips') }}</h3>
            <p class="text-muted">{{ __('trips.index.no_trips_hint') }}</p>
            <div class="flex-center gap-1 mt-2" style="justify-content:center">
                <a href="{{ route('trips.create') }}" class="btn btn-primary">{{ __('trips.index.create_trip') }}</a>
                <button onclick="openModal('joinModal')" class="btn btn-outline">{{ __('trips.index.join_trip') }}</button>
            </div>
        </div>
    </div>
@else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem">
        @foreach($trips as $trip)
        @php $isPast = $trip->end_date?->lt(today()); @endphp
        <a href="{{ route('trips.show', $trip) }}" style="{{ $isPast ? 'opacity:0.55;filter:grayscale(0.4)' : '' }}">
            <div class="card" style="transition:transform 0.2s,box-shadow 0.2s" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 32px rgba(26,26,46,0.15)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                <div style="background:{{ $isPast ? 'linear-gradient(135deg,#5a5a6e,#3a3a4e)' : 'linear-gradient(135deg,var(--ink),#2d2d4e)' }};padding:1.5rem;position:relative;overflow:hidden">
                    <div style="position:absolute;right:-20px;top:-20px;font-size:5rem;opacity:0.08">✈</div>
                    <h3 style="font-family:'Playfair Display',serif;font-size:1.25rem;color:white;margin-bottom:0.25rem">{{ $trip->name }}</h3>
                    @if($trip->start_date)
                        <p style="color:var(--gold-light);font-size:0.8rem">
                            {{ $trip->start_date->translatedFormat('M d') }} — {{ $trip->end_date?->translatedFormat('M d, Y') ?? '?' }}
                        </p>
                        <p style="color:var(--cream);font-size:0.8rem">
                            @if($isPast) 🏁 {{ __('trips.index.trip_completed') }} @else ⏰ {{ __('trips.index.in_days', ['count' => $trip->getTimeUntilTrip()]) }} @endif
                        </p>
                    @endif
                </div>
                <div style="padding:1.25rem">
                    @if($trip->description)
                        <p class="text-sm text-muted mb-2">{{ Str::limit($trip->description, 80) }}</p>
                    @endif
                    <div class="flex-between">
                        <div class="flex" style="align-items:center">
                            @foreach($trip->members->take(5) as $member)
                                <div class="avatar" title="{{ $member->name }}" style="background:{{ $member->avatar_color }};width:28px;height:28px;font-size:0.65rem;margin-left:{{ $loop->first ? '0' : '-6px' }};border:2px solid white">{{ $member->initials() }}</div>
                            @endforeach
                        </div>
                        <span class="badge {{ $trip->created_by === auth()->id() ? 'badge-gold' : 'badge-blue' }}">
                            {{ $trip->created_by === auth()->id() ? __('general.label.owner') : __('general.label.member') }}
                        </span>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
@endif

<div id="joinModal" class="modal-backdrop">
    <div class="modal">
        <div class="modal-header">
            <h3>{{ __('trips.join.title') }}</h3>
            <button class="modal-close" onclick="closeModal('joinModal')">×</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('trips.join') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">{{ __('trips.join.invite_code') }}</label>
                    <input type="text" name="invite_code" class="form-control" placeholder="{{ __('trips.join.placeholder') }}" style="text-transform:uppercase;letter-spacing:0.1em;font-size:1.1rem" required>
                    <p class="text-sm text-muted mt-1">{{ __('trips.join.hint') }}</p>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('trips.index.join_trip') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
