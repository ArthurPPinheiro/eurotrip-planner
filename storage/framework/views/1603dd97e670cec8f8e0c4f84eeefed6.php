<?php $__env->startSection('title', __('auth.login.title')); ?>
<?php $__env->startSection('content'); ?>
<div class="auth-card">
    <h2><?php echo e(__('auth.login.heading')); ?></h2>
    <form method="POST" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label class="form-label"><?php echo e(__('auth.login.email')); ?></label>
            <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required autofocus>
        </div>
        <div class="form-group">
            <label class="form-label"><?php echo e(__('auth.login.password')); ?></label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn-primary"><?php echo e(__('auth.login.submit')); ?></button>
    </form>
</div>
<div class="auth-footer">
    <?php echo e(__('auth.login.no_account')); ?> <a href="<?php echo e(route('register')); ?>"><?php echo e(__('auth.login.create_one')); ?></a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/auth/login.blade.php ENDPATH**/ ?>