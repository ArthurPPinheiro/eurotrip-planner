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
            <div class="text-sm text-muted mt-1"><?php echo e(__('general.label.added_by', ['name' => $act->author->name])); ?></div>
        </div>
    </div>
    <form method="POST" action="<?php echo e(route('activities.destroy', $act)); ?>" data-confirm="<?php echo e(__('trips.show.confirm_remove_this')); ?>">
        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
        <button class="btn btn-sm btn-ghost" style="color:var(--danger);padding:0.2rem 0.5rem">✕</button>
    </form>
</div>
<?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/trips/_activity_item.blade.php ENDPATH**/ ?>