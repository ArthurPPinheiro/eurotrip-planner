<?php $__env->startSection('title', 'Documents — '.$trip->name); ?>
<?php $__env->startSection('content'); ?>

<div class="flex-between mb-3">
    <div>
        <a href="<?php echo e(route('trips.show', $trip)); ?>" class="text-muted text-sm">← Back to itinerary</a>
        <h1 class="page-title mt-1">📂 Documents</h1>
        <p class="page-subtitle"><?php echo e($trip->name); ?></p>
    </div>
    <button onclick="openModal('uploadModal')" class="btn btn-primary">+ Upload Document</button>
</div>

<!-- Tabs -->
<div class="flex gap-1 mb-3">
    <a href="<?php echo e(route('trips.show', $trip)); ?>" class="btn btn-outline btn-sm">🗺️ Itinerary</a>
    <a href="<?php echo e(route('documents.index', $trip)); ?>" class="btn btn-primary btn-sm">📂 Documents</a>
</div>

<?php if($documents->isEmpty()): ?>
    <div class="card">
        <div class="empty-state">
            <span class="emoji">📁</span>
            <h3 style="font-family:'Playfair Display',serif;font-size:1.4rem;margin-bottom:0.5rem">No documents yet</h3>
            <p class="text-muted">Upload passports, visas, insurance, tickets — everything in one place</p>
            <button onclick="openModal('uploadModal')" class="btn btn-primary mt-2">Upload First Document</button>
        </div>
    </div>
<?php else: ?>
    <?php $__currentLoopData = ['passport' => '🛂 Passports', 'visa' => '📋 Visas', 'insurance' => '🏥 Insurance', 'ticket' => '✈️ Tickets', 'other' => '📄 Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                            <div class="badge badge-gold mb-2">Expires: <?php echo e($doc->expires_at); ?></div>
                        <?php endif; ?>
                        <?php if($doc->notes): ?>
                            <p class="text-sm text-muted mb-2"><?php echo e($doc->notes); ?></p>
                        <?php endif; ?>
                        <div class="text-sm text-muted mb-2"><?php echo e($doc->original_name); ?> · <?php echo e($doc->formattedSize()); ?></div>
                        <div class="flex gap-1">
                            <a href="<?php echo e(route('documents.download', $doc)); ?>" class="btn btn-sm btn-outline">⬇ Download</a>
                            <?php if(auth()->id() === $doc->user_id): ?>
                            <form method="POST" action="<?php echo e(route('documents.destroy', $doc)); ?>" onsubmit="return confirm('Delete this document?')">
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
            <h3>Upload Document</h3>
            <button class="modal-close" onclick="closeModal('uploadModal')">×</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?php echo e(route('documents.store', $trip)); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label">Document Type *</label>
                    <select name="type" class="form-control" required>
                        <option value="passport">🛂 Passport</option>
                        <option value="visa">📋 Visa</option>
                        <option value="insurance">🏥 Insurance</option>
                        <option value="ticket">✈️ Ticket / Booking</option>
                        <option value="other">📄 Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g. John's Passport, Travel Insurance">
                </div>
                <div class="form-group">
                    <label class="form-label">File * (max 10MB)</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expires_at" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" placeholder="Any additional notes..."></textarea>
                </div>
                <div class="flex gap-1">
                    <button type="button" onclick="closeModal('uploadModal')" class="btn btn-outline">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/documents/index.blade.php ENDPATH**/ ?>