<?php
    $cabinLabels = ['economy'=>__('flights.cabin.economy'),'premium_economy'=>__('flights.cabin.premium_economy'),'business'=>__('flights.cabin.business'),'first'=>__('flights.cabin.first')];
    $flDurH = intdiv($flight->duration_minutes ?? 0, 60);
    $flDurM = ($flight->duration_minutes ?? 0) % 60;
?>
<div class="flight-card" id="flight-card-<?php echo e($flight->id); ?>">
    <div class="flight-card-header">
        <div class="flex gap-1" style="align-items:center;flex-wrap:wrap">
            <span style="font-size:1.1rem">✈</span>
            <?php if($flight->flight_number): ?><span style="font-weight:700;letter-spacing:0.05em"><?php echo e($flight->flight_number); ?></span><?php endif; ?>
            <?php if($flight->airline): ?><span style="opacity:0.8;font-size:0.85rem"><?php echo e($flight->airline); ?></span><?php endif; ?>
        </div>
        <div class="flex gap-1">
            <button onclick="openFlightModal(<?php echo e($flight->day_id); ?>, <?php echo e($flight->id); ?>)" class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:white;border:1px solid rgba(255,255,255,0.25)"><?php echo e(__('trips.show.edit')); ?></button>
            <form method="POST" action="<?php echo e(route('flights.destroy', $flight)); ?>" data-confirm="<?php echo e(__('flights.confirm.remove_flight')); ?>" onclick="event.stopPropagation()">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="btn btn-sm" style="background:rgba(193,68,14,0.3);color:white;border:1px solid rgba(193,68,14,0.5)">✕</button>
            </form>
        </div>
    </div>
    <div class="flight-route">
        <div class="flight-airport-block">
            <div class="flight-airport-code"><?php echo e(strtoupper($flight->departure_airport)); ?></div>
            <?php if($flight->departure_city): ?><div class="flight-airport-city"><?php echo e($flight->departure_city); ?></div><?php endif; ?>
            <?php if($flight->departure_time): ?><div class="flight-time"><?php echo e($flight->departure_time); ?></div><?php endif; ?>
        </div>
        <div class="flight-line">
            <?php if($flight->duration_minutes): ?>
                <div class="flight-duration-label"><?php echo e($flDurH > 0 ? $flDurH.'h '.$flDurM.'min' : $flDurM.'min'); ?></div>
            <?php endif; ?>
            <div class="flight-line-bar"></div>
        </div>
        <div class="flight-airport-block">
            <div class="flight-airport-code"><?php echo e(strtoupper($flight->arrival_airport)); ?></div>
            <?php if($flight->arrival_city): ?><div class="flight-airport-city"><?php echo e($flight->arrival_city); ?></div><?php endif; ?>
            <?php if($flight->arrival_time): ?><div class="flight-time"><?php echo e($flight->arrival_time); ?></div><?php endif; ?>
        </div>
    </div>
    <div class="flight-details">
        <?php if($flight->locator): ?><span class="badge badge-blue">🎫 <?php echo e(strtoupper($flight->locator)); ?></span><?php endif; ?>
        <?php if($flight->seat): ?><span class="badge badge-purple">💺 <?php echo e($flight->seat); ?></span><?php endif; ?>
        <?php if($flight->cabin_class): ?><span class="badge badge-gold"><?php echo e($cabinLabels[$flight->cabin_class] ?? $flight->cabin_class); ?></span><?php endif; ?>
        <?php if($flight->notes): ?><span class="text-sm text-muted">📝 <?php echo e($flight->notes); ?></span><?php endif; ?>
    </div>
</div>
<?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/trips/_flight_card.blade.php ENDPATH**/ ?>