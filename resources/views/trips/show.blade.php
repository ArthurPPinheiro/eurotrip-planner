@extends('layouts.app')
@section('title', $trip->name)
@section('content')

<div style="background:linear-gradient(135deg,var(--ink),#2d2d4e);border-radius:16px;padding:2rem 2.5rem;margin-bottom:2rem;position:relative;overflow:hidden;color:white">
    <div style="position:absolute;right:-10px;top:50%;transform:translateY(-50%);font-size:8rem;opacity:0.06">✈</div>
    <div class="flex-between">
        <div>
            <h1 style="font-family:'Playfair Display',serif;font-size:2rem;margin-bottom:0.4rem">{{ $trip->name }}</h1>
            @if($trip->description)<p style="opacity:0.7;font-size:0.9rem">{{ $trip->description }}</p>@endif
            <div class="flex gap-2 mt-2" style="font-size:0.85rem;opacity:0.8;flex-wrap:wrap">
                @if($trip->start_date)<span>📅 {{ $trip->start_date->format('M d') }} – {{ $trip->end_date?->format('M d, Y') ?? '?' }}</span>@endif
                <span>🗓️ {{ $trip->days->count() }} days planned</span>
                <span>👥 {{ $trip->members->count() }} travellers</span>
                <span>⏰ In {{ $trip->getTimeUntilTrip() }} days</span>

            </div>
        </div>
        <div class="flex gap-1" style="align-items:flex-start">
            <a href="{{ route('trips.edit', $trip) }}" class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:white;border:1px solid rgba(255,255,255,0.25)">✏️ Edit</a>
            <a href="{{ route('trips.index') }}" class="btn btn-sm btn-ghost" style="color:rgba(255,255,255,0.6)">← Back</a>
        </div>
    </div>
    <div class="flex-between mt-3" style="align-items:center">
        <div class="flex gap-1">
            @foreach($trip->members as $m)
                <div class="avatar" title="{{ $m->name }}" style="background:{{ $m->avatar_color }};width:30px;height:30px;font-size:0.65rem;border:2px solid rgba(255,255,255,0.3)">{{ $m->initials() }}</div>
            @endforeach
        </div>
        <div style="background:rgba(255,255,255,0.1);border-radius:8px;padding:0.4rem 0.875rem;font-size:0.8rem;color:rgba(255,255,255,0.8)">
            Invite code: <strong style="letter-spacing:0.1em;color:var(--gold-light)">{{ $trip->invite_code }}</strong>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="flex gap-1 mb-3">
    <a href="{{ route('trips.show', $trip) }}" class="btn btn-primary btn-sm">🗺️ Itinerary</a>
    <a href="{{ route('documents.index', $trip) }}" class="btn btn-outline btn-sm">📂 Documents</a>

    <!-- Add Day -->
    <form method="POST" action="{{ route('trips.addDay', $trip) }}">
        @csrf
        <button type="submit" class="btn btn-gold">+ Add Day</button>
    </form>
</div>



