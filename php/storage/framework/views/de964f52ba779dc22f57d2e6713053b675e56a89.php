<?php $__env->startSection('content'); ?>
    <iframe 
        src="<?php echo e($iframe_url); ?>"
        width="100%"
        height="750"
        allow="camera"
        style="border:none;">
    </iframe>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/share/nginx/html/resources/views/session.blade.php ENDPATH**/ ?>