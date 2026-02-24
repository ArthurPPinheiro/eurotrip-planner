@extends('layouts.app')
@section('title', 'My Trips')
@section('content')
<div class="flex-between mb-3">
    <div>
        <h1 class="page-title">My Trips ✈</h1>
        <p class="page-subtitle">Plan and explore Europe together</p>
    </div>
    <div class="flex gap-1">
        <button onclick="openModal('joinModal')" class="btn btn-outline">Join Trip</button>
        <a href="{{ route('trips.create') }}" class="btn btn-primary">+ New Trip</a>
    </div>
</div>

@if($trips->isEmpty())
    <div class="card">
        <div class="empty-state">
            <span class="emoji">🗺️</span>
            <h3 style="font-family:'Playfair Display',serif;font-size:1.5rem;margin-bottom:0.5rem">No trips yet</h3>
            <p class="text-muted">Create your first trip or join one with an invite code</p>
            <div class="flex-center gap-1 mt-2" style="justify-content:center">
                <a href="{{ route('trips.create') }}" class="btn btn-primary">Create Trip</a>
                <button onclick="openModal('joinModal')" class="btn btn-outline">Join Trip</button>
            </div>
        </div>
    </div>
@else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem">
        @foreach($trips as $trip)
        <a href="{{ route('trips.show', $trip) }}">
            <div class="card" style="transition:transform 0.2s,box-shadow 0.2s" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 32px rgba(26,26,46,0.15)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                <div style="background:linear-gradient(135deg,var(--ink),#2d2d4e);padding:1.5rem;position:relative;overflow:hidden">
                    <div style="position:absolute;right:-20px;top:-20px;font-size:5rem;opacity:0.08">✈</div>
                    <h3 style="font-family:'Playfair Display',serif;font-size:1.25rem;color:white;margin-bottom:0.25rem">{{ $trip->name }}</h3>
                    @if($trip->start_date)
                        <p style="color:var(--gold-light);font-size:0.8rem">
                            {{ $trip->start_date->format('M d') }} — {{ $trip->end_date?->format('M d, Y') ?? '?' }}
                        </p>
                        <p style="color:var(--cream);font-size:0.8rem">
                            ⏰ In {{ $trip->getTimeUntilTrip() }} Days
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
                            {{ $trip->created_by === auth()->id() ? 'Owner' : 'Member' }}
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
            <h3>Join a Trip</h3>
            <button class="modal-close" onclick="closeModal('joinModal')">×</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('trips.join') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Invite Code</label>
                    <input type="text" name="invite_code" class="form-control" placeholder="e.g. ABCD1234" style="text-transform:uppercase;letter-spacing:0.1em;font-size:1.1rem" required>
                    <p class="text-sm text-muted mt-1">Ask your trip organizer for the 8-character invite code.</p>
                </div>
                <button type="submit" class="btn btn-primary">Join Trip</button>
            </form>
        </div>
    </div>
</div>
@endsection
