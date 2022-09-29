<?php $__env->startSection('page-css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/navigation.css')); ?>">
    <link href="//amp.azure.net/libs/amp/2.3.9/skins/amp-default/azuremediaplayer.min.css" rel="stylesheet">
    <style>

    </style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('top-nav-item'); ?>
    <h3>EXPRESS BUYING EXPERIENCE AT <?php echo e(strtoupper($dealer->dealer_name)); ?></h3>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>

    <div class="nav-main-container col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="welcome-section">
                    <?php if(!is_null($deal->fname)): ?>
                        <h1 style="text-transform: uppercase">WELCOME <?php echo e($deal->fname); ?>!</h1>
                    <?php else: ?>
                        <h1 style="text-transform: uppercase">WELCOME!</h1>
                    <?php endif; ?>

                    <?php if(isset($salesperson) && !empty($salesperson)): ?>
                    <div style = "display: flex">
                        <img class = "personal_image" src="<?php echo e($api_endpoints['image']); ?>/uploads/media/profile_images/cropped/<?php echo e($salesperson->photo); ?>">
                        <h3 style = "margin-top: 20px;">
                            <?php if(isset($welcomeInfo->header)): ?>
                                <?php echo e($welcomeInfo->header); ?>

                            <?php else: ?>
                                Glad to See You Here.
                            <?php endif; ?>

                            <div onclick="showSalespersonModal()" style="width:fit-content;cursor:pointer;font-weight:800;font-size: 15px; color:#039BE5 ">
                                <i style="color: #039BE5;" class="fas fa-asterisk fa-question-circle"></i>
                                Ask a Question
                            </div>

                            

                        </h3>

                    </div>

                    <?php else: ?>
                    <h3>
                        <?php if(isset($welcomeInfo->header)): ?>
                            <?php echo e($welcomeInfo->header); ?>

                        <?php else: ?>
                            Glad to See You Here.
                        <?php endif; ?>
                    </h3>
                    <?php endif; ?>

                    <h6 style="margin-bottom: 35px;">

                        <?php if(isset($welcomeInfo->message)): ?>
                            <?php echo e($welcomeInfo->message); ?>

                        <?php else: ?>
                            <?php if(isset($salesperson) && !empty($salesperson)): ?>
                                This is <?php echo e($salesperson->first); ?> <?php echo e($salesperson->last); ?>! I’m here to help you in your journey to find the best vehicle and to make this process easy. <br><br>
                            <?php endif; ?>
                            In this Express Buying site you can customize your payments, get a immediate value for your trade, start the credit application, schedule a test drive and more.

                        <?php endif; ?>


                    </h6>

                    <div class="video-container" style="text-align: center;">

                        <?php if(isset($welcomeInfo->media->resourceUrl)): ?>

                            <?php if($welcomeInfo->media->videoId == null || empty($welcomeInfo->media->videoId)): ?>

                            <img style = "max-width:100%" src="<?php echo e($welcomeInfo->media->resourceUrl); ?>"/>

                            <?php else: ?>
                                <video id="vid1" class="azuremediaplayer amp-default-skin" autoplay controls width="100%" height="400" fluid="true" poster="<?php echo e($welcomeInfo->media->thumbnail); ?>" data-setup='{"logo": { "enabled": false },"techOrder": ["azureHtml5JS", "flashSS", "html5FairPlayHLS","silverlightSS", "html5"], "nativeControlsForTouch": false}'>
                                    <source src="<?php echo e($welcomeInfo->media->resourceUrl); ?>" type="application/vnd.ms-sstr+xml" />
                                    <p class="amp-no-js">
                                        To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video
                                    </p>
                                </video>
                            <?php endif; ?>

                        <?php else: ?>
                            <img style = "max-width:100%" src="<?php echo e($dealer->brandImage); ?>"/>
                        <?php endif; ?>



                    </div>
                </div>
            </div>
            <div class="col-md-6" >
                <div class="welcome-links">
                    <h4>Save Time At The Dealership...</h4>
                    <div class="btn-group-vertical" role="group" aria-label="Basic outlined example">
                    <?php if(isset($salesperson) && !empty($salesperson)): ?>
                       <!-- <a href="<?php echo e(url('salesperson-detail?user_token=' . $user_token . '&s_id=' . $salesperson->s_id . '&d_id=' . $d_id . '&deal_id=' . $deal->id)); ?>">
                           <span style="background-color:#039BE5;width:100%;height: 40px;padding-top: 10px;" class="badge badge-pill badge-primary">
                            <?php if($next == "salesperson"): ?>
                                &nbsp;<i style="color: white;" class="fas fa-asterisk fa-spin fa-xs"></i>
                            <?php endif; ?>
                            Select Your Salesperson
                            </span>


                    </a>-->
                    <?php else: ?>
                        <a href="<?php echo e(url('start-sales-person?user_token=' . $user_token)); ?>">
                            <span style="background-color:#039BE5;width:100%;height: 40px;padding-top: 10px;" class="badge badge-pill badge-primary">
                            <?php if($next == "salesperson"): ?>
                                &nbsp;<i style="color: white;" class="fas fa-asterisk fa-spin fa-xs"></i>
                            <?php endif; ?>> Select Your Salesperson

                            </span>

                    </a>
                    <?php endif; ?>


                    <?php if($vehicleCheck): ?>
                       <!-- <a href="<?php echo e(url('vehicle-detail?user_token=' . $user_token . '&d_id=' . $d_id . '&deal_id=' . $deal->id . '&vehicle_id=' . $deal->vehicle_id)); ?>">
                            <span style="background-color:#039BE5;width:100%;height: 40px;padding-top: 10px;" class="badge badge-pill badge-primary">
                            <?php if($next == "vehicle"): ?>
                                &nbsp;<i style="color: white;" class="fas fa-asterisk fa-spin fa-xs"></i>
                            <?php endif; ?>
                            Select Your Vehicle</span></a>-->
                    <?php else: ?>
                        <a href="<?php echo e(url('vehicle-select?user_token=' . $user_token )); ?>">
                            <span style="background-color:#039BE5;width:100%;height: 40px;padding-top: 10px;" class="badge badge-pill badge-primary">
                            <?php if($next == "vehicle"): ?>
                                &nbsp;<i style="color: white;" class="fas fa-asterisk fa-spin fa-xs"></i>
                            <?php endif; ?>
                            Select Your Vehicle</span></a>
                    <?php endif; ?>



                    <a href="<?php echo e(url('schedule-appointment?user_token=' . $user_token)); ?>">
                        <span style="background-color:#039BE5;width:100%;height: 40px;padding-top: 10px;" class="badge badge-pill badge-primary">
                        <?php if($next == "appointment"): ?>
                                &nbsp;<i style="color: white;" class="fas fa-asterisk fa-spin fa-xs"></i>
                            <?php endif; ?>
                            Schedule An Appointment</span></a>
                    <a href="<?php echo e(url('pre-approved?user_token=' . $user_token )); ?>">
                        <span style="background-color:#039BE5;width:100%;height: 40px;padding-top: 10px;" class="badge badge-pill badge-primary">
                        <?php if($next == "preapproved"): ?>
                                &nbsp;<i style="color: white;" class="fas fa-asterisk fa-spin fa-xs"></i>
                            <?php endif; ?>
                            Get Pre-Approved</span></a>

                </div>
            </div>
            </div>
        </div>

    </div>



<?php $__env->stopSection(); ?>
    <style>
    .vjs-big-play-button{
        top:100px!important;
        position: relative!important;
        height: 75px!important;
        width: 75px!important;
    }
</style>

<?php $__env->startSection('page-js'); ?>
    <script src= "//amp.azure.net/libs/amp/2.3.9/azuremediaplayer.min.js"></script>
<script>
Tracking = {
    user_token: '<?php echo e($user_token); ?>',
    page: '<?php echo e($currentPage); ?>',
    s_id: '<?php echo e($s_id); ?>'
};

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OpenServer\domains\ttt\resources\views/wizard/pages/navigation.blade.php ENDPATH**/ ?>