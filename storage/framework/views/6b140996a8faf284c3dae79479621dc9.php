<?php $__env->startSection('title', $trip->name); ?>
<?php $__env->startSection('content'); ?>

<div style="background:linear-gradient(135deg,var(--ink),#2d2d4e);border-radius:16px;padding:2rem 2.5rem;margin-bottom:2rem;position:relative;overflow:hidden;color:white">
    <div style="position:absolute;right:-10px;top:50%;transform:translateY(-50%);font-size:8rem;opacity:0.06">✈</div>
    <div class="flex-between">
        <div>
            <h1 style="font-family:'Playfair Display',serif;font-size:2rem;margin-bottom:0.4rem"><?php echo e($trip->name); ?></h1>
            <?php if($trip->description): ?><p style="opacity:0.7;font-size:0.9rem"><?php echo e($trip->description); ?></p><?php endif; ?>
            <div class="flex gap-2 mt-2" style="font-size:0.85rem;opacity:0.8;flex-wrap:wrap">
                <?php if($trip->start_date): ?><span>📅 <?php echo e($trip->start_date->format('M d')); ?> – <?php echo e($trip->end_date?->format('M d, Y') ?? '?'); ?></span><?php endif; ?>
                <span>🗓️ <?php echo e($trip->days->count()); ?> days planned</span>
                <span>👥 <?php echo e($trip->members->count()); ?> travellers</span>
            </div>
        </div>
        <div class="flex gap-1" style="align-items:flex-start">
            <a href="<?php echo e(route('trips.edit', $trip)); ?>" class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:white;border:1px solid rgba(255,255,255,0.25)">✏️ Edit</a>
            <a href="<?php echo e(route('trips.index')); ?>" class="btn btn-sm btn-ghost" style="color:rgba(255,255,255,0.6)">← Back</a>
        </div>
    </div>
    <div class="flex-between mt-3" style="align-items:center">
        <div class="flex gap-1">
            <?php $__currentLoopData = $trip->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="avatar" title="<?php echo e($m->name); ?>" style="background:<?php echo e($m->avatar_color); ?>;width:30px;height:30px;font-size:0.65rem;border:2px solid rgba(255,255,255,0.3)"><?php echo e($m->initials()); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div style="background:rgba(255,255,255,0.1);border-radius:8px;padding:0.4rem 0.875rem;font-size:0.8rem;color:rgba(255,255,255,0.8)">
            Invite code: <strong style="letter-spacing:0.1em;color:var(--gold-light)"><?php echo e($trip->invite_code); ?></strong>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="flex gap-1 mb-3">
    <a href="<?php echo e(route('trips.show', $trip)); ?>" class="btn btn-primary btn-sm">🗺️ Itinerary</a>
    <a href="<?php echo e(route('documents.index', $trip)); ?>" class="btn btn-outline btn-sm">📂 Documents</a>

    <!-- Add Day -->
    <form method="POST" action="<?php echo e(route('trips.addDay', $trip)); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-gold">+ Add Day</button>
    </form>
</div>



