<template id="contact_salesperson">

    <?php
        if(  preg_match( '/^\+\d(\d{3})(\d{3})(\d{4})$/', $salesperson->IndividualPhoneNumber,  $matches ) )
        {
            $nicePhone = '('. $matches[1] . ')' . ' ' .$matches[2] . '-' . $matches[3];
        }
        else
        {
            $nicePhone = $salesperson->IndividualPhoneNumber;
        }
    ?>

    <swal-html>
        <div style="text-align: left;margin: 5px;">
        <div class="modal-salesperson-header">
            <img class="modal-salesperson-image" src="<?php echo e($api_endpoints['image']); ?>/uploads/media/profile_images/cropped/<?php echo e($salesperson->photo); ?>" width="75px" />
            <div class="modal-salesperson-grid">
                <span class="modal-salesperson-header-text modal-salesperson-left"><?php echo e($salesperson->first); ?> <?php echo e($salesperson->last); ?></span>
                <span class="modal-salesperson-header-text2 modal-salesperson-left">I'm here to help!</span>
            </div>
        </div>
        <div class="modal-salesperson-text">
            I can help you with anything you need.  Just type your question here to send.
        </div>

        <div class="modal-salesperson-form">

            <div style = "margin-bottom: 20px; display: flex; justify-content: space-between">
                <div>
                    <label style="font-size: 15px">First Name</label>
                    <input style="max-width: 200px;" type="text" id="modal-salesperson-fname" name="modal-salesperson-fname"  class="form-control" value="<?php echo e(old('fname', $deal->fname)); ?>">

                </div>
                <div>
                    <label style="font-size: 15px">Last Name</label>
                    <input style="max-width:200px;" type="text" id="modal-salesperson-lname" name="modal-salesperson-lname"  class="form-control" value="<?php echo e(old('lname', $deal->lname)); ?>">

                </div>
            </div>

            <label style="font-size: 15px">Phone Number</label>
            <input style="max-width: 200px;" type="text" id="modal-salesperson-phone" name="modal-salesperson-phone"  class="form-control" value="<?php echo e(old('lname', $deal->phone)); ?>">

            <div id="modal-salesperson-optin-block" style = "margin-top:20px" class="text-opt-in">
                <div class="checkbox">

                    <label for="text_opt_in">
                        <input type="checkbox" name="modal-salesperson-optin" id="modal-salesperson-optin" checked>
                        <span class="contact-box"></span>
                        <span class="cb-text" style="font-size:15px;margin-left: 35px!important;">I agree to receiving Text Messages from <?php echo e($dealer->dealer_name); ?>.  You will receive a text message from phone number <br> <?php echo e($nicePhone); ?> </span>
                    </label>


                </div>
            </div>

            <label style="font-size: 15px">Your Question</label>
            <textarea style="width:100%" class="form-control" required id="modal-salesperson-question" name="modal-salesperson-question" rows="4" ></textarea>

            <div style="text-align: center;margin-top:5px" class="">
                <a id="question-submit" class="btn btn-primary next-step-btn" onclick="submitSalespersonQuestion()">SEND QUESTION</a>
            </div>
            <br>
            <div style="text-align: center" class="">
                <span onclick="hideSalespersonModal()" class = "modal-salesperson-cancel">Cancel</span>
            </div>
        </div>
        </div>
    </swal-html>



</template><?php /**PATH D:\OpenServer\domains\ttt\resources\views/wizard/pages/partials/salesperson-modal.blade.php ENDPATH**/ ?>