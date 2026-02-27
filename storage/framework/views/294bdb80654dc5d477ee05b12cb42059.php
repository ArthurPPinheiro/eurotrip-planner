<?php $__env->startSection('title', __('documents.title') . ' — ' . $trip->name); ?>
<?php $__env->startSection('content'); ?>

<div class="flex-between mb-3">
    <div>
        <a href="<?php echo e(route('trips.show', $trip)); ?>" class="text-muted text-sm"><?php echo e(__('documents.back')); ?></a>
        <h1 class="page-title mt-1">📂 <?php echo e(__('documents.title')); ?></h1>
        <p class="page-subtitle"><?php echo e($trip->name); ?></p>
    </div>
    <button onclick="openModal('uploadModal')" class="btn btn-primary"><?php echo e(__('documents.upload_btn')); ?></button>
</div>

<!-- Tabs -->
<div class="flex gap-1 mb-3">
    <a href="<?php echo e(route('trips.show', $trip)); ?>" class="btn btn-outline btn-sm">🗺️ <?php echo e(__('trips.show.itinerary')); ?></a>
    <a href="<?php echo e(route('documents.index', $trip)); ?>" class="btn btn-primary btn-sm">📂 <?php echo e(__('documents.title')); ?></a>
</div>

<?php if($documents->isEmpty()): ?>
    <div class="card">
        <div class="empty-state">
            <span class="emoji">📁</span>
            <h3 style="font-family:'Playfair Display',serif;font-size:1.4rem;margin-bottom:0.5rem"><?php echo e(__('documents.no_documents')); ?></h3>
            <p class="text-muted"><?php echo e(__('documents.no_documents_hint')); ?></p>
            <button onclick="openModal('uploadModal')" class="btn btn-primary mt-2"><?php echo e(__('documents.upload_first')); ?></button>
        </div>
    </div>
<?php else: ?>
    <?php $__currentLoopData = ['passport' => '🛂 ' . __('documents.group.passport'), 'visa' => '📋 ' . __('documents.group.visa'), 'insurance' => '🏥 ' . __('documents.group.insurance'), 'ticket' => '✈️ ' . __('documents.group.ticket'), 'other' => '📄 ' . __('documents.group.other')]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(isset($grouped[$type]) && $grouped[$type]->count()): ?>
        <div class="mb-3">
            <h2 style="font-family:'Playfair Display',serif;font-size:1.2rem;margin-bottom:1rem"><?php echo e($label); ?></h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem">
                <?php $__currentLoopData = $grouped[$type]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card">
                    <div style="padding:1.25rem">
                        <div class="flex-between mb-2">
                            <div class="flex gap-1" style="align-items:center">
                                <span style="font-size:1.5rem"><?php echo e($doc->typeIcon()); ?></span>
                                <div>
                                    <div style="font-weight:600;font-size:0.95rem"><?php echo e($doc->title); ?></div>
                                    <div class="text-sm text-muted"><?php echo e($doc->owner->name); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php if($doc->expires_at): ?>
                            <div class="badge badge-gold mb-2"><?php echo e(__('documents.expires', ['date' => $doc->expires_at])); ?></div>
                        <?php endif; ?>
                        <?php if($doc->notes): ?>
                            <p class="text-sm text-muted mb-2"><?php echo e($doc->notes); ?></p>
                        <?php endif; ?>
                        <div class="text-sm text-muted mb-2"><?php echo e($doc->original_name); ?> · <?php echo e($doc->formattedSize()); ?></div>
                        <div class="flex gap-1">
                            <a href="<?php echo e(route('documents.download', $doc)); ?>" class="btn btn-sm btn-outline"><?php echo e(__('documents.download')); ?></a>
                            <?php if(auth()->id() === $doc->user_id): ?>
                            <form method="POST" action="<?php echo e(route('documents.destroy', $doc)); ?>" data-confirm="<?php echo e(__('documents.confirm_delete')); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-ghost" style="color:var(--danger)">✕</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<!-- Upload Modal -->
<div id="uploadModal" class="modal-backdrop">
    <div class="modal">
        <div class="modal-header">
            <h3><?php echo e(__('documents.modal_title')); ?></h3>
            <button class="modal-close" onclick="closeModal('uploadModal')">×</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?php echo e(route('documents.store', $trip)); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('documents.type_label')); ?></label>
                    <select name="type" class="form-control" required>
                        <option value="passport">🛂 <?php echo e(__('documents.type.passport')); ?></option>
                        <option value="visa">📋 <?php echo e(__('documents.type.visa')); ?></option>
                        <option value="insurance">🏥 <?php echo e(__('documents.type.insurance')); ?></option>
                        <option value="ticket">✈️ <?php echo e(__('documents.type.ticket')); ?></option>
                        <option value="other">📄 <?php echo e(__('documents.type.other')); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('documents.title_label')); ?></label>
                    <input type="text" name="title" class="form-control" required placeholder="<?php echo e(__('documents.title_placeholder')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('documents.file_label')); ?></label>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('documents.expiry_date')); ?></label>
                    <input type="date" name="expires_at" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('general.label.notes')); ?></label>
                    <textarea name="notes" class="form-control" placeholder="<?php echo e(__('documents.notes_placeholder')); ?>"></textarea>
                </div>
                <div class="flex gap-1">
                    <button type="button" onclick="closeModal('uploadModal')" class="btn btn-outline"><?php echo e(__('general.btn.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(__('documents.upload_btn')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/documents/index.blade.php ENDPATH**/ ?>