<?php $__env->startPush('styles'); ?>
<style>
.accordion-day { border-radius: 12px; overflow: hidden; box-shadow: var(--shadow); margin-bottom: 0.75rem; }
.accordion-trigger {
    width: 100%; background: linear-gradient(90deg, var(--ink), #2d2d4e);
    color: white; border: none; padding: 1rem 1.5rem;
    display: flex; align-items: center; justify-content: space-between;
    cursor: pointer; font-family: inherit; text-align: left; gap: 1rem;
    transition: background 0.2s;
}
.accordion-trigger:hover { background: linear-gradient(90deg, #2d2d4e, #3a3a6e); }
.accordion-trigger.open { background: linear-gradient(90deg, #2d2d4e, #3a3a6e); }
.accordion-chevron {
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(255,255,255,0.15); display: flex; align-items: center;
    justify-content: center; font-size: 0.7rem; flex-shrink: 0;
    transition: transform 0.3s ease;
}
.accordion-trigger.open .accordion-chevron { transform: rotate(180deg); }
.accordion-body {
    background: white; max-height: 0; overflow: hidden;
    transition: max-height 0.35s ease, padding 0.2s;
}
.accordion-body.open { max-height: 9999px; }
.accordion-inner { padding: 1.25rem; }
.day-summary-pills { display: flex; gap: 0.4rem; flex-wrap: wrap; }
.day-pill { background: rgba(255,255,255,0.12); border-radius: 20px; padding: 0.15rem 0.6rem; font-size: 0.75rem; }
.day-delete-btn { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: rgba(255,255,255,0.55); border-radius: 6px; padding: 0.25rem 0.55rem; cursor: pointer; font-size: 0.8rem; line-height: 1; transition: all 0.2s; }
.day-delete-btn:hover { background: rgba(193,68,14,0.6); border-color: transparent; color: white; }
.day-delete-btn {
    background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.7); border-radius: 6px; padding: 0.2rem 0.55rem;
    cursor: pointer; font-size: 0.8rem; line-height: 1; transition: all 0.2s; font-family: inherit;
}
.day-delete-btn:hover { background: rgba(193,68,14,0.65); border-color: rgba(193,68,14,0.9); color: white; }
</style>
<?php $__env->stopPush(); ?>

<!-- Days -->
<?php if($trip->days->isEmpty()): ?>
    <div class="card">
        <div class="empty-state">
            <span class="emoji">🗓️</span>
            <p>No days yet — click "Add Day" to start building your itinerary!</p>
        </div>
    </div>
<?php else: ?>
    <div style="display:flex;flex-direction:column;gap:0.75rem">
        <?php $__currentLoopData = $trip->days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="accordion-day">

            
            <span class="accordion-trigger <?php echo e($loop->first ? 'open' : ''); ?>" onclick="toggleAccordion(<?php echo e($day->id); ?>)">
                <div style="flex:1;min-width:0">
                    <div style="font-family:'Playfair Display',serif;font-size:1.05rem;margin-bottom:0.25rem">
                        Day <?php echo e($day->day_number); ?>

                        <?php if($day->title): ?> — <?php echo e($day->title); ?><?php endif; ?>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap">
                        <span style="font-size:0.78rem;opacity:0.65"><?php echo e($day->date->format('l, M d, Y')); ?></span>
                        <?php if($day->destinations->count()): ?>
                            <div class="day-summary-pills">
                                <?php $__currentLoopData = $day->destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="day-pill"><?php echo e($d->emoji); ?> <?php echo e($d->city); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <span style="font-size:0.78rem;opacity:0.45;font-style:italic">No cities yet</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem;flex-shrink:0">
                    <?php if($day->destinations->flatMap->activities->count()): ?>
                        <span style="font-size:0.75rem;opacity:0.6"><?php echo e($day->destinations->flatMap->activities->count()); ?> items</span>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('days.destroy', $day)); ?>"
                          onsubmit="event.stopPropagation(); return confirm('Delete Day <?php echo e($day->day_number); ?> and all its cities?')"
                          onclick="event.stopPropagation()"
                          class="day-delete-form">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" title="Delete day" class="day-delete-btn">✕</button>
                    </form>
                    <span class="accordion-chevron">▼</span>
                </div>
            </span>

            
            <div id="accordion-body-<?php echo e($day->id); ?>" class="accordion-body <?php echo e($loop->first ? 'open' : ''); ?>">
                <div class="accordion-inner">

                    
                    <div style="display:flex;justify-content:flex-end;margin-bottom:1rem">
                        <button onclick="openModal('dest-modal-<?php echo e($day->id); ?>')" class="btn btn-sm btn-gold">+ Add City</button>
                    </div>

                    <?php if($day->destinations->isEmpty()): ?>
                        <div style="text-align:center;padding:1.5rem 0;color:var(--muted);font-size:0.9rem">
                            No cities added yet for this day.
                        </div>
                    <?php else: ?>
                        <div style="display:flex;flex-direction:column;gap:1rem">
                            <?php $__currentLoopData = $day->destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div style="border:1.5px solid var(--cream);border-radius:10px;overflow:hidden">
                                <div style="background:var(--cream);padding:0.75rem 1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem">
                                    <div class="flex gap-1" style="align-items:center">
                                        <span style="font-size:1.8rem"><?php echo e($dest->emoji); ?></span>
                                        <div>
                                            <div style="font-weight:600;font-size:1rem"><?php echo e($dest->city); ?></div>
                                            <div class="text-sm text-muted"><?php echo e($dest->country); ?></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-1">
                                        <button onclick="openModal('act-modal-<?php echo e($dest->id); ?>')" class="btn btn-sm btn-primary">+ Add Item</button>
                                        <form method="POST" action="<?php echo e(route('destinations.destroy', $dest)); ?>" onsubmit="return confirm('Remove <?php echo e($dest->city); ?>?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-ghost" style="color:var(--danger)">✕</button>
                                        </form>
                                    </div>
                                </div>

                                <?php if($dest->activities->count()): ?>
                                <div style="padding:0.75rem 1.25rem;display:flex;flex-direction:column;gap:0.25rem">
                                    <?php $__currentLoopData = $dest->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;padding:0.6rem 0;border-bottom:1px solid var(--cream)">
                                        <div class="flex gap-1" style="align-items:flex-start;flex:1">
                                            <span style="font-size:1.1rem;flex-shrink:0;margin-top:2px"><?php echo e($act->typeIcon()); ?></span>
                                            <div style="flex:1">
                                                <div style="font-weight:500;font-size:0.9rem"><?php echo e($act->title); ?></div>
                                                <?php if($act->description): ?><div class="text-sm text-muted"><?php echo e($act->description); ?></div><?php endif; ?>
                                                <div class="flex gap-1 mt-1" style="flex-wrap:wrap;align-items:center">
                                                    <?php if($act->time): ?><span class="badge badge-blue">🕐 <?php echo e($act->time); ?></span><?php endif; ?>
                                                    <?php if($act->address): ?><span class="text-sm text-muted">📍 <?php echo e($act->address); ?></span><?php endif; ?>
                                                    <?php if($act->price): ?><span class="badge badge-green"><?php echo e($act->currency); ?> <?php echo e(number_format($act->price, 2)); ?></span><?php endif; ?>
                                                    <?php if($act->link): ?><a href="<?php echo e($act->link); ?>" target="_blank" class="text-sm" style="color:var(--accent)">🔗 Link</a><?php endif; ?>
                                                </div>
                                                <div class="text-sm text-muted mt-1">Added by <?php echo e($act->author->name); ?></div>
                                            </div>
                                        </div>
                                        <form method="POST" action="<?php echo e(route('activities.destroy', $act)); ?>" onsubmit="return confirm('Remove this?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-ghost" style="color:var(--danger);padding:0.2rem 0.5rem">✕</button>
                                        </form>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php else: ?>
                                    <div style="padding:0.875rem 1.25rem;font-size:0.85rem;color:var(--muted);font-style:italic">No items yet — add a hotel, POI, or note.</div>
                                <?php endif; ?>
                            </div>

                            
                            <div id="act-modal-<?php echo e($dest->id); ?>" class="modal-backdrop">
                                <div class="modal">
                                    <div class="modal-header">
                                        <h3>Add to <?php echo e($dest->city); ?></h3>
                                        <button class="modal-close" onclick="closeModal('act-modal-<?php echo e($dest->id); ?>')">×</button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="<?php echo e(route('activities.store', $dest)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <div class="form-group">
                                                <label class="form-label">Type</label>
                                                <select name="type" class="form-control" required>
                                                    <option value="poi">📍 Point of Interest</option>
                                                    <option value="hotel">🏨 Hotel / Accommodation</option>
                                                    <option value="reservation">🎟️ Reservation</option>
                                                    <option value="comment">💬 Comment / Note</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Title *</label>
                                                <input type="text" name="title" class="form-control" required placeholder="e.g. Eiffel Tower">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" placeholder="Any details..."></textarea>
                                            </div>
                                            <div class="grid-2">
                                                <div class="form-group">
                                                    <label class="form-label">Time</label>
                                                    <input type="text" name="time" class="form-control" placeholder="e.g. 10:00 AM">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Price</label>
                                                    <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Address</label>
                                                <input type="text" name="address" class="form-control" placeholder="Street, City...">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Link / URL</label>
                                                <input type="url" name="link" class="form-control" placeholder="https://...">
                                            </div>
                                            <input type="hidden" name="currency" value="EUR">
                                            <div class="flex gap-1">
                                                <button type="button" onclick="closeModal('act-modal-<?php echo e($dest->id); ?>')" class="btn btn-outline">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Add Item</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div id="dest-modal-<?php echo e($day->id); ?>" class="modal-backdrop">
            <div class="modal">
                <div class="modal-header">
                    <h3>Add City — Day <?php echo e($day->day_number); ?></h3>
                    <button class="modal-close" onclick="closeModal('dest-modal-<?php echo e($day->id); ?>')">×</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo e(route('destinations.store', $day)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">City *</label>
                                <input type="text" name="city" class="form-control" required placeholder="Paris">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Country *</label>
                                <input type="text" name="country" class="form-control" required placeholder="France">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Emoji Flag</label>
                            <input type="text" name="emoji" class="form-control" placeholder="🇫🇷" style="font-size:1.4rem;width:80px;text-align:center">
                        </div>
                        <div class="flex gap-1">
                            <button type="button" onclick="closeModal('dest-modal-<?php echo e($day->id); ?>')" class="btn btn-outline">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add City</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleAccordion(dayId) {
    const trigger = document.querySelector(`[onclick="toggleAccordion(${dayId})"]`);
    const body = document.getElementById(`accordion-body-${dayId}`);
    trigger.classList.toggle('open');
    body.classList.toggle('open');
}



</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/trips/show.blade.php ENDPATH**/ ?>