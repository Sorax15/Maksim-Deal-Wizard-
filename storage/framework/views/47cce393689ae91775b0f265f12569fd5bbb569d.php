    <div class="side-nav-content">

        <div class="dealer-logo">
            <img src="<?php echo e($dealer->brandLogo); ?>">
        </div>
        <div class="welcome">
            <div class="side-nav-row <?php echo e($currentPage == 'welcome' ? 'active':''); ?>">
                <div class="sp-nav-img pa-icon">
                    <img class="complete" src="<?php echo e(asset('imgs/icons/welcome.png')); ?>" />
                </div>
                <div class="sp-nav-text welcome">
                    <?php if($deal->fname != ''): ?>
                        <a href="<?php echo e(url('welcome?user_token=' . $user_token)); ?>"><h6>WELCOME, <?php echo e($deal->fname); ?></h6></a>
                    <?php else: ?>
                        <a href="<?php echo e(url('welcome?user_token=' . $user_token )); ?>"><h6>WELCOME</h6></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if(isset($salesperson) && !empty($salesperson)): ?>
            <div class="salesperson-selected">
                <div class="side-nav-row <?php echo e($currentPage == 'sales' || $currentPage == 'sales_start' ? 'active':''); ?>">
                    <a href="<?php echo e(url('salesperson-detail?user_token=' . $user_token . '&s_id=' . $salesperson->s_id)); ?>">
                        <div class="sp-nav-img sp-icon">
                            <img src="<?php echo e($api_endpoints['image']); ?>/uploads/media/profile_images/cropped/<?php echo e($salesperson->photo); ?>" />
                        </div>
                    </a>
                    <a href="<?php echo e(url('salesperson-detail?user_token=' . $user_token . '&s_id=' . $salesperson->s_id)); ?>">
                        <div class="sp-nav-text">
                            <h6>SALESPERSON</h6>
                            <p><?php echo e($salesperson->first); ?> <?php echo e($salesperson->last); ?></p>
                            <span class="time-saved">10 Min</span>
                        </div>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="salesperson-selected">
                <div class="side-nav-row <?php echo e($currentPage == 'sales' || $currentPage == 'sales_start' ? 'active':''); ?>">
                    <div class="sp-nav-img sp-icon">
                        <img src="<?php echo e(asset('imgs/icons/salesperson.png')); ?>" />
                    </div>
                    <div class="sp-nav-text">
                        <h6>SALESPERSON</h6>
                        <a href="<?php echo e(url('start-sales-person?user_token=' . $user_token )); ?>">Start Now
                            <?php if($next == 'salesperson' && $currentPage != 'sales_start' && $currentPage != 'sales'): ?>
                                &nbsp;<i style="color: #039BE5;" class="fas fa-asterisk fa-spin"></i>
                            <?php endif; ?>
                        </a>
                        <span class="time-saved">10 Min </span>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if($vehicleCheck): ?>
            <div class="vehicle-selected">
                <div class="side-nav-row <?php echo e($currentPage == 'vehicle' ? 'active':''); ?>">
                    <a href="<?php echo e(url('vehicle-detail?user_token=' . $user_token . '&vehicle_id=' . $deal->vehicle_id)); ?>">
                        <div class="sp-nav-img">
                            <img class="complete" src="<?php echo e(asset('imgs/icons/vehicle.png')); ?>" />
                        </div>
                    </a>
                    <a href="<?php echo e(url('vehicle-detail?user_token=' . $user_token . '&vehicle_id=' . $deal->vehicle_id)); ?>">
                        <div class="sp-nav-text">
                            <h6>VEHICLE</h6>
                            <!-- Added $v_name because there is a flow where $vehicle->model->name is empty but populated in controller. -->
                            <?php if($vehicle->name): ?>
                                <?php
                                    $v_name = '';
                                ?>
                                <p style="max-width: 130px;white-space: nowrap;"><?php echo e($vehicle->name); ?></p>
                            <?php else: ?>
                                <p></p>
                            <?php endif; ?>
                            <span class="time-saved">10 Min</span>
                        </div>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="vehicle-selected">
                <div class="side-nav-row <?php echo e($currentPage == 'vehicle' ? 'active':''); ?>">
                    <div class="sp-nav-img">
                        <img src="<?php echo e(asset('imgs/icons/vehicle.png')); ?>" />
                    </div>
                    <div class="sp-nav-text">
                        <h6>VEHICLE</h6>
                        <a href="<?php echo e(url('vehicle-select?user_token=' . $user_token)); ?>">Start Now
                            <?php if($next == 'vehicle' && $currentPage != 'vehicle'): ?>
                                &nbsp;<i style="color: #039BE5;" class="fas fa-asterisk fa-spin"></i>
                            <?php endif; ?>
                        </a>
                        <span class="time-saved">30 Min</span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

         <?php if($contactCheck): ?>
            <!--<div class="salesperson-selected">
                <div class="side-nav-row <?php echo e($currentPage == 'contact' ? 'active':''); ?>">
                    <a href="<?php echo e(url('contact-information?user_token=' . $user_token)); ?>">
                        <div class="sp-nav-img sp-icon">
                            <img class="complete" src="<?php echo e(asset('imgs/icons/salesperson.png')); ?>" />
                        </div>
                    </a>
                    <a href="<?php echo e(url('contact-information?user_token=' . $user_token)); ?>">
                        <div class="sp-nav-text">
                            <h6>CONTACT</h6>
                            <p><?php echo e($deal->fname); ?> <?php echo e($deal->lname); ?></p>
                            <span class="time-saved">10 Min</span>
                        </div>
                    </a>
                </div>
            </div>-->
        <?php else: ?>
           <!-- <div class="salesperson-selected">
                <div class="side-nav-row <?php echo e($currentPage == 'sales' || $currentPage == 'sales_start' ? 'active':''); ?>">
                    <div class="sp-nav-img sp-icon">
                        <img src="<?php echo e(asset('imgs/icons/salesperson.png')); ?>" />
                    </div>
                    <div class="sp-nav-text">
                        <h6>CONTACT</h6>
                    <a href="<?php echo e(url('contact-information?user_token=' . $user_token)); ?>">Start Now
                            <?php if($next == 'contact' && $currentPage != 'contact'): ?>
                                &nbsp;<i style="color: #039BE5;" class="fas fa-asterisk fa-spin"></i>
                            <?php endif; ?>
                        </a>
                        <span class="time-saved">10 Min </span>

                    </div>
                </div>
            </div>-->
        <?php endif; ?>















































































        <?php if($appointmentCheck): ?>
            <div class="schedule-info">
                <div class="side-nav-row <?php echo e($currentPage == 'appointment' ? 'active':''); ?>">
                    <a href="<?php echo e(url('schedule-appointment?user_token=' . $user_token)); ?>">
                        <div class="sp-nav-img">
                            <img class="complete" src="<?php echo e(asset('imgs/icons/appointment.png')); ?>" />
                        </div>
                    </a>
                    <a href="<?php echo e(url('schedule-appointment?user_token=' . $user_token )); ?>">
                        <div class="sp-nav-text">
                            <h6>SCHEDULE APPOINTMENT</h6>
                            <p style="max-width: 130px;white-space: nowrap;"><?php echo e(\Carbon\Carbon::parse($deal->td_date . ' ' . $deal->td_time)->format('M d, Y h:i A')); ?></p>
                            <span class="time-saved">30 Min</span>
                        </div>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="schedule-info">
                <div class="side-nav-row <?php echo e($currentPage == 'appointment' ? 'active':''); ?>">
                    <div class="sp-nav-img">
                        <img src="<?php echo e(asset('imgs/icons/appointment.png')); ?>" />
                    </div>
                    <div class="sp-nav-text">
                        <h6>SCHEDULE APPOINTMENT</h6>
                        <a href="<?php echo e(url('schedule-appointment?user_token=' . $user_token)); ?>">Start Now
                            <?php if($next == 'appointment' && $currentPage != 'appointment'): ?>
                                &nbsp;<i style="color: #039BE5;" class="fas fa-asterisk fa-spin"></i>
                            <?php endif; ?>
                        </a>
                        <span class="time-saved">30 Min</span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if($preapprovedCheck): ?>
            <div class="schedule-info">
                <div class="side-nav-row <?php echo e($currentPage == 'preapproved' ? 'active':''); ?>">
                    <a href="<?php echo e(url('pre-approved?user_token=' . $user_token )); ?>">
                        <div class="sp-nav-img">
                            <img class="complete" src="<?php echo e(asset('imgs/icons/pre-approved.png')); ?>" />
                        </div>
                    </a>
                    <a href="<?php echo e(url('pre-approved?user_token=' . $user_token)); ?>">
                        <div class="sp-nav-text">
                            <h6>GET PRE-APPROVED</h6>
                            <span class="time-saved">40 Min</span>
                            <br>
                        </div>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="schedule-info">
                <div class="side-nav-row <?php echo e($currentPage == 'preapproved' ? 'active':''); ?> ">
                    <div class="sp-nav-img">
                        <img src="<?php echo e(asset('imgs/icons/pre-approved.png')); ?>" />
                    </div>
                    <div class="sp-nav-text">
                        <h6>GET PRE-APPROVED</h6>
                        <a href="<?php echo e(url('pre-approved?user_token=' . $user_token)); ?>">Start Now
                            <?php if($next == 'preapproved' && $currentPage != 'preapproved'): ?>
                                &nbsp;<i style="color: #039BE5;" class="fas fa-asterisk fa-spin"></i>
                            <?php endif; ?>
                        </a>
                        <span class="time-saved">40 Min</span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if($vehicleCheck && $preapprovedCheck && $paymentCheck && $tradeCheck && $appointmentCheck && isset($salesperson) && !empty($salesperson)): ?>
            <div class="schedule-info">
                <div class="side-nav-row <?php echo e($currentPage == 'summary' ? 'active':''); ?>">
                    <a href="<?php echo e(url('summary?user_token=' . $user_token )); ?>">
                        <div class="sp-nav-img pa-icon">
                            <img class="complete" src="<?php echo e(asset('imgs/icons/summary.png')); ?>" />
                        </div>
                    </a>
                    <a href="<?php echo e(url('summary?user_token=' . $user_token)); ?>">
                        <div class="sp-nav-text">
                            <h6>SUMMARY</h6>
                        </div>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="schedule-info">
                <div class="side-nav-row <?php echo e($currentPage == 'summary' ? 'active':''); ?>">
                    <a href="<?php echo e(url('summary?user_token=' . $user_token)); ?>">
                        <div class="sp-nav-img pa-icon">
                            <img src="<?php echo e(asset('imgs/icons/summary.png')); ?>" />
                        </div>
                        <div class="sp-nav-text summary">
                            <h6>SUMMARY
                                <?php if($next == 'summary' && $currentPage != 'summary'): ?>
                                &nbsp;<i style="color: #039BE5;" class="fas fa-asterisk fa-spin"></i>
                            <?php endif; ?>
                            </h6>
                        </div>
                    </a>
                </div>
            </div>
        <?php endif; ?>
        <br>
        <div class="progress-div">
            <div class="side-nav-row">
                <span class ="progressbar-text"><i class="fas fa-check-circle"></i>&nbsp;My Progress</span>
                <div class="progress" style = "background-color:#72c2effa; border-radius:.50rem">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo e($percentage); ?>%;" aria-valuenow="<?php echo e($percentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="progressbar-text2" style = "float:left"><?php echo e($percentage); ?>%</span>
                <span class="progressbar-text2" style = "float:right"><?php echo e($minSaved); ?> min saved</span>
            </div>
        </div>
    </div>
<?php /**PATH D:\OpenServer\domains\ttt\resources\views/includes/side-nav.blade.php ENDPATH**/ ?>