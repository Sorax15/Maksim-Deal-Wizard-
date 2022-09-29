<div class="top-nav">
    <div class="row" style = "margin-right:0px">
        <div class = "col-md-10">
            <?php if(isset($hideNav) && $hideNav == 'vehicle_filter'): ?>
                <i style="cursor:pointer; font-size: 30px; color: white; float:left;padding-right: .5em;height:100%" class="mobile-menu-icon-filter fas fa-bars"></i>

            <?php else: ?>

            <i style="cursor:pointer; font-size: 30px; color: white; float:left;padding-right: .5em;height:100%" class="<?php echo e($currentPage != 'sales_start' ? 'mobile-menu-icon' : 'mobile-menu-icon-sales'); ?> fas fa-bars"></i>
           <?php endif; ?>

            <?php echo $__env->yieldContent('top-nav-item'); ?>


        </div>
        <div class = "col-md-2">

            <a style = "display:none" id="exit-now" class="btn exit-button" onclick="window.parent.postMessage('close', '*');" href="javascript:void(0)">Exit</a>

                    <a style = "display:none" id="exit-summary" class="btn exit-button" href="<?php echo e(url('/summary?user_token=' . $user_token)); ?>">Finish Later</a>
                    <a style = "display:none" id = "exit-contact" class="btn exit-button" href="<?php echo e(url('/summary?user_token=' . $user_token )); ?>">Finish Later</a>







        </div>
    </div>



</div>
<?php if(session()->has('message')): ?>
<div class = "row">
        <div class = "col-md-12">
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                <strong><?php echo e(session('message')); ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\OpenServer\domains\ttt\resources\views/includes/header.blade.php ENDPATH**/ ?>