@push('styles')
<style>
.accordion-day { border-radius: 12px; overflow: hidden; box-shadow: var(--shadow); margin-bottom: 0.75rem; }
.accordion-trigger {
    width: 100%; background: linear-gradient(90deg, var(--ink), #2d2d4e);
    color: white; border: none; padding: 1rem 1.5rem;
    display: flex; align-items: center; justify-content: space-between;
    cursor: pointer; font-family: inherit; text-align: left; gap: 1rem;
    transition: background 0.2s;
}
.accordion-trigger:hover { background: linear-gradient(90deg, #2d2d4e, #3a3a6e); }
.accordion-trigger.open { background: linear-gradient(90deg, #2d2d4e, #3a3a6e); }
.accordion-chevron {
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(255,255,255,0.15); display: flex; align-items: center;
    justify-content: center; font-size: 0.7rem; flex-shrink: 0;
    transition: transform 0.3s ease;
}
.accordion-trigger.open .accordion-chevron { transform: rotate(180deg); }
.accordion-body {
    background: white; max-height: 0; overflow: hidden;
    transition: max-height 0.35s ease, padding 0.2s;
}
.accordion-body.open { max-height: 9999px; }
.accordion-inner { padding: 1.25rem; }
.day-summary-pills { display: flex; gap: 0.4rem; flex-wrap: wrap; }
.day-pill { background: rgba(255,255,255,0.12); border-radius: 20px; padding: 0.15rem 0.6rem; font-size: 0.75rem; }
.day-delete-btn { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: rgba(255,255,255,0.55); border-radius: 6px; padding: 0.25rem 0.55rem; cursor: pointer; font-size: 0.8rem; line-height: 1; transition: all 0.2s; }
.day-delete-btn:hover { background: rgba(193,68,14,0.6); border-color: transparent; color: white; }
.day-delete-btn {
    background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.7); border-radius: 6px; padding: 0.2rem 0.55rem;
    cursor: pointer; font-size: 0.8rem; line-height: 1; transition: all 0.2s; font-family: inherit;
}
.day-delete-btn:hover { background: rgba(193,68,14,0.65); border-color: rgba(193,68,14,0.9); color: white; }
/* Route feature */
.route-card { border: 1.5px solid var(--cream); border-radius: 10px; padding: 1rem; margin-bottom: 1rem; background: #fafafa; }
.route-summary-bar { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 0.6rem; }
.route-map { height: 260px; border-radius: 8px; overflow: hidden; border: 1px solid var(--cream); isolation: isolate; }
.route-map-preview { height: 200px; border-radius: 8px; overflow: hidden; border: 1px solid var(--cream); margin-bottom: 0.5rem; isolation: isolate; }
.transport-btn { padding: 0.4rem 0.875rem; border-radius: 20px; border: 1.5px solid var(--cream); background: white; cursor: pointer; font-family: inherit; font-size: 0.85rem; transition: all 0.2s; }
.transport-btn.active { background: var(--ink); color: white; border-color: var(--ink); }
.transport-btn:hover:not(.active) { border-color: var(--gold); }
.stop-row { background: white; border: 1px solid var(--cream); border-radius: 8px; padding: 0.5rem 0.75rem; }
/* Flight feature */
.flight-card { border: 1.5px solid #dbeafe; border-radius: 10px; overflow: hidden; margin-bottom: 0.75rem; background: white; }
.flight-card-header { background: linear-gradient(90deg, #1e3a5f, #1e40af); color: white; padding: 0.75rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
.flight-route { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; border-bottom: 1px solid #eff6ff; }
.flight-airport-block { text-align: center; min-width: 70px; }
.flight-airport-code { font-size: 1.6rem; font-weight: 700; color: var(--ink); letter-spacing: 0.05em; line-height: 1; }
.flight-airport-city { font-size: 0.72rem; color: var(--muted); margin-top: 0.2rem; }
.flight-time { font-size: 0.85rem; font-weight: 600; color: var(--ink); margin-top: 0.35rem; }
.flight-line { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 0.25rem; }
.flight-line-bar { width: 100%; height: 2px; background: linear-gradient(90deg, #1e40af, #93c5fd); border-radius: 2px; position: relative; }
.flight-line-bar::before, .flight-line-bar::after { content: ''; position: absolute; top: 50%; transform: translateY(-50%); width: 6px; height: 6px; border-radius: 50%; background: #1e40af; }
.flight-line-bar::before { left: -3px; }
.flight-line-bar::after { right: -3px; }
.flight-duration-label { font-size: 0.72rem; color: var(--muted); }
.flight-details { padding: 0.75rem 1.25rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
</style>
@endpush

<!-- Days -->
@if($trip->days->isEmpty())
    <div class="card">
        <div class="empty-state">
            <span class="emoji">🗓️</span>
            <p>No days yet — click "Add Day" to start building your itinerary!</p>
        </div>
    </div>
@else
    <div style="display:flex;flex-direction:column;gap:0.75rem">
        @foreach($trip->days as $day)
        @php $dayIsPast = $day->date->lt(today()); @endphp
        <div class="accordion-day" style="{{ $dayIsPast ? 'opacity:0.55;filter:grayscale(0.35)' : '' }}">

            {{-- Accordion Trigger --}}
            <span class="accordion-trigger {{ $loop->first ? 'open' : '' }}" onclick="toggleAccordion({{ $day->id }})">
                <div style="flex:1;min-width:0">
                    <div style="font-family:'Playfair Display',serif;font-size:1.05rem;margin-bottom:0.25rem">
                        Day {{ $day->day_number }}
                        @if($day->title) — {{ $day->title }}@endif
                    </div>
                    <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap">
                        <span style="font-size:0.78rem;opacity:0.65">{{ $day->date->format('l, M d, Y') }}{{ $dayIsPast ? ' · 🏁' : '' }}</span>
                        @if($day->flights->count() || $day->destinations->count())
                            <div class="day-summary-pills">
                                @foreach($day->flights as $fl)
                                    <span class="day-pill">✈ {{ $fl->departure_airport }} → {{ $fl->arrival_airport }}</span>
                                @endforeach
                                @foreach($day->destinations as $d)
                                    <span class="day-pill">{{ $d->emoji }} {{ $d->city }}</span>
                                @endforeach
                            </div>
                        @else
                            <span style="font-size:0.78rem;opacity:0.45;font-style:italic">No cities yet</span>
                        @endif
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem;flex-shrink:0">
                    @if($day->route?->total_distance_km)
                        <span style="font-size:0.75rem;background:rgba(255,255,255,0.15);border-radius:20px;padding:0.15rem 0.55rem;white-space:nowrap">
                            {{ ['car'=>'🚗','bus'=>'🚌','train'=>'🚂'][$day->route->transport_mode] ?? '🗺️' }} {{ number_format($day->route->total_distance_km, 1) }} km
                        </span>
                    @endif
                    @if($day->destinations->flatMap->activities->count())
                        <span style="font-size:0.75rem;opacity:0.6">{{ $day->destinations->flatMap->activities->count() }} items</span>
                    @endif
                    <form method="POST" action="{{ route('days.destroy', $day) }}"
                          onsubmit="event.stopPropagation(); return confirm('Delete Day {{ $day->day_number }} and all its cities?')"
                          onclick="event.stopPropagation()"
                          class="day-delete-form">
                        @csrf @method('DELETE')
                        <button type="submit" title="Delete day" class="day-delete-btn">✕</button>
                    </form>
                    <span class="accordion-chevron">▼</span>
                </div>
            </span>

            {{-- Accordion Body --}}
            <div id="accordion-body-{{ $day->id }}" class="accordion-body {{ $loop->first ? 'open' : '' }}">
                <div class="accordion-inner">

                    {{-- Route Section --}}
                    @if($day->route)
                        @php
                            $routeStops = $day->route->stops->map(fn($s) => [
                                'city' => $s->city, 'country' => $s->country,
                                'latitude' => (float)$s->latitude, 'longitude' => (float)$s->longitude
                            ])->toArray();
                            $rh = intdiv($day->route->total_duration_minutes ?? 0, 60);
                            $rm = ($day->route->total_duration_minutes ?? 0) % 60;
                            $modeIcon = ['car'=>'🚗','bus'=>'🚌','train'=>'🚂'][$day->route->transport_mode] ?? '🚗';
                            $modeLabel = ['car'=>'Car','bus'=>'Bus','train'=>'Train'][$day->route->transport_mode] ?? 'Car';
                        @endphp
                        <div class="route-card">
                            <div class="route-summary-bar">
                                <span style="font-weight:600">{{ $modeIcon }} {{ $modeLabel }}</span>
                                @if($day->route->total_distance_km)
                                    <span class="badge badge-blue">📏 {{ number_format($day->route->total_distance_km, 1) }} km</span>
                                @endif
                                @if($day->route->total_duration_minutes)
                                    <span class="badge badge-gold">⏱ {{ $rh > 0 ? $rh.'h '.$rm.'min' : $rm.'min' }}</span>
                                @endif
                            </div>
                            <div style="display:flex;align-items:center;gap:0.3rem;flex-wrap:wrap;margin-bottom:0.75rem;font-size:0.85rem;color:var(--muted)">
                                @foreach($day->route->stops as $stop)
                                    @if(!$loop->first)<span style="opacity:0.5">→</span>@endif
                                    <span>{{ $stop->city }}</span>
                                @endforeach
                            </div>
                            <div class="route-map"
                                 id="route-map-{{ $day->id }}"
                                 data-day-id="{{ $day->id }}"
                                 data-stops="{{ json_encode($routeStops) }}"
                                 data-mode="{{ $day->route->transport_mode }}"></div>
                            <div class="flex gap-1" style="margin-top:0.75rem">
                                <button
                                    data-day="{{ $day->id }}"
                                    data-stops="{{ json_encode($routeStops) }}"
                                    data-mode="{{ $day->route->transport_mode }}"
                                    onclick="openRouteModalFromEl(this)"
                                    class="btn btn-sm btn-outline">✏️ Edit Route</button>
                                <form method="POST" action="{{ route('routes.destroy', $day->route) }}" onsubmit="return confirm('Remove this route?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-ghost" style="color:var(--danger)">✕ Remove Route</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div style="margin-bottom:1rem">
                            <button
                                data-day="{{ $day->id }}"
                                onclick="openRouteModalFromEl(this)"
                                class="btn btn-sm btn-outline">🗺️ Add Route</button>
                        </div>
                    @endif

                    {{-- Flights Section --}}
                    @foreach($day->flights as $flight)
                        @php
                            $cabinLabels = ['economy'=>'Economy','premium_economy'=>'Premium Economy','business'=>'Business','first'=>'First Class'];
                            $flDurH = intdiv($flight->duration_minutes ?? 0, 60);
                            $flDurM = ($flight->duration_minutes ?? 0) % 60;
                        @endphp
                        <div class="flight-card">
                            <div class="flight-card-header">
                                <div class="flex gap-1" style="align-items:center;flex-wrap:wrap">
                                    <span style="font-size:1.1rem">✈</span>
                                    @if($flight->flight_number)<span style="font-weight:700;letter-spacing:0.05em">{{ $flight->flight_number }}</span>@endif
                                    @if($flight->airline)<span style="opacity:0.8;font-size:0.85rem">{{ $flight->airline }}</span>@endif
                                </div>
                                <div class="flex gap-1">
                                    <button
                                        onclick="openFlightModal({{ $day->id }}, {{ $flight->id }})"
                                        class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:white;border:1px solid rgba(255,255,255,0.25)">✏️ Edit</button>
                                    <form method="POST" action="{{ route('flights.destroy', $flight) }}" onsubmit="return confirm('Remove this flight?')" onclick="event.stopPropagation()">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm" style="background:rgba(193,68,14,0.3);color:white;border:1px solid rgba(193,68,14,0.5)">✕</button>
                                    </form>
                                </div>
                            </div>
                            <div class="flight-route">
                                <div class="flight-airport-block">
                                    <div class="flight-airport-code">{{ strtoupper($flight->departure_airport) }}</div>
                                    @if($flight->departure_city)<div class="flight-airport-city">{{ $flight->departure_city }}</div>@endif
                                    @if($flight->departure_time)<div class="flight-time">{{ $flight->departure_time }}</div>@endif
                                </div>
                                <div class="flight-line">
                                    @if($flight->duration_minutes)
                                        <div class="flight-duration-label">{{ $flDurH > 0 ? $flDurH.'h '.$flDurM.'min' : $flDurM.'min' }}</div>
                                    @endif
                                    <div class="flight-line-bar"></div>
                                </div>
                                <div class="flight-airport-block">
                                    <div class="flight-airport-code">{{ strtoupper($flight->arrival_airport) }}</div>
                                    @if($flight->arrival_city)<div class="flight-airport-city">{{ $flight->arrival_city }}</div>@endif
                                    @if($flight->arrival_time)<div class="flight-time">{{ $flight->arrival_time }}</div>@endif
                                </div>
                            </div>
                            <div class="flight-details">
                                @if($flight->locator)
                                    <span class="badge badge-blue">🎫 {{ strtoupper($flight->locator) }}</span>
                                @endif
                                @if($flight->seat)
                                    <span class="badge badge-purple">💺 {{ $flight->seat }}</span>
                                @endif
                                @if($flight->cabin_class)
                                    <span class="badge badge-gold">{{ $cabinLabels[$flight->cabin_class] ?? $flight->cabin_class }}</span>
                                @endif
                                @if($flight->notes)
                                    <span class="text-sm text-muted">📝 {{ $flight->notes }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{-- Add City / Add Flight buttons --}}
                    <div style="display:flex;justify-content:flex-end;gap:0.5rem;margin-bottom:1rem">
                        <button onclick="openFlightModal({{ $day->id }})" class="btn btn-sm btn-outline" style="border-color:#1e40af;color:#1e40af">✈ Add Flight</button>
                        <button onclick="openModal('dest-modal-{{ $day->id }}')" class="btn btn-sm btn-gold">+ Add City</button>
                    </div>

                    @if($day->destinations->isEmpty())
                        <div style="text-align:center;padding:1.5rem 0;color:var(--muted);font-size:0.9rem">
                            No cities added yet for this day.
                        </div>
                    @else
                        <div style="display:flex;flex-direction:column;gap:1rem">
                            @foreach($day->destinations as $dest)
                            <div style="border:1.5px solid var(--cream);border-radius:10px;overflow:hidden">
                                <div style="background:var(--cream);padding:0.75rem 1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem">
                                    <div class="flex gap-1" style="align-items:center">
                                        <span style="font-size:1.8rem">{{ $dest->emoji }}</span>
                                        <div>
                                            <div style="font-weight:600;font-size:1rem">{{ $dest->city }}</div>
                                            <div class="text-sm text-muted">{{ $dest->country }}</div>
                                        </div>
                                    </div>
                                    <div class="flex gap-1">
                                        <button onclick="openModal('act-modal-{{ $dest->id }}')" class="btn btn-sm btn-primary">+ Add Item</button>
                                        <form method="POST" action="{{ route('destinations.destroy', $dest) }}" onsubmit="return confirm('Remove {{ $dest->city }}?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-ghost" style="color:var(--danger)">✕</button>
                                        </form>
                                    </div>
                                </div>

                                @if($dest->activities->count())
                                <div style="padding:0.75rem 1.25rem;display:flex;flex-direction:column;gap:0.25rem">
                                    @foreach($dest->activities as $act)
                                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;padding:0.6rem 0;border-bottom:1px solid var(--cream)">
                                        <div class="flex gap-1" style="align-items:flex-start;flex:1">
                                            <span style="font-size:1.1rem;flex-shrink:0;margin-top:2px">{{ $act->typeIcon() }}</span>
                                            <div style="flex:1">
                                                <div style="font-weight:500;font-size:0.9rem">{{ $act->title }}</div>
                                                @if($act->description)<div class="text-sm text-muted">{{ $act->description }}</div>@endif
                                                <div class="flex gap-1 mt-1" style="flex-wrap:wrap;align-items:center">
                                                    @if($act->time)<span class="badge badge-blue">🕐 {{ $act->time }}</span>@endif
                                                    @if($act->address)<span class="text-sm text-muted">📍 {{ $act->address }}</span>@endif
                                                    @if($act->price)<span class="badge badge-green">{{ $act->currency }} {{ number_format($act->price, 2) }}</span>@endif
                                                    @if($act->link)<a href="{{ $act->link }}" target="_blank" class="text-sm" style="color:var(--accent)">🔗 Link</a>@endif
                                                </div>
                                                <div class="text-sm text-muted mt-1">Added by {{ $act->author->name }}</div>
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('activities.destroy', $act) }}" onsubmit="return confirm('Remove this?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-ghost" style="color:var(--danger);padding:0.2rem 0.5rem">✕</button>
                                        </form>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                    <div style="padding:0.875rem 1.25rem;font-size:0.85rem;color:var(--muted);font-style:italic">No items yet — add a hotel, POI, or note.</div>
                                @endif
                            </div>

                            {{-- Activity Modal --}}
                            <div id="act-modal-{{ $dest->id }}" class="modal-backdrop">
                                <div class="modal">
                                    <div class="modal-header">
                                        <h3>Add to {{ $dest->city }}</h3>
                                        <button class="modal-close" onclick="closeModal('act-modal-{{ $dest->id }}')">×</button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="{{ route('activities.store', $dest) }}">
                                            @csrf
                                            <div class="form-group">
                                                <label class="form-label">Type</label>
                                                <select name="type" class="form-control" required>
                                                    <option value="poi">📍 Point of Interest</option>
                                                    <option value="hotel">🏨 Hotel / Accommodation</option>
                                                    <option value="reservation">🎟️ Reservation</option>
                                                    <option value="comment">💬 Comment / Note</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Title *</label>
                                                <input type="text" name="title" class="form-control" required placeholder="e.g. Eiffel Tower">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" placeholder="Any details..."></textarea>
                                            </div>
                                            <div class="grid-2">
                                                <div class="form-group">
                                                    <label class="form-label">Time</label>
                                                    <input type="text" name="time" class="form-control" placeholder="e.g. 10:00 AM">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Price</label>
                                                    <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Address</label>
                                                <input type="text" name="address" class="form-control" placeholder="Street, City...">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Link / URL</label>
                                                <input type="url" name="link" class="form-control" placeholder="https://...">
                                            </div>
                                            <input type="hidden" name="currency" value="EUR">
                                            <div class="flex gap-1">
                                                <button type="button" onclick="closeModal('act-modal-{{ $dest->id }}')" class="btn btn-outline">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Add Item</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Add Destination Modal --}}
        <div id="dest-modal-{{ $day->id }}" class="modal-backdrop">
            <div class="modal">
                <div class="modal-header">
                    <h3>Add City — Day {{ $day->day_number }}</h3>
                    <button class="modal-close" onclick="closeModal('dest-modal-{{ $day->id }}')">×</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('destinations.store', $day) }}">
                        @csrf
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">City *</label>
                                <input type="text" name="city" class="form-control" required placeholder="Paris">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Country *</label>
                                <input type="text" name="country" class="form-control" required placeholder="France">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Emoji Flag</label>
                            <input type="text" name="emoji" class="form-control" placeholder="🇫🇷" style="font-size:1.4rem;width:80px;text-align:center">
                        </div>
                        <div class="flex gap-1">
                            <button type="button" onclick="closeModal('dest-modal-{{ $day->id }}')" class="btn btn-outline">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add City</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Route Modal --}}
        <div id="route-modal-{{ $day->id }}" class="modal-backdrop">
            <div class="modal" style="max-width:640px">
                <div class="modal-header">
                    <h3>Route — Day {{ $day->day_number }}</h3>
                    <button class="modal-close" onclick="closeModal('route-modal-{{ $day->id }}')">×</button>
                </div>
                <div class="modal-body">
                    <form method="POST"
                          id="route-form-{{ $day->id }}"
                          action="{{ $day->route ? route('routes.update', $day->route) : route('routes.store', $day) }}">
                        @csrf
                        @if($day->route) @method('PUT') @endif

                        {{-- Transport mode --}}
                        <div class="form-group">
                            <label class="form-label">Transport Mode</label>
                            <div class="flex gap-1" style="flex-wrap:wrap">
                                <button type="button" class="transport-btn" data-mode="car"
                                        onclick="setTransport({{ $day->id }}, 'car')">🚗 Car</button>
                                <button type="button" class="transport-btn" data-mode="bus"
                                        onclick="setTransport({{ $day->id }}, 'bus')">🚌 Bus</button>
                                <button type="button" class="transport-btn" data-mode="train"
                                        onclick="setTransport({{ $day->id }}, 'train')">🚂 Train</button>
                            </div>
                            <input type="hidden" name="transport_mode" id="transport-input-{{ $day->id }}" value="car">
                        </div>

                        {{-- Stops --}}
                        <div class="form-group">
                            <label class="form-label">Stops <span style="font-weight:400;text-transform:none;letter-spacing:0;font-size:0.8rem">(min 2 — type city then click away to geocode)</span></label>
                            <div id="stops-list-{{ $day->id }}" style="display:flex;flex-direction:column;gap:0.5rem"></div>
                            <button type="button" onclick="addStop({{ $day->id }})" class="btn btn-sm btn-ghost" style="margin-top:0.5rem">+ Add Stop</button>
                        </div>

                        {{-- Map preview --}}
                        <div class="form-group">
                            <div id="route-preview-{{ $day->id }}" class="route-map-preview"></div>
                            <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap">
                                <button type="button" onclick="calculateRoute({{ $day->id }})" class="btn btn-sm btn-outline">🔄 Calculate Route</button>
                                <span id="route-calc-summary-{{ $day->id }}" style="font-size:0.85rem;color:var(--muted)"></span>
                            </div>
                        </div>

                        <input type="hidden" name="total_distance_km" id="total-distance-{{ $day->id }}">
                        <input type="hidden" name="total_duration_minutes" id="total-duration-{{ $day->id }}">

                        <div class="flex gap-1">
                            <button type="button" onclick="closeModal('route-modal-{{ $day->id }}')" class="btn btn-outline">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Route</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Flight Modals (one per day, for each flight + new) --}}
        @foreach($day->flights as $flight)
        <div id="flight-edit-modal-{{ $flight->id }}" class="modal-backdrop">
            <div class="modal" style="max-width:580px">
                <div class="modal-header">
                    <h3>Edit Flight — Day {{ $day->day_number }}</h3>
                    <button class="modal-close" onclick="closeModal('flight-edit-modal-{{ $flight->id }}')">×</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('flights.update', $flight) }}">
                        @csrf @method('PUT')
                        @include('trips._flight_form', ['f' => $flight])
                        <div class="flex gap-1">
                            <button type="button" onclick="closeModal('flight-edit-modal-{{ $flight->id }}')" class="btn btn-outline">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        <div id="flight-add-modal-{{ $day->id }}" class="modal-backdrop">
            <div class="modal" style="max-width:580px">
                <div class="modal-header">
                    <h3>Add Flight — Day {{ $day->day_number }}</h3>
                    <button class="modal-close" onclick="closeModal('flight-add-modal-{{ $day->id }}')">×</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('flights.store', $day) }}">
                        @csrf
                        @include('trips._flight_form', ['f' => null])
                        <div class="flex gap-1">
                            <button type="button" onclick="closeModal('flight-add-modal-{{ $day->id }}')" class="btn btn-outline">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Flight</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @endforeach
    </div>

    {{-- Trip totals --}}
    @php
        $totalKm = $trip->days->sum(fn($d) => $d->route?->total_distance_km ?? 0);
        $totalMin = $trip->days->sum(fn($d) => $d->route?->total_duration_minutes ?? 0);
    @endphp
    @if($totalKm > 0)
        <div style="margin-top:0.5rem;padding:0.875rem 1.25rem;background:white;border-radius:12px;box-shadow:var(--shadow);display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap">
            <span style="font-size:0.85rem;color:var(--muted);font-weight:500">Trip totals</span>
            <span class="badge badge-blue" style="font-size:0.85rem;padding:0.3rem 0.75rem">📏 {{ number_format($totalKm, 1) }} km total</span>
            @if($totalMin > 0)
                @php $th = intdiv($totalMin, 60); $tm = $totalMin % 60; @endphp
                <span class="badge badge-gold" style="font-size:0.85rem;padding:0.3rem 0.75rem">⏱ {{ $th > 0 ? $th.'h '.$tm.'min' : $tm.'min' }} driving</span>
            @endif
        </div>
    @endif
