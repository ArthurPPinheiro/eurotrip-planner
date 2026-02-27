@extends('layouts.app')
@section('title', $destination->city . ', ' . $destination->country)

@push('styles')
<style>
.dest-hero {
    background: var(--navy);
    border-radius: var(--radius);
    padding: 1.75rem 2rem;
    margin-bottom: 1.75rem;
    color: var(--cream);
    display: flex;
    align-items: center;
    gap: 1.5rem;
}
.dest-hero-flag { font-size: 4rem; }
.dest-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: 1.9rem;
    line-height: 1;
    margin-bottom: .35rem;
}
.dest-hero-sub { opacity: .7; font-size: .9rem; }
.dest-hero-actions { margin-left: auto; display: flex; gap: .5rem; }

.section-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}
@media (max-width: 768px) { .section-grid { grid-template-columns: 1fr; } }

.poi-item {
    display: flex;
    align-items: flex-start;
    gap: .75rem;
    padding: .85rem;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    transition: border-color .2s;
}
.poi-item.visited { opacity: .6; background: var(--cream); }
.poi-icon { font-size: 1.4rem; flex-shrink: 0; margin-top: .1rem; }
.poi-name { font-weight: 600; color: var(--navy); font-size: .95rem; }
.poi-category { font-size: .75rem; color: var(--text-muted); text-transform: capitalize; }
.poi-desc { font-size: .82rem; color: var(--text-muted); margin-top: .25rem; }
.poi-actions { margin-left: auto; display: flex; gap: .3rem; flex-shrink: 0; }

.accommodation-item, .reservation-item {
    padding: .9rem;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    margin-bottom: .75rem;
}
.accommodation-item:last-child, .reservation-item:last-child { margin-bottom: 0; }
.item-title { font-weight: 600; color: var(--navy); display: flex; align-items: center; gap: .5rem; }
.item-meta { font-size: .8rem; color: var(--text-muted); margin-top: .4rem; display: flex; flex-wrap: wrap; gap: .75rem; }
.item-meta span { display: flex; align-items: center; gap: .3rem; }

.comments-list { display: flex; flex-direction: column; gap: .75rem; margin-bottom: 1rem; }
.comment {
    background: var(--cream);
    border-radius: var(--radius-sm);
    padding: .75rem 1rem;
}
.comment-author { font-weight: 600; font-size: .85rem; color: var(--navy); }
.comment-date { font-size: .75rem; color: var(--text-muted); }
.comment-text { margin-top: .35rem; font-size: .9rem; }
</style>
@endpush

@section('content')
{{-- Hero --}}
<div class="dest-hero">
    <div class="dest-hero-flag">{{ $destination->flag_emoji ?: '📍' }}</div>
    <div>
        <h1>{{ $destination->city }}</h1>
        <div class="dest-hero-sub">
            {{ $destination->country }} &nbsp;•&nbsp;
            {{ $day->date->translatedFormat('l, M d, Y') }} &nbsp;•&nbsp;
            <a href="{{ route('trips.show', $day->trip) }}" style="color: var(--terra-muted); text-decoration: none;">{{ $day->trip->name }}</a>
        </div>
        @if($destination->notes)
            <div style="margin-top: .5rem; font-size: .85rem; opacity: .8; font-style: italic;">{{ $destination->notes }}</div>
        @endif
    </div>
    <div class="dest-hero-actions">
        <button onclick="openModal('modal-edit-dest')" class="btn btn-ghost btn-sm" style="background:rgba(255,255,255,.15); color:#fff; border-color:rgba(255,255,255,.3);">✏️ {{ __('general.btn.edit') }}</button>
    </div>
</div>

