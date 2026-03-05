<div id="flight-edit-modal-{{ $flight->id }}" class="modal-backdrop">
    <div class="modal" style="max-width:580px">
        <div class="modal-header">
            <h3>{{ __('flights.modal.edit_title', ['number' => $day->day_number]) }}</h3>
            <button class="modal-close" onclick="closeModal('flight-edit-modal-{{ $flight->id }}')">×</button>
        </div>
        <div class="modal-body">
            <form id="flight-edit-form-{{ $flight->id }}" method="POST" action="{{ route('flights.update', $flight) }}"
                  data-ajax
                  data-ajax-handler="handleFlightEditSuccess"
                  data-ajax-no-clear>
                @csrf @method('PUT')
                @include('trips._flight_form', ['f' => $flight])
                <div class="flex gap-1">
                    <button type="button" onclick="closeModal('flight-edit-modal-{{ $flight->id }}')" class="btn btn-outline">{{ __('general.btn.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('general.btn.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
