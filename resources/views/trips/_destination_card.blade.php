<div id="destination-card-{{ $dest->id }}" style="border:1.5px solid var(--cream);border-radius:10px;overflow:hidden">
    <div style="background:var(--cream);padding:0.75rem 1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem">
        <div class="flex gap-1" style="align-items:center">
            <span style="font-size:1.8rem">{{ $dest->emoji }}</span>
            <div>
                <div style="font-weight:600;font-size:1rem">{{ $dest->city }}</div>
                <div class="text-sm text-muted">{{ $dest->country }}</div>
            </div>
        </div>
        <div class="flex gap-1">
            <button onclick="openModal('act-modal-{{ $dest->id }}')" class="btn btn-sm btn-primary">{{ __('trips.show.add_item') }}</button>
            <form method="POST" action="{{ route('destinations.destroy', $dest) }}" data-confirm="{{ __('trips.show.confirm_remove_city', ['city' => $dest->city]) }}">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-ghost" style="color:var(--danger)">✕</button>
            </form>
        </div>
    </div>

    <div id="no-activities-{{ $dest->id }}" style="padding:0.875rem 1.25rem;font-size:0.85rem;color:var(--muted);font-style:italic;{{ $dest->activities->count() ? 'display:none' : '' }}">
        {{ __('trips.show.no_items') }}
    </div>
    <div id="activities-list-{{ $dest->id }}" style="padding:0.75rem 1.25rem;flex-direction:column;gap:0.25rem;display:{{ $dest->activities->count() ? 'flex' : 'none' }}">
        @foreach($dest->activities as $act)
            @include('trips._activity_item', ['act' => $act])
        @endforeach
    </div>

    {{-- Activity Modal --}}
    <div id="act-modal-{{ $dest->id }}" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <h3>{{ __('trips.modal.add_to_city', ['city' => $dest->city]) }}</h3>
                <button class="modal-close" onclick="closeModal('act-modal-{{ $dest->id }}')">×</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('activities.store', $dest) }}"
                      data-ajax
                      data-ajax-target="#activities-list-{{ $dest->id }}"
                      data-ajax-insert="beforeend"
                      data-ajax-hide="#no-activities-{{ $dest->id }}"
                      data-ajax-show="#activities-list-{{ $dest->id }}"
                      data-ajax-show-display="flex"
                      data-ajax-keep-open>
                    @csrf
                    <div class="form-group">
                        <label class="form-label">{{ __('general.label.type') }}</label>
                        <select name="type" class="form-control" required>
                            <option value="poi">📍 {{ __('trips.activity_type.poi') }}</option>
                            <option value="hotel">🏨 {{ __('trips.activity_type.hotel') }}</option>
                            <option value="reservation">🎟️ {{ __('trips.activity_type.reservation') }}</option>
                            <option value="comment">💬 {{ __('trips.activity_type.comment') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('trips.modal.title_label') }}</label>
                        <input type="text" name="title" class="form-control" required placeholder="{{ __('trips.modal.title_placeholder') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('general.label.description') }}</label>
                        <textarea name="description" class="form-control" placeholder="{{ __('trips.modal.description_placeholder') }}"></textarea>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">{{ __('general.label.time') }}</label>
                            <input type="text" name="time" class="form-control" placeholder="{{ __('trips.modal.time_placeholder') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('general.label.price') }}</label>
                            <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('general.label.address') }}</label>
                        <input type="text" name="address" class="form-control" placeholder="{{ __('trips.modal.address_placeholder') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('general.label.link_url') }}</label>
                        <input type="url" name="link" class="form-control" placeholder="https://...">
                    </div>
                    <input type="hidden" name="currency" value="EUR">
                    <div class="flex gap-1">
                        <button type="button" onclick="closeModal('act-modal-{{ $dest->id }}')" class="btn btn-outline">{{ __('general.btn.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('trips.modal.add_item_btn') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
