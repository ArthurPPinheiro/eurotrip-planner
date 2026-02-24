<?php $__env->startSection('title', 'My Trips'); ?>
<?php $__env->startSection('content'); ?>
<div class="flex-between mb-3">
    <div>
        <h1 class="page-title">My Trips ✈</h1>
        <p class="page-subtitle">Plan and explore Europe together</p>
    </div>
    <div class="flex gap-1">
        <button onclick="openModal('joinModal')" class="btn btn-outline">Join Trip</button>
        <a href="<?php echo e(route('trips.create')); ?>" class="btn btn-primary">+ New Trip</a>
    </div>
</div>

<?php if($trips->isEmpty()): ?>
    <div class="card">
        <div class="empty-state">
            <span class="emoji">🗺️</span>
            <h3 style="font-family:'Playfair Display',serif;font-size:1.5rem;margin-bottom:0.5rem">No trips yet</h3>
            <p class="text-muted">Create your first trip or join one with an invite code</p>
            <div class="flex-center gap-1 mt-2" style="justify-content:center">
                <a href="<?php echo e(route('trips.create')); ?>" class="btn btn-primary">Create Trip</a>
                <button onclick="openModal('joinModal')" class="btn btn-outline">Join Trip</button>
            </div>
        </div>
    </div>
<?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem">
        <?php $__currentLoopData = $trips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('trips.show', $trip)); ?>">
            <div class="card" style="transition:transform 0.2s,box-shadow 0.2s" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 32px rgba(26,26,46,0.15)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                <div style="background:linear-gradient(135deg,var(--ink),#2d2d4e);padding:1.5rem;position:relative;overflow:hidden">
                    <div style="position:absolute;right:-20px;top:-20px;font-size:5rem;opacity:0.08">✈</div>
                    <h3 style="font-family:'Playfair Display',serif;font-size:1.25rem;color:white;margin-bottom:0.25rem"><?php echo e($trip->name); ?></h3>
                    <?php if($trip->start_date): ?>
                        <p style="color:var(--gold-light);font-size:0.8rem">
                            <?php echo e($trip->start_date->format('M d')); ?> — <?php echo e($trip->end_date?->format('M d, Y') ?? '?'); ?>

                        </p>
                        <p style="color:var(--cream);font-size:0.8rem">
                            ⏰ In <?php echo e($trip->getTimeUntilTrip()); ?> Days
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
                            <?php echo e($trip->created_by === auth()->id() ? 'Owner' : 'Member'); ?>

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
            <h3>Join a Trip</h3>
            <button class="modal-close" onclick="closeModal('joinModal')">×</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?php echo e(route('trips.join')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label">Invite Code</label>
                    <input type="text" name="invite_code" class="form-control" placeholder="e.g. ABCD1234" style="text-transform:uppercase;letter-spacing:0.1em;font-size:1.1rem" required>
                    <p class="text-sm text-muted mt-1">Ask your trip organizer for the 8-character invite code.</p>
                </div>
                <button type="submit" class="btn btn-primary">Join Trip</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/trips/index.blade.php ENDPATH**/ ?>