@endif

@push('scripts')
<script>
function openFlightModal(dayId, flightId = null) {
    if (flightId) {
        openModal(`flight-edit-modal-${flightId}`);
    } else {
        openModal(`flight-add-modal-${dayId}`);
    }
}

function toggleAccordion(dayId) {
    const trigger = document.querySelector(`[onclick="toggleAccordion(${dayId})"]`);
    const body = document.getElementById(`accordion-body-${dayId}`);
    trigger.classList.toggle('open');
    body.classList.toggle('open');
}

// ─── Route feature ───────────────────────────────────────────────

function haversine(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) * Math.sin(dLon/2)**2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

function formatDuration(minutes) {
    const h = Math.floor(minutes / 60);
    const m = Math.round(minutes % 60);
    return h > 0 ? `${h}h ${m}min` : `${m}min`;
}

function setTransport(dayId, mode) {
    document.getElementById(`transport-input-${dayId}`).value = mode;
    document.querySelectorAll(`#route-modal-${dayId} .transport-btn`).forEach(btn => {
        btn.classList.toggle('active', btn.dataset.mode === mode);
    });
}

const stopCounter = {};
const leafletMaps = {};

function addStop(dayId, prefill = null) {
    if (!stopCounter[dayId]) stopCounter[dayId] = 0;
    const idx = stopCounter[dayId]++;
    const list = document.getElementById(`stops-list-${dayId}`);
    const row = document.createElement('div');
    row.className = 'stop-row';
    row.dataset.idx = idx;
    const geocoded = prefill ? '✓' : '';
    const geocodedColor = prefill ? 'var(--accent)' : '';
    row.innerHTML = `
        <div style="display:flex;align-items:center;gap:0.5rem">
            <span class="stop-num" style="width:22px;height:22px;border-radius:50%;background:var(--ink);color:white;display:flex;align-items:center;justify-content:center;font-size:0.7rem;flex-shrink:0;font-weight:600">●</span>
            <input type="text" class="form-control stop-city-input" placeholder="City (e.g. Paris)" style="flex:1;padding:0.4rem 0.6rem"
                   value="${prefill?.city || ''}" onblur="geocodeStop(${dayId}, this)">
            <input type="hidden" class="stop-lat" name="stops[${idx}][latitude]" value="${prefill?.latitude || ''}">
            <input type="hidden" class="stop-lng" name="stops[${idx}][longitude]" value="${prefill?.longitude || ''}">
            <input type="hidden" class="stop-country" name="stops[${idx}][country]" value="${prefill?.country || ''}">
            <input type="hidden" class="stop-city-val" name="stops[${idx}][city]" value="${prefill?.city || ''}">
            <span class="stop-status" style="font-size:0.9rem;flex-shrink:0;color:${geocodedColor}">${geocoded}</span>
            <button type="button" onclick="removeStop(this, ${dayId})" class="btn btn-sm btn-ghost" style="color:var(--danger);padding:0.2rem 0.4rem;flex-shrink:0">✕</button>
        </div>
    `;
    list.appendChild(row);
    updateStopNumbers(dayId);
}

