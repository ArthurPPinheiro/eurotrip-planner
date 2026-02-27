
<div class="grid-2">
    <div class="form-group">
        <label class="form-label"><?php echo e(__('flights.departure_airport')); ?></label>
        <input type="text" name="departure_airport" class="form-control" required
               placeholder="LIS" style="text-transform:uppercase;letter-spacing:0.05em"
               value="<?php echo e(old('departure_airport', $f?->departure_airport)); ?>">
        <p class="form-error" style="font-size:0.75rem;color:var(--muted);margin-top:0.2rem"><?php echo e(__('flights.iata_hint')); ?></p>
    </div>
    <div class="form-group">
        <label class="form-label"><?php echo e(__('flights.arrival_airport')); ?></label>
        <input type="text" name="arrival_airport" class="form-control" required
               placeholder="CDG" style="text-transform:uppercase;letter-spacing:0.05em"
               value="<?php echo e(old('arrival_airport', $f?->arrival_airport)); ?>">
        <p class="form-error" style="font-size:0.75rem;color:var(--muted);margin-top:0.2rem"><?php echo e(__('flights.iata_hint')); ?></p>
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label"><?php echo e(__('flights.departure_city')); ?></label>
        <input type="text" name="departure_city" class="form-control" placeholder="Lisboa"
               value="<?php echo e(old('departure_city', $f?->departure_city)); ?>">
    </div>
    <div class="form-group">
        <label class="form-label"><?php echo e(__('flights.arrival_city')); ?></label>
        <input type="text" name="arrival_city" class="form-control" placeholder="Paris"
               value="<?php echo e(old('arrival_city', $f?->arrival_city)); ?>">
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label"><?php echo e(__('flights.departure_time')); ?></label>
        <input type="time" name="departure_time" class="form-control"
               value="<?php echo e(old('departure_time', $f?->departure_time)); ?>">
    </div>
    <div class="form-group">
        <label class="form-label"><?php echo e(__('flights.arrival_time')); ?></label>
        <input type="time" name="arrival_time" class="form-control"
               value="<?php echo e(old('arrival_time', $f?->arrival_time)); ?>">
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label"><?php echo e(__('flights.flight_number')); ?></label>
        <input type="text" name="flight_number" class="form-control" placeholder="TP1234"
               style="text-transform:uppercase;letter-spacing:0.05em"
               value="<?php echo e(old('flight_number', $f?->flight_number)); ?>">
    </div>
    <div class="form-group">
        <label class="form-label"><?php echo e(__('flights.airline')); ?></label>
        <input type="text" name="airline" class="form-control" placeholder="TAP Air Portugal"
               value="<?php echo e(old('airline', $f?->airline)); ?>">
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label"><?php echo e(__('flights.locator')); ?></label>
        <input type="text" name="locator" class="form-control" placeholder="ABC123"
               style="text-transform:uppercase;letter-spacing:0.1em"
               value="<?php echo e(old('locator', $f?->locator)); ?>">
    </div>
    <div class="form-group">
        <label class="form-label"><?php echo e(__('flights.duration')); ?></label>
        <input type="number" name="duration_minutes" class="form-control" placeholder="135" min="0"
               value="<?php echo e(old('duration_minutes', $f?->duration_minutes)); ?>">
    </div>
</div>
<div class="grid-2">
    <div class="form-group">
        <label class="form-label"><?php echo e(__('flights.seat')); ?></label>
        <input type="text" name="seat" class="form-control" placeholder="14A"
               style="text-transform:uppercase"
               value="<?php echo e(old('seat', $f?->seat)); ?>">
    </div>
    <div class="form-group">
        <label class="form-label"><?php echo e(__('flights.cabin_class')); ?></label>
        <select name="cabin_class" class="form-control">
            <option value=""><?php echo e(__('flights.cabin_select')); ?></option>
            <option value="economy" <?php echo e(old('cabin_class', $f?->cabin_class) === 'economy' ? 'selected' : ''); ?>><?php echo e(__('flights.cabin.economy')); ?></option>
            <option value="premium_economy" <?php echo e(old('cabin_class', $f?->cabin_class) === 'premium_economy' ? 'selected' : ''); ?>><?php echo e(__('flights.cabin.premium_economy')); ?></option>
            <option value="business" <?php echo e(old('cabin_class', $f?->cabin_class) === 'business' ? 'selected' : ''); ?>><?php echo e(__('flights.cabin.business')); ?></option>
            <option value="first" <?php echo e(old('cabin_class', $f?->cabin_class) === 'first' ? 'selected' : ''); ?>><?php echo e(__('flights.cabin.first')); ?></option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="form-label"><?php echo e(__('flights.notes')); ?></label>
    <textarea name="notes" class="form-control" placeholder="<?php echo e(__('flights.notes_placeholder')); ?>" rows="2"><?php echo e(old('notes', $f?->notes)); ?></textarea>
</div>
<?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/trips/_flight_form.blade.php ENDPATH**/ ?>