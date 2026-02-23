@extends('layouts.app')
@section('title', 'Documents — '.$trip->name)
@section('content')

<div class="flex-between mb-3">
    <div>
        <a href="{{ route('trips.show', $trip) }}" class="text-muted text-sm">← Back to itinerary</a>
        <h1 class="page-title mt-1">📂 Documents</h1>
        <p class="page-subtitle">{{ $trip->name }}</p>
    </div>
    <button onclick="openModal('uploadModal')" class="btn btn-primary">+ Upload Document</button>
</div>

<!-- Tabs -->
<div class="flex gap-1 mb-3">
    <a href="{{ route('trips.show', $trip) }}" class="btn btn-outline btn-sm">🗺️ Itinerary</a>
    <a href="{{ route('documents.index', $trip) }}" class="btn btn-primary btn-sm">📂 Documents</a>
</div>

@if($documents->isEmpty())
    <div class="card">
        <div class="empty-state">
            <span class="emoji">📁</span>
            <h3 style="font-family:'Playfair Display',serif;font-size:1.4rem;margin-bottom:0.5rem">No documents yet</h3>
            <p class="text-muted">Upload passports, visas, insurance, tickets — everything in one place</p>
            <button onclick="openModal('uploadModal')" class="btn btn-primary mt-2">Upload First Document</button>
        </div>
    </div>
@else
    @foreach(['passport' => '🛂 Passports', 'visa' => '📋 Visas', 'insurance' => '🏥 Insurance', 'ticket' => '✈️ Tickets', 'other' => '📄 Other'] as $type => $label)
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
                            <div class="badge badge-gold mb-2">Expires: {{ $doc->expires_at }}</div>
                        @endif
                        @if($doc->notes)
                            <p class="text-sm text-muted mb-2">{{ $doc->notes }}</p>
                        @endif
                        <div class="text-sm text-muted mb-2">{{ $doc->original_name }} · {{ $doc->formattedSize() }}</div>
                        <div class="flex gap-1">
                            <a href="{{ route('documents.download', $doc) }}" class="btn btn-sm btn-outline">⬇ Download</a>
                            @if(auth()->id() === $doc->user_id)
                            <form method="POST" action="{{ route('documents.destroy', $doc) }}" onsubmit="return confirm('Delete this document?')">
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
            <h3>Upload Document</h3>
            <button class="modal-close" onclick="closeModal('uploadModal')">×</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('documents.store', $trip) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Document Type *</label>
                    <select name="type" class="form-control" required>
                        <option value="passport">🛂 Passport</option>
                        <option value="visa">📋 Visa</option>
                        <option value="insurance">🏥 Insurance</option>
                        <option value="ticket">✈️ Ticket / Booking</option>
                        <option value="other">📄 Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g. John's Passport, Travel Insurance">
                </div>
                <div class="form-group">
                    <label class="form-label">File * (max 10MB)</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expires_at" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" placeholder="Any additional notes..."></textarea>
                </div>
                <div class="flex gap-1">
                    <button type="button" onclick="closeModal('uploadModal')" class="btn btn-outline">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
