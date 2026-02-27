@extends('layouts.app')
@section('title', __('documents.title') . ' — ' . $trip->name)
@section('content')

<div class="flex-between mb-3">
    <div>
        <a href="{{ route('trips.show', $trip) }}" class="text-muted text-sm">{{ __('documents.back') }}</a>
        <h1 class="page-title mt-1">📂 {{ __('documents.title') }}</h1>
        <p class="page-subtitle">{{ $trip->name }}</p>
    </div>
    <button onclick="openModal('uploadModal')" class="btn btn-primary">{{ __('documents.upload_btn') }}</button>
</div>

<!-- Tabs -->
<div class="flex gap-1 mb-3">
    <a href="{{ route('trips.show', $trip) }}" class="btn btn-outline btn-sm">🗺️ {{ __('trips.show.itinerary') }}</a>
    <a href="{{ route('documents.index', $trip) }}" class="btn btn-primary btn-sm">📂 {{ __('documents.title') }}</a>
</div>

@if($documents->isEmpty())
    <div class="card">
        <div class="empty-state">
            <span class="emoji">📁</span>
            <h3 style="font-family:'Playfair Display',serif;font-size:1.4rem;margin-bottom:0.5rem">{{ __('documents.no_documents') }}</h3>
            <p class="text-muted">{{ __('documents.no_documents_hint') }}</p>
            <button onclick="openModal('uploadModal')" class="btn btn-primary mt-2">{{ __('documents.upload_first') }}</button>
        </div>
    </div>
@else
    @foreach(['passport' => '🛂 ' . __('documents.group.passport'), 'visa' => '📋 ' . __('documents.group.visa'), 'insurance' => '🏥 ' . __('documents.group.insurance'), 'ticket' => '✈️ ' . __('documents.group.ticket'), 'other' => '📄 ' . __('documents.group.other')] as $type => $label)
        @if(isset($grouped[$type]) && $grouped[$type]->count())
        <div class="mb-3">
            <h2 style="font-family:'Playfair Display',serif;font-size:1.2rem;margin-bottom:1rem">{{ $label }}</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem">
                @foreach($grouped[$type] as $doc)
                <div class="card">
                    <div style="padding:1.25rem">
                        <div class="flex-between mb-2">
                            <div class="flex gap-1" style="align-items:center">
                                <span style="font-size:1.5rem">{{ $doc->typeIcon() }}</span>
                                <div>
                                    <div style="font-weight:600;font-size:0.95rem">{{ $doc->title }}</div>
                                    <div class="text-sm text-muted">{{ $doc->owner->name }}</div>
                                </div>
                            </div>
                        </div>
                        @if($doc->expires_at)
                            <div class="badge badge-gold mb-2">{{ __('documents.expires', ['date' => $doc->expires_at]) }}</div>
                        @endif
                        @if($doc->notes)
                            <p class="text-sm text-muted mb-2">{{ $doc->notes }}</p>
                        @endif
                        <div class="text-sm text-muted mb-2">{{ $doc->original_name }} · {{ $doc->formattedSize() }}</div>
                        <div class="flex gap-1">
                            <a href="{{ route('documents.download', $doc) }}" class="btn btn-sm btn-outline">{{ __('documents.download') }}</a>
                            @if(auth()->id() === $doc->user_id)
                            <form method="POST" action="{{ route('documents.destroy', $doc) }}" data-confirm="{{ __('documents.confirm_delete') }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-ghost" style="color:var(--danger)">✕</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach
@endif

<!-- Upload Modal -->
<div id="uploadModal" class="modal-backdrop">
    <div class="modal">
        <div class="modal-header">
            <h3>{{ __('documents.modal_title') }}</h3>
            <button class="modal-close" onclick="closeModal('uploadModal')">×</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('documents.store', $trip) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">{{ __('documents.type_label') }}</label>
                    <select name="type" class="form-control" required>
                        <option value="passport">🛂 {{ __('documents.type.passport') }}</option>
                        <option value="visa">📋 {{ __('documents.type.visa') }}</option>
                        <option value="insurance">🏥 {{ __('documents.type.insurance') }}</option>
                        <option value="ticket">✈️ {{ __('documents.type.ticket') }}</option>
                        <option value="other">📄 {{ __('documents.type.other') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('documents.title_label') }}</label>
                    <input type="text" name="title" class="form-control" required placeholder="{{ __('documents.title_placeholder') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('documents.file_label') }}</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('documents.expiry_date') }}</label>
                    <input type="date" name="expires_at" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('general.label.notes') }}</label>
                    <textarea name="notes" class="form-control" placeholder="{{ __('documents.notes_placeholder') }}"></textarea>
                </div>
                <div class="flex gap-1">
                    <button type="button" onclick="closeModal('uploadModal')" class="btn btn-outline">{{ __('general.btn.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('documents.upload_btn') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
