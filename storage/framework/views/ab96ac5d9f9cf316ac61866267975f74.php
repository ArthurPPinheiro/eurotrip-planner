<div id="destination-card-<?php echo e($dest->id); ?>" style="border:1.5px solid var(--cream);border-radius:10px;overflow:hidden">
    <div style="background:var(--cream);padding:0.75rem 1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem">
        <div class="flex gap-1" style="align-items:center">
            <span style="font-size:1.8rem"><?php echo e($dest->emoji); ?></span>
            <div>
                <div style="font-weight:600;font-size:1rem"><?php echo e($dest->city); ?></div>
                <div class="text-sm text-muted"><?php echo e($dest->country); ?></div>
            </div>
        </div>
        <div class="flex gap-1">
            <button onclick="openModal('act-modal-<?php echo e($dest->id); ?>')" class="btn btn-sm btn-primary"><?php echo e(__('trips.show.add_item')); ?></button>
            <form method="POST" action="<?php echo e(route('destinations.destroy', $dest)); ?>" data-confirm="<?php echo e(__('trips.show.confirm_remove_city', ['city' => $dest->city])); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="btn btn-sm btn-ghost" style="color:var(--danger)">✕</button>
            </form>
        </div>
    </div>

    <div id="no-activities-<?php echo e($dest->id); ?>" style="padding:0.875rem 1.25rem;font-size:0.85rem;color:var(--muted);font-style:italic;<?php echo e($dest->activities->count() ? 'display:none' : ''); ?>">
        <?php echo e(__('trips.show.no_items')); ?>

    </div>
    <div id="activities-list-<?php echo e($dest->id); ?>" style="padding:0.75rem 1.25rem;flex-direction:column;gap:0.25rem;display:<?php echo e($dest->activities->count() ? 'flex' : 'none'); ?>">
        <?php $__currentLoopData = $dest->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('trips._activity_item', ['act' => $act], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div id="act-modal-<?php echo e($dest->id); ?>" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <h3><?php echo e(__('trips.modal.add_to_city', ['city' => $dest->city])); ?></h3>
                <button class="modal-close" onclick="closeModal('act-modal-<?php echo e($dest->id); ?>')">×</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php echo e(route('activities.store', $dest)); ?>"
                      data-ajax
                      data-ajax-target="#activities-list-<?php echo e($dest->id); ?>"
                      data-ajax-insert="beforeend"
                      data-ajax-hide="#no-activities-<?php echo e($dest->id); ?>"
                      data-ajax-show="#activities-list-<?php echo e($dest->id); ?>"
                      data-ajax-show-display="flex"
                      data-ajax-keep-open>
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('general.label.type')); ?></label>
                        <select name="type" class="form-control" required>
                            <option value="poi">📍 <?php echo e(__('trips.activity_type.poi')); ?></option>
                            <option value="hotel">🏨 <?php echo e(__('trips.activity_type.hotel')); ?></option>
                            <option value="reservation">🎟️ <?php echo e(__('trips.activity_type.reservation')); ?></option>
                            <option value="comment">💬 <?php echo e(__('trips.activity_type.comment')); ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('trips.modal.title_label')); ?></label>
                        <input type="text" name="title" class="form-control" required placeholder="<?php echo e(__('trips.modal.title_placeholder')); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('general.label.description')); ?></label>
                        <textarea name="description" class="form-control" placeholder="<?php echo e(__('trips.modal.description_placeholder')); ?>"></textarea>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('general.label.time')); ?></label>
                            <input type="text" name="time" class="form-control" placeholder="<?php echo e(__('trips.modal.time_placeholder')); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('general.label.price')); ?></label>
                            <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('general.label.address')); ?></label>
                        <input type="text" name="address" class="form-control" placeholder="<?php echo e(__('trips.modal.address_placeholder')); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('general.label.link_url')); ?></label>
                        <input type="url" name="link" class="form-control" placeholder="https://...">
                    </div>
                    <input type="hidden" name="currency" value="EUR">
                    <div class="flex gap-1">
                        <button type="button" onclick="closeModal('act-modal-<?php echo e($dest->id); ?>')" class="btn btn-outline"><?php echo e(__('general.btn.cancel')); ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('trips.modal.add_item_btn')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/trips/_destination_card.blade.php ENDPATH**/ ?>