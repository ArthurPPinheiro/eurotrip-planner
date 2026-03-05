<?php if($day->route): ?>
    <?php
        $routeStops = $day->route->stops->map(fn($s) => [
            'city' => $s->city, 'country' => $s->country,
            'latitude' => (float)$s->latitude, 'longitude' => (float)$s->longitude
        ])->toArray();
        $rh = intdiv($day->route->total_duration_minutes ?? 0, 60);
        $rm = ($day->route->total_duration_minutes ?? 0) % 60;
        $modeIcon = ['car'=>'🚗','bus'=>'🚌','train'=>'🚂'][$day->route->transport_mode] ?? '🚗';
        $modeLabel = ['car'=>__('routes.mode.car'),'bus'=>__('routes.mode.bus'),'train'=>__('routes.mode.train')][$day->route->transport_mode] ?? __('routes.mode.car');
    ?>
    <div class="route-card">
        <div class="route-summary-bar">
            <span style="font-weight:600"><?php echo e($modeIcon); ?> <?php echo e($modeLabel); ?></span>
            <?php if($day->route->total_distance_km): ?>
                <span class="badge badge-blue">📏 <?php echo e(number_format($day->route->total_distance_km, 1)); ?> km</span>
            <?php endif; ?>
            <?php if($day->route->total_duration_minutes): ?>
                <span class="badge badge-gold">⏱ <?php echo e($rh > 0 ? $rh.'h '.$rm.'min' : $rm.'min'); ?></span>
            <?php endif; ?>
        </div>
        <div style="display:flex;align-items:center;gap:0.3rem;flex-wrap:wrap;margin-bottom:0.75rem;font-size:0.85rem;color:var(--muted)">
            <?php $__currentLoopData = $day->route->stops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(!$loop->first): ?><span style="opacity:0.5">→</span><?php endif; ?>
                <span><?php echo e($stop->city); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="route-map"
             id="route-map-<?php echo e($day->id); ?>"
             data-day-id="<?php echo e($day->id); ?>"
             data-stops="<?php echo e(json_encode($routeStops)); ?>"
             data-mode="<?php echo e($day->route->transport_mode); ?>"></div>
        <div class="flex gap-1" style="margin-top:0.75rem">
            <button
                data-day="<?php echo e($day->id); ?>"
                data-stops="<?php echo e(json_encode($routeStops)); ?>"
                data-mode="<?php echo e($day->route->transport_mode); ?>"
                onclick="openRouteModalFromEl(this)"
                class="btn btn-sm btn-outline"><?php echo e(__('routes.edit_route')); ?></button>
            <form method="POST" action="<?php echo e(route('routes.destroy', $day->route)); ?>" data-confirm="<?php echo e(__('routes.confirm_remove')); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="btn btn-sm btn-ghost" style="color:var(--danger)"><?php echo e(__('routes.remove_route')); ?></button>
            </form>
        </div>
    </div>
<?php else: ?>
    <div style="margin-bottom:1rem">
        <button
            data-day="<?php echo e($day->id); ?>"
            onclick="openRouteModalFromEl(this)"
            class="btn btn-sm btn-outline"><?php echo e(__('routes.add_route')); ?></button>
    </div>
<?php endif; ?>
<?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/trips/_route_section.blade.php ENDPATH**/ ?>