<div class="section-grid">
    {{-- ===== POINTS OF INTEREST ===== --}}
    <div class="card">
        <div class="card-header">
            <h3>📍 {{ __('destinations.poi.title') }}</h3>
            <button onclick="openModal('modal-add-poi')" class="btn btn-primary btn-sm">{{ __('general.btn.add') }}</button>
        </div>
        <div class="card-body">
            @if($destination->pointsOfInterest->isEmpty())
                <div class="empty-state" style="padding: 1.5rem 1rem;">
                    <div class="empty-icon" style="font-size: 2rem;">🗺️</div>
                    <p>{{ __('destinations.poi.empty') }}</p>
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: .6rem;">
                @foreach($destination->pointsOfInterest as $poi)
                @php $cats = \App\Models\PointOfInterest::categories(); $cat = $cats[$poi->category] ?? $cats['other']; @endphp
                <div class="poi-item {{ $poi->visited ? 'visited' : '' }}">
                    <div class="poi-icon">{{ $cat['icon'] }}</div>
                    <div style="flex: 1; min-width: 0;">
                        <div class="poi-name" style="{{ $poi->visited ? 'text-decoration: line-through;' : '' }}">{{ $poi->name }}</div>
                        <div class="poi-category">{{ $cat['label'] }}</div>
                        @if($poi->description) <div class="poi-desc">{{ Str::limit($poi->description, 80) }}</div> @endif
                        @if($poi->address) <div class="poi-desc">📍 {{ $poi->address }}</div> @endif
                        @if($poi->url) <div class="poi-desc"><a href="{{ $poi->url }}" target="_blank" style="color:var(--terra)">🔗 {{ __('general.label.link') }}</a></div> @endif
                    </div>
                    <div class="poi-actions">
                        <form method="POST" action="{{ route('pois.toggle', $poi) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-xs" style="background: {{ $poi->visited ? 'var(--success)' : 'var(--cream-dark)' }}; color: {{ $poi->visited ? '#fff' : 'var(--text-muted)' }}; border: none; cursor: pointer;" title="{{ $poi->visited ? __('destinations.poi.unvisited') : __('destinations.poi.visited') }}">{{ $poi->visited ? '✓' : '○' }}</button>
                        </form>
                        <form method="POST" action="{{ route('pois.destroy', $poi) }}" class="delete-form" data-confirm="{{ __('general.confirm.remove') }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-ghost" style="color:var(--danger); border-color:var(--danger);">✕</button>
                        </form>
                    </div>
                </div>
                @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ===== ACCOMMODATION ===== --}}
    <div class="card">
        <div class="card-header">
            <h3>🏨 {{ __('destinations.accommodation.title') }}</h3>
            <button onclick="openModal('modal-add-accommodation')" class="btn btn-primary btn-sm">{{ __('general.btn.add') }}</button>
        </div>
        <div class="card-body">
            @if($destination->accommodations->isEmpty())
                <div class="empty-state" style="padding: 1.5rem 1rem;">
                    <div class="empty-icon" style="font-size: 2rem;">🛏️</div>
                    <p>{{ __('destinations.accommodation.empty') }}</p>
                </div>
            @else
                @foreach($destination->accommodations as $accom)
                @php $types = \App\Models\Accommodation::types(); $atype = $types[$accom->type] ?? $types['other']; @endphp
                <div class="accommodation-item">
                    <div class="item-title">{{ $atype['icon'] }} {{ $accom->name }} <span class="badge badge-muted">{{ $atype['label'] }}</span></div>
                    <div class="item-meta">
                        @if($accom->address) <span>📍 {{ $accom->address }}</span> @endif
                        @if($accom->check_in) <span>🕐 {{ __('destinations.accommodation.in') }} {{ $accom->check_in }}</span> @endif
                        @if($accom->check_out) <span>🕐 {{ __('destinations.accommodation.out') }} {{ $accom->check_out }}</span> @endif
                        @if($accom->confirmation_code) <span>🔑 {{ $accom->confirmation_code }}</span> @endif
                        @if($accom->price_per_night) <span>💰 {{ $accom->price_per_night }} {{ $accom->currency }}{{ __('destinations.accommodation.per_night') }}</span> @endif
                        @if($accom->url) <span><a href="{{ $accom->url }}" target="_blank" style="color:var(--terra)">🔗 {{ __('general.label.view') }}</a></span> @endif
                    </div>
                    @if($accom->notes) <div style="font-size:.82rem; color:var(--text-muted); margin-top:.4rem; font-style:italic;">{{ $accom->notes }}</div> @endif
                    <div class="mt-1">
                        <form method="POST" action="{{ route('accommodations.destroy', $accom) }}" class="delete-form" data-confirm="{{ __('general.confirm.remove') }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-ghost" style="color:var(--danger); border-color:var(--danger);">✕ {{ __('general.btn.remove') }}</button>
                        </form>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- ===== RESERVATIONS ===== --}}
    <div class="card">
        <div class="card-header">
            <h3>🎫 {{ __('destinations.reservation.title') }}</h3>
            <button onclick="openModal('modal-add-reservation')" class="btn btn-primary btn-sm">{{ __('general.btn.add') }}</button>
        </div>
        <div class="card-body">
            @if($destination->reservations->isEmpty())
                <div class="empty-state" style="padding: 1.5rem 1rem;">
                    <div class="empty-icon" style="font-size: 2rem;">📋</div>
                    <p>{{ __('destinations.reservation.empty') }}</p>
                </div>
            @else
                @foreach($destination->reservations as $res)
                @php $rtypes = \App\Models\Reservation::types(); $rtype = $rtypes[$res->type] ?? $rtypes['other']; @endphp
                <div class="reservation-item">
                    <div class="item-title">{{ $rtype['icon'] }} {{ $res->title }} <span class="badge badge-muted">{{ $rtype['label'] }}</span></div>
                    <div class="item-meta">
                        @if($res->datetime) <span>📅 {{ $res->datetime->translatedFormat('M d, Y H:i') }}</span> @endif
                        @if($res->venue) <span>📍 {{ $res->venue }}</span> @endif
                        @if($res->confirmation_code) <span>🔑 {{ $res->confirmation_code }}</span> @endif
                        @if($res->price) <span>💰 {{ $res->price }} {{ $res->currency }}</span> @endif
                    </div>
                    @if($res->notes) <div style="font-size:.82rem; color:var(--text-muted); margin-top:.4rem; font-style:italic;">{{ $res->notes }}</div> @endif
                    <div class="mt-1">
                        <form method="POST" action="{{ route('reservations.destroy', $res) }}" class="delete-form" data-confirm="{{ __('general.confirm.remove') }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-ghost" style="color:var(--danger); border-color:var(--danger);">✕ {{ __('general.btn.remove') }}</button>
                        </form>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- ===== COMMENTS ===== --}}
    <div class="card">
        <div class="card-header">
            <h3>💬 {{ __('destinations.comments.title') }}</h3>
        </div>
        <div class="card-body">
            @if($destination->comments->count())
            <div class="comments-list">
                @foreach($destination->comments as $comment)
                <div class="comment">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="comment-author">{{ $comment->author_name }}</span>
                            <span class="comment-date"> · {{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="delete-form" data-confirm="{{ __('general.confirm.delete_comment') }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-ghost" style="color:var(--danger); border-color:var(--danger);">✕</button>
                        </form>
                    </div>
                    <div class="comment-text">{{ $comment->content }}</div>
                </div>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('comments.store', ['type' => 'destination', 'id' => $destination->id]) }}">
                @csrf
                <div class="form-group">
                    <label>{{ __('destinations.comments.your_name') }}</label>
                    <input type="text" name="author_name" placeholder="{{ __('destinations.comments.name_placeholder') }}" required>
                </div>
                <div class="form-group">
                    <label>{{ __('destinations.comments.comment_label') }}</label>
                    <textarea name="content" placeholder="{{ __('destinations.comments.comment_placeholder') }}" required></textarea>
                </div>
                <button type="submit" class="btn btn-navy btn-sm">{{ __('destinations.comments.post_btn') }}</button>
            </form>
        </div>
    </div>
