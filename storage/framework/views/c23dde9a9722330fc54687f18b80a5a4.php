<?php $__env->startSection('title', __('trips.index.title')); ?>
<?php $__env->startSection('content'); ?>
<div class="flex-between mb-3">
    <div>
        <h1 class="page-title"><?php echo e(__('trips.index.title')); ?> ✈</h1>
        <p class="page-subtitle"><?php echo e(__('trips.index.subtitle')); ?></p>
    </div>
    <div class="flex gap-1">
        <button onclick="openModal('joinModal')" class="btn btn-outline"><?php echo e(__('trips.index.join_trip')); ?></button>
        <a href="<?php echo e(route('trips.create')); ?>" class="btn btn-primary"><?php echo e(__('trips.index.new_trip')); ?></a>
    </div>
</div>

<?php if($trips->isEmpty()): ?>
    <div class="card">
        <div class="empty-state">
            <span class="emoji">🗺️</span>
            <h3 style="font-family:'Playfair Display',serif;font-size:1.5rem;margin-bottom:0.5rem"><?php echo e(__('trips.index.no_trips')); ?></h3>
            <p class="text-muted"><?php echo e(__('trips.index.no_trips_hint')); ?></p>
            <div class="flex-center gap-1 mt-2" style="justify-content:center">
                <a href="<?php echo e(route('trips.create')); ?>" class="btn btn-primary"><?php echo e(__('trips.index.create_trip')); ?></a>
                <button onclick="openModal('joinModal')" class="btn btn-outline"><?php echo e(__('trips.index.join_trip')); ?></button>
            </div>
        </div>
    </div>
<?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem">
        <?php $__currentLoopData = $trips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $isPast = $trip->end_date?->lt(today()); ?>
        <a href="<?php echo e(route('trips.show', $trip)); ?>" style="<?php echo e($isPast ? 'opacity:0.55;filter:grayscale(0.4)' : ''); ?>">
            <div class="card" style="transition:transform 0.2s,box-shadow 0.2s" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 32px rgba(26,26,46,0.15)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                <div style="background:<?php echo e($isPast ? 'linear-gradient(135deg,#5a5a6e,#3a3a4e)' : 'linear-gradient(135deg,var(--ink),#2d2d4e)'); ?>;padding:1.5rem;position:relative;overflow:hidden">
                    <div style="position:absolute;right:-20px;top:-20px;font-size:5rem;opacity:0.08">✈</div>
                    <h3 style="font-family:'Playfair Display',serif;font-size:1.25rem;color:white;margin-bottom:0.25rem"><?php echo e($trip->name); ?></h3>
                    <?php if($trip->start_date): ?>
                        <p style="color:var(--gold-light);font-size:0.8rem">
                            <?php echo e($trip->start_date->translatedFormat('M d')); ?> — <?php echo e($trip->end_date?->translatedFormat('M d, Y') ?? '?'); ?>

                        </p>
                        <p style="color:var(--cream);font-size:0.8rem">
                            <?php if($isPast): ?> 🏁 <?php echo e(__('trips.index.trip_completed')); ?> <?php else: ?> ⏰ <?php echo e(__('trips.index.in_days', ['count' => $trip->getTimeUntilTrip()])); ?> <?php endif; ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div style="padding:1.25rem">
                    <?php if($trip->description): ?>
                        <p class="text-sm text-muted mb-2"><?php echo e(Str::limit($trip->description, 80)); ?></p>
                    <?php endif; ?>
                    <div class="flex-between">
                        <div class="flex" style="align-items:center">
                            <?php $__currentLoopData = $trip->members->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="avatar" title="<?php echo e($member->name); ?>" style="background:<?php echo e($member->avatar_color); ?>;width:28px;height:28px;font-size:0.65rem;margin-left:<?php echo e($loop->first ? '0' : '-6px'); ?>;border:2px solid white"><?php echo e($member->initials()); ?></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <span class="badge <?php echo e($trip->created_by === auth()->id() ? 'badge-gold' : 'badge-blue'); ?>">
                            <?php echo e($trip->created_by === auth()->id() ? __('general.label.owner') : __('general.label.member')); ?>

                        </span>
                    </div>
                </div>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<div id="joinModal" class="modal-backdrop">
    <div class="modal">
        <div class="modal-header">
            <h3><?php echo e(__('trips.join.title')); ?></h3>
            <button class="modal-close" onclick="closeModal('joinModal')">×</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?php echo e(route('trips.join')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('trips.join.invite_code')); ?></label>
                    <input type="text" name="invite_code" class="form-control" placeholder="<?php echo e(__('trips.join.placeholder')); ?>" style="text-transform:uppercase;letter-spacing:0.1em;font-size:1.1rem" required>
                    <p class="text-sm text-muted mt-1"><?php echo e(__('trips.join.hint')); ?></p>
                </div>
                <button type="submit" class="btn btn-primary"><?php echo e(__('trips.index.join_trip')); ?></button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/trips/index.blade.php ENDPATH**/ ?>