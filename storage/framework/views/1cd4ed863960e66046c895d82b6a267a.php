<?php $__env->startSection('title', 'Edit Trip'); ?>
<?php $__env->startSection('content'); ?>
<div style="max-width:600px;margin:0 auto">
    <a href="<?php echo e(route('trips.show', $trip)); ?>" class="text-muted text-sm">← Back to trip</a>
    <h1 class="page-title mt-2">Edit Trip</h1>
    <div class="card mt-2">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('trips.update', $trip)); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="form-group">
                    <label class="form-label">Trip Name *</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $trip->name)); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control"><?php echo e(old('description', $trip->description)); ?></textarea>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="<?php echo e(old('start_date', $trip->start_date?->format('Y-m-d'))); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="<?php echo e(old('end_date', $trip->end_date?->format('Y-m-d'))); ?>">
                    </div>
                </div>
                <div class="flex gap-1">
                    <a href="<?php echo e(route('trips.show', $trip)); ?>" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
            <hr class="divider">
            <h3 style="font-size:1rem;font-weight:600;color:var(--danger);margin-bottom:0.75rem">Danger Zone</h3>
            <form method="POST" action="<?php echo e(route('trips.destroy', $trip)); ?>" onsubmit="return confirm('Delete this trip permanently?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger btn-sm">Delete Trip</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/trips/edit.blade.php ENDPATH**/ ?>