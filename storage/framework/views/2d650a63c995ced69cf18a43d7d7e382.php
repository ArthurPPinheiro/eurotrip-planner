<?php $__env->startSection('title', 'New Trip'); ?>
<?php $__env->startSection('content'); ?>
<div style="max-width:600px;margin:0 auto">
    <a href="<?php echo e(route('trips.index')); ?>" class="text-muted text-sm">← Back to trips</a>
    <h1 class="page-title mt-2">Plan a New Trip</h1>
    <p class="page-subtitle mb-3">Fill in the details and start building your itinerary</p>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('trips.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label">Trip Name *</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required placeholder="e.g. Summer Euro 2025">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" placeholder="Tell your friends what this trip is about..."><?php echo e(old('description')); ?></textarea>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="<?php echo e(old('start_date')); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="<?php echo e(old('end_date')); ?>">
                    </div>
                </div>
                <p class="text-sm text-muted mb-3">If you set dates, the days will be auto-created for you.</p>
                <div class="flex gap-1">
                    <a href="<?php echo e(route('trips.index')); ?>" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Trip ✈</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/trips/create.blade.php ENDPATH**/ ?>