</div>


{{-- ===================== MODALS ===================== --}}

{{-- Edit Destination --}}
<div class="modal-overlay" id="modal-edit-dest">
    <div class="modal">
        <div class="modal-header">
            <h3>✏️ {{ __('destinations.modal.edit_dest') }}</h3>
            <button class="modal-close" onclick="closeModal('modal-edit-dest')">×</button>
        </div>
        <form method="POST" action="{{ route('destinations.update', $destination) }}">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>{{ __('trips.modal.city') }}</label>
                        <input type="text" name="city" value="{{ $destination->city }}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('trips.modal.country') }}</label>
                        <input type="text" name="country" value="{{ $destination->country }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ __('destinations.modal.flag_emoji') }}</label>
                    <input type="text" name="flag_emoji" value="{{ $destination->flag_emoji }}" maxlength="4" style="font-size: 1.4rem; text-align: center; width: 80px;">
                </div>
                <div class="form-group">
                    <label>{{ __('general.label.notes') }}</label>
                    <textarea name="notes">{{ $destination->notes }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modal-edit-dest')">{{ __('general.btn.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('general.btn.save') }}</button>
            </div>
        </form>
    </div>
</div>

{{-- Add POI --}}
<div class="modal-overlay" id="modal-add-poi">
    <div class="modal">
        <div class="modal-header">
            <h3>📍 {{ __('destinations.poi.modal_title') }}</h3>
            <button class="modal-close" onclick="closeModal('modal-add-poi')">×</button>
        </div>
        <form method="POST" action="{{ route('pois.store', $destination) }}">
            @csrf
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group" style="flex:2">
                        <label>{{ __('destinations.poi.name') }}</label>
                        <input type="text" name="name" placeholder="{{ __('destinations.poi.name_placeholder') }}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('destinations.poi.category') }}</label>
                        <select name="category">
                            @foreach(\App\Models\PointOfInterest::categories() as $key => $cat)
                                <option value="{{ $key }}">{{ $cat['icon'] }} {{ $cat['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ __('general.label.description') }}</label>
                    <textarea name="description" placeholder="{{ __('destinations.poi.description_placeholder') }}"></textarea>
                </div>
                <div class="form-group">
                    <label>{{ __('general.label.address') }}</label>
                    <input type="text" name="address" placeholder="{{ __('destinations.poi.address_placeholder') }}">
                </div>
                <div class="form-group">
                    <label>{{ __('general.label.link_url') }}</label>
                    <input type="url" name="url" placeholder="https://...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modal-add-poi')">{{ __('general.btn.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('destinations.poi.add_btn') }}</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Accommodation --}}
<div class="modal-overlay" id="modal-add-accommodation">
    <div class="modal">
        <div class="modal-header">
            <h3>🏨 {{ __('destinations.accommodation.modal_title') }}</h3>
            <button class="modal-close" onclick="closeModal('modal-add-accommodation')">×</button>
        </div>
        <form method="POST" action="{{ route('accommodations.store', $destination) }}">
            @csrf
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group" style="flex:2">
                        <label>{{ __('general.label.name') }} *</label>
                        <input type="text" name="name" placeholder="{{ __('destinations.accommodation.name_placeholder') }}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('general.label.type') }} *</label>
                        <select name="type">
                            @foreach(\App\Models\Accommodation::types() as $key => $t)
                                <option value="{{ $key }}">{{ $t['icon'] }} {{ $t['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ __('general.label.address') }}</label>
                    <input type="text" name="address">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>{{ __('destinations.accommodation.check_in') }}</label>
                        <input type="text" name="check_in" placeholder="{{ __('destinations.accommodation.check_in_placeholder') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('destinations.accommodation.check_out') }}</label>
                        <input type="text" name="check_out" placeholder="{{ __('destinations.accommodation.check_out_placeholder') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>{{ __('general.label.confirmation_code') }}</label>
                        <input type="text" name="confirmation_code">
                    </div>
                    <div class="form-group">
                        <label>{{ __('destinations.accommodation.price_night') }}</label>
                        <input type="number" name="price_per_night" step="0.01" placeholder="0.00">
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ __('destinations.accommodation.booking_url') }}</label>
                    <input type="url" name="url" placeholder="https://...">
                </div>
                <div class="form-group">
                    <label>{{ __('general.label.notes') }}</label>
                    <textarea name="notes"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modal-add-accommodation')">{{ __('general.btn.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('destinations.accommodation.add_btn') }}</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Reservation --}}
<div class="modal-overlay" id="modal-add-reservation">
    <div class="modal">
        <div class="modal-header">
            <h3>🎫 {{ __('destinations.reservation.modal_title') }}</h3>
            <button class="modal-close" onclick="closeModal('modal-add-reservation')">×</button>
        </div>
        <form method="POST" action="{{ route('reservations.store', $destination) }}">
            @csrf
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group" style="flex:2">
                        <label>{{ __('general.label.title') }} *</label>
                        <input type="text" name="title" placeholder="{{ __('destinations.reservation.title_placeholder') }}" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('general.label.type') }} *</label>
                        <select name="type">
                            @foreach(\App\Models\Reservation::types() as $key => $t)
                                <option value="{{ $key }}">{{ $t['icon'] }} {{ $t['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>{{ __('destinations.reservation.datetime') }}</label>
                        <input type="datetime-local" name="datetime">
                    </div>
                    <div class="form-group">
                        <label>{{ __('destinations.reservation.venue') }}</label>
                        <input type="text" name="venue">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>{{ __('general.label.confirmation_code') }}</label>
                        <input type="text" name="confirmation_code">
                    </div>
                    <div class="form-group">
                        <label>{{ __('general.label.price') }}</label>
                        <input type="number" name="price" step="0.01" placeholder="0.00">
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ __('general.label.notes') }}</label>
                    <textarea name="notes"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modal-add-reservation')">{{ __('general.btn.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('destinations.reservation.add_btn') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