function removeStop(btn, dayId) {
    const list = document.getElementById(`stops-list-${dayId}`);
    if (list.querySelectorAll('.stop-row').length <= 2) { alert('You need at least 2 stops.'); return; }
    btn.closest('.stop-row').remove();
    updateStopNumbers(dayId);
}

function updateStopNumbers(dayId) {
    document.querySelectorAll(`#stops-list-${dayId} .stop-row`).forEach((row, i) => {
        row.querySelector('.stop-num').textContent = i + 1;
        row.querySelector('.stop-lat').name = `stops[${i}][latitude]`;
        row.querySelector('.stop-lng').name = `stops[${i}][longitude]`;
        row.querySelector('.stop-country').name = `stops[${i}][country]`;
        row.querySelector('.stop-city-val').name = `stops[${i}][city]`;
    });
}

async function geocodeStop(dayId, input) {
    const city = input.value.trim();
    if (!city) return;
    const row = input.closest('.stop-row');
    const statusEl = row.querySelector('.stop-status');
    statusEl.textContent = '⏳'; statusEl.style.color = '';
    try {
        const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(city)}&format=json&limit=1`, {
            headers: { 'Accept-Language': 'en' }
        });
        const data = await res.json();
        if (data.length > 0) {
            const place = data[0];
            row.querySelector('.stop-lat').value = place.lat;
            row.querySelector('.stop-lng').value = place.lon;
            row.querySelector('.stop-city-val').value = city;
            const parts = place.display_name.split(', ');
            row.querySelector('.stop-country').value = parts[parts.length - 1] || '';
            statusEl.textContent = '✓'; statusEl.style.color = 'var(--accent)';
            updatePreviewMap(dayId);
        } else {
            statusEl.textContent = '✗'; statusEl.style.color = 'var(--danger)';
        }
    } catch(e) {
        statusEl.textContent = '✗'; statusEl.style.color = 'var(--danger)';
    }
}

function getModalStops(dayId) {
    const stops = [];
    document.querySelectorAll(`#stops-list-${dayId} .stop-row`).forEach(row => {
        const lat = parseFloat(row.querySelector('.stop-lat').value);
        const lng = parseFloat(row.querySelector('.stop-lng').value);
        const city = row.querySelector('.stop-city-val').value;
        if (!isNaN(lat) && !isNaN(lng)) stops.push({ lat, lng, city });
    });
    return stops;
}

