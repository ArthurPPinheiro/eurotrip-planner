{{-- Shared flight form fields. $f = existing Flight model or null for new --}}
<div class="grid-2">
    <div class="form-group">
        <label class="form-label">Departure Airport *</label>
        <input type="text" name="departure_airport" class="form-control" required
               placeholder="LIS" style="text-transform:uppercase;letter-spacing:0.05em"
               value="{{ old('departure_airport', $f?->departure_airport) }}">
        <p class="form-error" style="font-size:0.75rem;color:var(--muted);margin-top:0.2rem">IATA code or full name</p>
    </div>
    <div class="form-group">
        <label class="form-label">Arrival Airport *</label>
        <input type="text" name="arrival_airport" class="form-control" required
               placeholder="CDG" style="text-transform:uppercase;letter-spacing:0.05em"
               value="{{ old('arrival_airport', $f?->arrival_airport) }}">
        <p class="form-error" style="font-size:0.75rem;color:var(--muted);margin-top:0.2rem">IATA code or full name</p>
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label">Departure City</label>
        <input type="text" name="departure_city" class="form-control" placeholder="Lisbon"
               value="{{ old('departure_city', $f?->departure_city) }}">
    </div>
    <div class="form-group">
        <label class="form-label">Arrival City</label>
        <input type="text" name="arrival_city" class="form-control" placeholder="Paris"
               value="{{ old('arrival_city', $f?->arrival_city) }}">
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label">Departure Time</label>
        <input type="time" name="departure_time" class="form-control"
               value="{{ old('departure_time', $f?->departure_time) }}">
    </div>
    <div class="form-group">
        <label class="form-label">Arrival Time</label>
        <input type="time" name="arrival_time" class="form-control"
               value="{{ old('arrival_time', $f?->arrival_time) }}">
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label">Flight Number</label>
        <input type="text" name="flight_number" class="form-control" placeholder="TP1234"
               style="text-transform:uppercase;letter-spacing:0.05em"
               value="{{ old('flight_number', $f?->flight_number) }}">
    </div>
    <div class="form-group">
        <label class="form-label">Airline</label>
        <input type="text" name="airline" class="form-control" placeholder="TAP Air Portugal"
               value="{{ old('airline', $f?->airline) }}">
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label">Booking Ref / Locator</label>
        <input type="text" name="locator" class="form-control" placeholder="ABC123"
               style="text-transform:uppercase;letter-spacing:0.1em"
               value="{{ old('locator', $f?->locator) }}">
    </div>
    <div class="form-group">
        <label class="form-label">Duration (minutes)</label>
        <input type="number" name="duration_minutes" class="form-control" placeholder="135" min="0"
               value="{{ old('duration_minutes', $f?->duration_minutes) }}">
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label">Seat</label>
        <input type="text" name="seat" class="form-control" placeholder="14A"
               style="text-transform:uppercase"
               value="{{ old('seat', $f?->seat) }}">
    </div>
    <div class="form-group">
        <label class="form-label">Cabin Class</label>
        <select name="cabin_class" class="form-control">
            <option value="">— Select —</option>
            <option value="economy" {{ old('cabin_class', $f?->cabin_class) === 'economy' ? 'selected' : '' }}>Economy</option>
            <option value="premium_economy" {{ old('cabin_class', $f?->cabin_class) === 'premium_economy' ? 'selected' : '' }}>Premium Economy</option>
            <option value="business" {{ old('cabin_class', $f?->cabin_class) === 'business' ? 'selected' : '' }}>Business</option>
            <option value="first" {{ old('cabin_class', $f?->cabin_class) === 'first' ? 'selected' : '' }}>First Class</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="form-label">Notes</label>
    <textarea name="notes" class="form-control" placeholder="Check-in closes 1h before departure, baggage allowance 23kg…" rows="2">{{ old('notes', $f?->notes) }}</textarea>
</div>
