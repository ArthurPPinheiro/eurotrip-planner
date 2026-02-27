{{-- Shared flight form fields. $f = existing Flight model or null for new --}}
<div class="grid-2">
    <div class="form-group">
        <label class="form-label">{{ __('flights.departure_airport') }}</label>
        <input type="text" name="departure_airport" class="form-control" required
               placeholder="LIS" style="text-transform:uppercase;letter-spacing:0.05em"
               value="{{ old('departure_airport', $f?->departure_airport) }}">
        <p class="form-error" style="font-size:0.75rem;color:var(--muted);margin-top:0.2rem">{{ __('flights.iata_hint') }}</p>
    </div>
    <div class="form-group">
        <label class="form-label">{{ __('flights.arrival_airport') }}</label>
        <input type="text" name="arrival_airport" class="form-control" required
               placeholder="CDG" style="text-transform:uppercase;letter-spacing:0.05em"
               value="{{ old('arrival_airport', $f?->arrival_airport) }}">
        <p class="form-error" style="font-size:0.75rem;color:var(--muted);margin-top:0.2rem">{{ __('flights.iata_hint') }}</p>
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label">{{ __('flights.departure_city') }}</label>
        <input type="text" name="departure_city" class="form-control" placeholder="Lisboa"
               value="{{ old('departure_city', $f?->departure_city) }}">
    </div>
    <div class="form-group">
        <label class="form-label">{{ __('flights.arrival_city') }}</label>
        <input type="text" name="arrival_city" class="form-control" placeholder="Paris"
               value="{{ old('arrival_city', $f?->arrival_city) }}">
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label">{{ __('flights.departure_time') }}</label>
        <input type="time" name="departure_time" class="form-control"
               value="{{ old('departure_time', $f?->departure_time) }}">
    </div>
    <div class="form-group">
        <label class="form-label">{{ __('flights.arrival_time') }}</label>
        <input type="time" name="arrival_time" class="form-control"
               value="{{ old('arrival_time', $f?->arrival_time) }}">
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label">{{ __('flights.flight_number') }}</label>
        <input type="text" name="flight_number" class="form-control" placeholder="TP1234"
               style="text-transform:uppercase;letter-spacing:0.05em"
               value="{{ old('flight_number', $f?->flight_number) }}">
    </div>
    <div class="form-group">
        <label class="form-label">{{ __('flights.airline') }}</label>
        <input type="text" name="airline" class="form-control" placeholder="TAP Air Portugal"
               value="{{ old('airline', $f?->airline) }}">
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label">{{ __('flights.locator') }}</label>
        <input type="text" name="locator" class="form-control" placeholder="ABC123"
               style="text-transform:uppercase;letter-spacing:0.1em"
               value="{{ old('locator', $f?->locator) }}">
    </div>
    <div class="form-group">
        <label class="form-label">{{ __('flights.duration') }}</label>
        <input type="number" name="duration_minutes" class="form-control" placeholder="135" min="0"
               value="{{ old('duration_minutes', $f?->duration_minutes) }}">
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label">{{ __('flights.seat') }}</label>
        <input type="text" name="seat" class="form-control" placeholder="14A"
               style="text-transform:uppercase"
               value="{{ old('seat', $f?->seat) }}">
    </div>
    <div class="form-group">
        <label class="form-label">{{ __('flights.cabin_class') }}</label>
        <select name="cabin_class" class="form-control">
            <option value="">{{ __('flights.cabin_select') }}</option>
            <option value="economy" {{ old('cabin_class', $f?->cabin_class) === 'economy' ? 'selected' : '' }}>{{ __('flights.cabin.economy') }}</option>
            <option value="premium_economy" {{ old('cabin_class', $f?->cabin_class) === 'premium_economy' ? 'selected' : '' }}>{{ __('flights.cabin.premium_economy') }}</option>
            <option value="business" {{ old('cabin_class', $f?->cabin_class) === 'business' ? 'selected' : '' }}>{{ __('flights.cabin.business') }}</option>
            <option value="first" {{ old('cabin_class', $f?->cabin_class) === 'first' ? 'selected' : '' }}>{{ __('flights.cabin.first') }}</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="form-label">{{ __('flights.notes') }}</label>
    <textarea name="notes" class="form-control" placeholder="{{ __('flights.notes_placeholder') }}" rows="2">{{ old('notes', $f?->notes) }}</textarea>
</div>