function initPreviewMap(dayId) {
    const mapEl = document.getElementById(`route-preview-${dayId}`);
    if (!mapEl) return null;
    if (leafletMaps[`preview-${dayId}`]) return leafletMaps[`preview-${dayId}`];
    const map = L.map(mapEl).setView([48.8566, 2.3522], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    leafletMaps[`preview-${dayId}`] = map;
    return map;
}

function clearMapLayers(map) {
    map.eachLayer(layer => { if (!(layer instanceof L.TileLayer)) map.removeLayer(layer); });
}

function addStopMarkers(map, stops) {
    stops.forEach((s, i) => {
        L.marker([s.lat, s.lng], {
            icon: L.divIcon({
                html: `<div style="background:#1a1a2e;color:white;border-radius:50%;width:24px;height:24px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.4)">${i+1}</div>`,
                className: '', iconSize: [24,24], iconAnchor: [12,12]
            })
        }).addTo(map).bindPopup(s.city);
    });
}

function updatePreviewMap(dayId) {
    const stops = getModalStops(dayId);
    if (stops.length < 2) return;
    const map = initPreviewMap(dayId);
    if (!map) return;
    clearMapLayers(map);
    const latlngs = stops.map(s => [s.lat, s.lng]);
    L.polyline(latlngs, { color: '#1a1a2e', weight: 2, dashArray: '5,5' }).addTo(map);
    addStopMarkers(map, stops);
    map.fitBounds(L.latLngBounds(latlngs), { padding: [20, 20] });
}

async function calculateRoute(dayId) {
    const stops = getModalStops(dayId);
    if (stops.length < 2) { alert('Please add and geocode at least 2 stops.'); return; }
    const mode = document.getElementById(`transport-input-${dayId}`).value;
    const summaryEl = document.getElementById(`route-calc-summary-${dayId}`);
    summaryEl.textContent = 'Calculating…'; summaryEl.style.color = 'var(--muted)';
    let distKm, durationMin;

    if (mode === 'car') {
        try {
            const coords = stops.map(s => `${s.lng},${s.lat}`).join(';');
            const res = await fetch(`https://router.project-osrm.org/route/v1/driving/${coords}?overview=full&geometries=geojson`);
            const data = await res.json();
            if (data.code === 'Ok' && data.routes.length > 0) {
                const route = data.routes[0];
                distKm = route.distance / 1000;
                durationMin = route.duration / 60;
                const map = initPreviewMap(dayId);
                clearMapLayers(map);
                const geojsonLayer = L.geoJSON(route.geometry, { style: { color: '#1a1a2e', weight: 3 } }).addTo(map);
                addStopMarkers(map, stops);
                map.fitBounds(geojsonLayer.getBounds(), { padding: [20, 20] });
            } else {
                throw new Error('OSRM no route');
            }
        } catch(e) {
            distKm = stops.reduce((sum, s, i) => i === 0 ? 0 : sum + haversine(stops[i-1].lat, stops[i-1].lng, s.lat, s.lng), 0);
            durationMin = distKm / 90 * 60;
            updatePreviewMap(dayId);
        }
    } else {
        distKm = stops.reduce((sum, s, i) => i === 0 ? 0 : sum + haversine(stops[i-1].lat, stops[i-1].lng, s.lat, s.lng), 0);
        durationMin = distKm / (mode === 'train' ? 150 : 70) * 60;
        updatePreviewMap(dayId);
    }

    distKm = Math.round(distKm * 10) / 10;
    durationMin = Math.round(durationMin);
    document.getElementById(`total-distance-${dayId}`).value = distKm;
    document.getElementById(`total-duration-${dayId}`).value = durationMin;
    const modeLabel = { car: '🚗 Car', bus: '🚌 Bus', train: '🚂 Train' }[mode];
    summaryEl.innerHTML = `<strong>${modeLabel}:</strong> ~${distKm} km · ${formatDuration(durationMin)}`;
    summaryEl.style.color = 'var(--accent)';
}

function openRouteModalFromEl(btn) {
    const dayId = btn.dataset.day;
    const stopsJson = btn.dataset.stops;
    const existingStops = stopsJson ? JSON.parse(stopsJson) : null;
    const mode = btn.dataset.mode || 'car';
    openRouteModal(dayId, existingStops, mode);
}

function openRouteModal(dayId, existingStops = null, mode = 'car') {
    const list = document.getElementById(`stops-list-${dayId}`);
    list.innerHTML = '';
    stopCounter[dayId] = 0;

    // Reset calc summary
    const summaryEl = document.getElementById(`route-calc-summary-${dayId}`);
    if (summaryEl) { summaryEl.textContent = ''; }

    setTransport(dayId, mode);

    if (existingStops && existingStops.length > 0) {
        existingStops.forEach(stop => addStop(dayId, stop));
    } else {
        addStop(dayId); addStop(dayId);
    }

    openModal(`route-modal-${dayId}`);

    // Destroy old preview map instance so Leaflet can re-init in modal
    if (leafletMaps[`preview-${dayId}`]) {
        leafletMaps[`preview-${dayId}`].remove();
        delete leafletMaps[`preview-${dayId}`];
    }

    setTimeout(() => {
        initPreviewMap(dayId);
        if (existingStops && existingStops.length >= 2) updatePreviewMap(dayId);
    }, 150);
}

// Initialize saved route display maps on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.route-map[data-stops]').forEach(mapEl => {
        const stops = JSON.parse(mapEl.dataset.stops);
        const mode = mapEl.dataset.mode;
        const dayId = mapEl.dataset.dayId;
        if (stops.length < 2) return;

        const map = L.map(mapEl).setView([stops[0].latitude, stops[0].longitude], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        const latlngs = stops.map(s => [s.latitude, s.longitude]);
        const mappedStops = stops.map(s => ({ lat: s.latitude, lng: s.longitude, city: s.city }));

        if (mode === 'car') {
            const coords = stops.map(s => `${s.longitude},${s.latitude}`).join(';');
            fetch(`https://router.project-osrm.org/route/v1/driving/${coords}?overview=full&geometries=geojson`)
                .then(r => r.json())
                .then(data => {
                    if (data.code === 'Ok' && data.routes.length > 0) {
                        L.geoJSON(data.routes[0].geometry, { style: { color: '#1a1a2e', weight: 3 } }).addTo(map);
                    } else {
                        L.polyline(latlngs, { color: '#1a1a2e', weight: 2, dashArray: '5,5' }).addTo(map);
                    }
                    addStopMarkers(map, mappedStops);
                    map.fitBounds(L.latLngBounds(latlngs), { padding: [20, 20] });
                })
                .catch(() => {
                    L.polyline(latlngs, { color: '#1a1a2e', weight: 2, dashArray: '5,5' }).addTo(map);
                    addStopMarkers(map, mappedStops);
                    map.fitBounds(L.latLngBounds(latlngs), { padding: [20, 20] });
                });
        } else {
            L.polyline(latlngs, { color: '#1a1a2e', weight: 2, dashArray: '5,5' }).addTo(map);
            addStopMarkers(map, mappedStops);
            map.fitBounds(L.latLngBounds(latlngs), { padding: [20, 20] });
        }

        leafletMaps[`display-${dayId}`] = map;
    });
});
</script>
@endpush
@endsection
