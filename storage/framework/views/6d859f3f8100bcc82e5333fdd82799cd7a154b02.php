<?php

$progress_time_message = $minSaved.'  minutes saved in total';
$progress_wait_message = 'We are processing request. Please wait.';




?>



<?php if($progress_show): ?>

<template id="progress-template">
    <swal-html>
        <div class = "modal-text-header">
            <?php echo e($progress_header); ?>

        </div>
        <div class = "modal-message-container">
            <div class = "modal-message-text">
                <div class = "modal-message-text1"><?php echo e($progress_message1); ?></div>
                <div class = "modal-message-text2"><?php echo e($progress_message2); ?></div>
            </div>
            <div class = "modal-image-container">
                <img src="<?php echo e($api_endpoints['image']); ?>/uploads/media/profile_images/cropped/<?php echo e($salesperson->photo); ?>">
            </div>
        </div>
        <div class = "modal-message-text3">You've just save <b><?php echo e($saved_time); ?> minutes</b>.</div>
        <div class = "modal-message-text3"><?php echo e($progress_text2); ?></div>
        <div class = "modal-message-text4"><?php echo e($progress_time_message); ?></div>
        <div class = "modal-message-text5">
            <i style="color: #0095ff;" class="fas fa-info-circle"></i>
            <?php echo e($progress_wait_message); ?>

        </div>


    </swal-html>
</template>

<?php endif; ?>
<?php /**PATH D:\OpenServer\domains\ttt\resources\views/wizard/pages/partials/progress-modal.blade.php ENDPATH**/ ?>