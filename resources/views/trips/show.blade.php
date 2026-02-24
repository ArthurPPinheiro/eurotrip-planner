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
        <div class="accordion-day">

            {{-- Accordion Trigger --}}
            <span class="accordion-trigger {{ $loop->first ? 'open' : '' }}" onclick="toggleAccordion({{ $day->id }})">
                <div style="flex:1;min-width:0">
                    <div style="font-family:'Playfair Display',serif;font-size:1.05rem;margin-bottom:0.25rem">
                        Day {{ $day->day_number }}
                        @if($day->title) — {{ $day->title }}@endif
                    </div>
                    <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap">
                        <span style="font-size:0.78rem;opacity:0.65">{{ $day->date->format('l, M d, Y') }}</span>
                        @if($day->destinations->count())
                            <div class="day-summary-pills">
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

                    {{-- Add City button --}}
                    <div style="display:flex;justify-content:flex-end;margin-bottom:1rem">
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
        @endforeach
    </div>
@endif

@push('scripts')
<script>
function toggleAccordion(dayId) {
    const trigger = document.querySelector(`[onclick="toggleAccordion(${dayId})"]`);
    const body = document.getElementById(`accordion-body-${dayId}`);
    trigger.classList.toggle('open');
    body.classList.toggle('open');
}



</script>
@endpush
@endsection
