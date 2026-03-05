<?php $dayIsPast = $day->date->lt(today()); ?>
<div class="accordion-day" id="day-accordion-<?php echo e($day->id); ?>" style="<?php echo e($dayIsPast ? 'opacity:0.55;filter:grayscale(0.35)' : ''); ?>">

    
    <span class="accordion-trigger <?php echo e(($open ?? false) ? 'open' : ''); ?>" onclick="toggleAccordion(<?php echo e($day->id); ?>)">
        <div style="flex:1;min-width:0">
            <div style="font-family:'Playfair Display',serif;font-size:1.05rem;margin-bottom:0.25rem">
                <?php echo e($day->date->translatedFormat('l, M d')); ?><?php echo e($dayIsPast ? ' · 🏁' : ''); ?>

            </div>
            <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap">
                <span style="font-size:0.78rem;opacity:0.65"><?php echo e(__('trips.show.day_number', ['number' => $day->day_number])); ?><?php if($day->title): ?> · <?php echo e($day->title); ?><?php endif; ?></span>
                <?php if($day->flights->count() || $day->destinations->count()): ?>
                    <div class="day-summary-pills">
                        <?php $__currentLoopData = $day->flights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="day-pill">✈ <?php echo e($fl->departure_airport); ?> → <?php echo e($fl->arrival_airport); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $day->destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="day-pill"><?php echo e($d->emoji); ?> <?php echo e($d->city); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <span style="font-size:0.78rem;opacity:0.45;font-style:italic"><?php echo e(__('trips.show.no_cities')); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:0.75rem;flex-shrink:0">
            <?php if($day->route?->total_distance_km): ?>
                <span style="font-size:0.75rem;background:rgba(255,255,255,0.15);border-radius:20px;padding:0.15rem 0.55rem;white-space:nowrap">
                    <?php echo e(['car'=>'🚗','bus'=>'🚌','train'=>'🚂'][$day->route->transport_mode] ?? '🗺️'); ?> <?php echo e(number_format($day->route->total_distance_km, 1)); ?> km
                </span>
            <?php endif; ?>
            <?php if($day->destinations->flatMap->activities->count()): ?>
                <span style="font-size:0.75rem;opacity:0.6"><?php echo e(__('general.label.items', ['count' => $day->destinations->flatMap->activities->count()])); ?></span>
            <?php endif; ?>
            <form method="POST" action="<?php echo e(route('days.destroy', $day)); ?>"
                  data-confirm="<?php echo e(__('trips.show.confirm_delete_day', ['number' => $day->day_number])); ?>"
                  onsubmit="event.stopPropagation()"
                  onclick="event.stopPropagation()"
                  class="day-delete-form">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" title="Delete day" class="day-delete-btn">✕</button>
            </form>
            <span class="accordion-chevron">▼</span>
        </div>
    </span>

    
    <div id="accordion-body-<?php echo e($day->id); ?>" class="accordion-body <?php echo e(($open ?? false) ? 'open' : ''); ?>">
        <div class="accordion-inner">

            
            <div id="route-section-<?php echo e($day->id); ?>">
                <?php echo $__env->make('trips._route_section', ['day' => $day], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            
            <div id="flights-container-<?php echo e($day->id); ?>">
                <?php $__currentLoopData = $day->flights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('trips._flight_card', ['flight' => $flight], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div style="display:flex;justify-content:flex-end;gap:0.5rem;margin-bottom:1rem">
                <button onclick="openFlightModal(<?php echo e($day->id); ?>)" class="btn btn-sm btn-outline" style="border-color:#1e40af;color:#1e40af"><?php echo e(__('trips.show.add_flight')); ?></button>
                <button onclick="openModal('dest-modal-<?php echo e($day->id); ?>')" class="btn btn-sm btn-gold"><?php echo e(__('trips.show.add_city')); ?></button>
            </div>

            
            <div id="no-destinations-<?php echo e($day->id); ?>" style="<?php echo e($day->destinations->isNotEmpty() ? 'display:none;' : ''); ?>text-align:center;padding:1.5rem 0;color:var(--muted);font-size:0.9rem">
                <?php echo e(__('trips.show.no_cities_day')); ?>

            </div>
            <div id="destinations-list-<?php echo e($day->id); ?>" style="display:flex;flex-direction:column;gap:1rem">
                <?php $__currentLoopData = $day->destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('trips._destination_card', ['dest' => $dest], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

        </div>
    </div>

    
    <div id="dest-modal-<?php echo e($day->id); ?>" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <h3><?php echo e(__('trips.modal.add_city_day', ['number' => $day->day_number])); ?></h3>
                <button class="modal-close" onclick="closeModal('dest-modal-<?php echo e($day->id); ?>')">×</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php echo e(route('destinations.store', $day)); ?>"
                      data-ajax
                      data-ajax-target="#destinations-list-<?php echo e($day->id); ?>"
                      data-ajax-insert="beforeend"
                      data-ajax-hide="#no-destinations-<?php echo e($day->id); ?>"
                      data-ajax-keep-open>
                    <?php echo csrf_field(); ?>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('trips.modal.city')); ?></label>
                            <input type="text" name="city" class="form-control" required placeholder="<?php echo e(__('trips.modal.city_placeholder')); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('trips.modal.country')); ?></label>
                            <input type="text" name="country" class="form-control" required placeholder="<?php echo e(__('trips.modal.country_placeholder')); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('trips.modal.emoji_flag')); ?></label>
                        <input type="text" name="emoji" class="form-control" placeholder="🇫🇷" style="font-size:1.4rem;width:80px;text-align:center">
                    </div>
                    <div class="flex gap-1">
                        <button type="button" onclick="closeModal('dest-modal-<?php echo e($day->id); ?>')" class="btn btn-outline"><?php echo e(__('general.btn.cancel')); ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('trips.modal.add_city_btn')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div id="route-modal-<?php echo e($day->id); ?>" class="modal-backdrop">
        <div class="modal" style="max-width:640px">
            <div class="modal-header">
                <h3><?php echo e(__('routes.modal_title', ['number' => $day->day_number])); ?></h3>
                <button class="modal-close" onclick="closeModal('route-modal-<?php echo e($day->id); ?>')">×</button>
            </div>
            <div class="modal-body">
                <form method="POST"
                      id="route-form-<?php echo e($day->id); ?>"
                      action="<?php echo e($day->route ? route('routes.update', $day->route) : route('routes.store', $day)); ?>"
                      data-ajax
                      data-ajax-handler="handleRouteSuccess"
                      data-ajax-day-id="<?php echo e($day->id); ?>">
                    <?php echo csrf_field(); ?>
                    <?php if($day->route): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('routes.transport_mode')); ?></label>
                        <div class="flex gap-1" style="flex-wrap:wrap">
                            <button type="button" class="transport-btn" data-mode="car"
                                    onclick="setTransport(<?php echo e($day->id); ?>, 'car')">🚗 <?php echo e(__('routes.mode.car')); ?></button>
                            <button type="button" class="transport-btn" data-mode="bus"
                                    onclick="setTransport(<?php echo e($day->id); ?>, 'bus')">🚌 <?php echo e(__('routes.mode.bus')); ?></button>
                            <button type="button" class="transport-btn" data-mode="train"
                                    onclick="setTransport(<?php echo e($day->id); ?>, 'train')">🚂 <?php echo e(__('routes.mode.train')); ?></button>
                        </div>
                        <input type="hidden" name="transport_mode" id="transport-input-<?php echo e($day->id); ?>" value="car">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('routes.stops_label')); ?> <span style="font-weight:400;text-transform:none;letter-spacing:0;font-size:0.8rem"><?php echo e(__('routes.stops_hint')); ?></span></label>
                        <div id="stops-list-<?php echo e($day->id); ?>" style="display:flex;flex-direction:column;gap:0.5rem"></div>
                        <button type="button" onclick="addStop(<?php echo e($day->id); ?>)" class="btn btn-sm btn-ghost" style="margin-top:0.5rem"><?php echo e(__('routes.add_stop')); ?></button>
                    </div>

                    <div class="form-group">
                        <div id="route-preview-<?php echo e($day->id); ?>" class="route-map-preview"></div>
                        <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap">
                            <button type="button" onclick="calculateRoute(<?php echo e($day->id); ?>)" class="btn btn-sm btn-outline"><?php echo e(__('routes.calculate_route')); ?></button>
                            <span id="route-calc-summary-<?php echo e($day->id); ?>" style="font-size:0.85rem;color:var(--muted)"></span>
                        </div>
                    </div>

                    <input type="hidden" name="total_distance_km" id="total-distance-<?php echo e($day->id); ?>">
                    <input type="hidden" name="total_duration_minutes" id="total-duration-<?php echo e($day->id); ?>">

                    <div class="flex gap-1">
                        <button type="button" onclick="closeModal('route-modal-<?php echo e($day->id); ?>')" class="btn btn-outline"><?php echo e(__('general.btn.cancel')); ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('routes.save_route')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <?php $__currentLoopData = $day->flights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('trips._flight_edit_modal', ['flight' => $flight, 'day' => $day], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <div id="flight-add-modal-<?php echo e($day->id); ?>" class="modal-backdrop">
        <div class="modal" style="max-width:580px">
            <div class="modal-header">
                <h3><?php echo e(__('flights.modal.add_title', ['number' => $day->day_number])); ?></h3>
                <button class="modal-close" onclick="closeModal('flight-add-modal-<?php echo e($day->id); ?>')">×</button>
            </div>
            <div class="modal-body">
                <form id="flight-add-form-<?php echo e($day->id); ?>" method="POST" action="<?php echo e(route('flights.store', $day)); ?>"
                      data-ajax
                      data-ajax-handler="handleFlightAddSuccess"
                      data-ajax-day-id="<?php echo e($day->id); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo $__env->make('trips._flight_form', ['f' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <div class="flex gap-1">
                        <button type="button" onclick="closeModal('flight-add-modal-<?php echo e($day->id); ?>')" class="btn btn-outline"><?php echo e(__('general.btn.cancel')); ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('flights.btn.add_flight')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/trips/_day_accordion.blade.php ENDPATH**/ ?>