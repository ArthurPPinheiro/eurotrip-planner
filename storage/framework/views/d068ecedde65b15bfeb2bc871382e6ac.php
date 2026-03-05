<div id="flight-edit-modal-<?php echo e($flight->id); ?>" class="modal-backdrop">
    <div class="modal" style="max-width:580px">
        <div class="modal-header">
            <h3><?php echo e(__('flights.modal.edit_title', ['number' => $day->day_number])); ?></h3>
            <button class="modal-close" onclick="closeModal('flight-edit-modal-<?php echo e($flight->id); ?>')">×</button>
        </div>
        <div class="modal-body">
            <form id="flight-edit-form-<?php echo e($flight->id); ?>" method="POST" action="<?php echo e(route('flights.update', $flight)); ?>"
                  data-ajax
                  data-ajax-handler="handleFlightEditSuccess"
                  data-ajax-no-clear>
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <?php echo $__env->make('trips._flight_form', ['f' => $flight], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="flex gap-1">
                    <button type="button" onclick="closeModal('flight-edit-modal-<?php echo e($flight->id); ?>')" class="btn btn-outline"><?php echo e(__('general.btn.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(__('general.btn.save_changes')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/trips/_flight_edit_modal.blade.php ENDPATH**/ ?>