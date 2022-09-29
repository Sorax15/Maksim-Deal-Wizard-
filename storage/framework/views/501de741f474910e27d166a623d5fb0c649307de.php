<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Express Buying - Buy a car faster with <?php echo e($dealer->dealer_name); ?>">
    <meta name="keywords" content="car, dealership, payments, value trade">

    <?php if(!empty($dealer->brandImage)): ?>
    <meta property="og:image" content="<?php echo e($dealer->brandImage); ?>" />
    <meta property="og:image:secure_url" content="<?php echo e($dealer->brandImage); ?>" />
    <meta property="og:image:alt" content="<?php echo e($dealer->dealer_name); ?>" />
    <meta property="og:title" content="Express Buying - Buy a car faster with <?php echo e($dealer->dealer_name); ?>" />
    <meta property="og:description" content="Making car buying easier online.  Visit <?php echo e($dealer->dealer_name); ?> today." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo e(url()->full()); ?>" />



    <?php endif; ?>



    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php echo $__env->yieldContent('page-title'); ?>

<!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/main.css?v=<?php echo e(time()); ?>"/>
     <link rel="stylesheet" href="<?php echo e(asset('/css/mobile-menu.css')); ?>"/>
          <link rel="stylesheet" href="<?php echo e(asset('/css/sweetalert.min.css')); ?>"/>

        <!-- Used to remove css on search page -->
        <?php if($currentPage != 'vehicle'): ?>

        <link rel="stylesheet" href="/css/salesperson-modal.css?v=<?php echo e(time()); ?>"/>
        <link rel="stylesheet" href="<?php echo e(asset('css/contact-info.css')); ?>">
        <?php endif; ?>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>





<?php echo $__env->yieldContent('page-css'); ?>

    <?php echo $__env->yieldContent('page-meta'); ?>


    <?php if(config()->get("app.env") == "prod" || config()->get("app.env") == "production"): ?>

        <!-- Meta Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '1670951096394969');
            fbq('track', 'PageView');
            </script>
            <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=1670951096394969&ev=PageView&noscript=1"
            />
            </noscript>
        <!-- End Meta Pixel Code -->

    <?php endif; ?>

<body>
<?php echo $__env->make('includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="side-navigation-container sidenav" style = "z-index:999">
    <div  class="closebtn" >&times;</div>
    <?php if(!isset($hideNav)): ?>
        <?php echo $__env->make('includes.side-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <?php if($hideNav == 'vehicle_filter'): ?>

        <?php endif; ?>
    <?php endif; ?>
</div>


<div class="wizard-content row" style = "margin-right:0px;margin-left:0px;padding-bottom: 100px;">

    <?php if(!isset($hideNav)): ?>
    <div class="side-navigation-container">
         <?php echo $__env->make('includes.side-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>


</div>
<?php echo $__env->make('wizard.pages.partials.progress-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make('wizard.pages.partials.salesperson-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make('wizard.pages.partials.contact-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<input type="hidden" id="contactCheck" value="<?php echo e(($contactCheck == false) ? 'false' : 'true'); ?>" />


<?php echo $__env->yieldContent('footer'); ?>
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" src="/js/sweetalert2.min.js"></script>

<script type="text/javascript" src="/js/main.js?v=<?php echo e(time()); ?>"></script>





<script>
let contactCheck = '<?php echo e(($contactCheck == false) ? 'false' : 'true'); ?>';

if ( window.location !== window.parent.location )
    {

        // The page is in an iFrames
        isIframe = true;
    }
    else {

        // The page is not in an iFrame
        isIframe = false;
    }

    $(document).ready(function() {
        var _currentPage = "<?php echo e($currentPage); ?>";
        var _contactCheck = "<?php echo e($contactCheck); ?>";
        console.log(_currentPage);
        console.log(_contactCheck);

        if(isIframe){
                $('#exit-now').show();

        }else{

                if(_currentPage != 'summary' && _contactCheck){
                        $('#exit-summary').show();
                }else if(_currentPage != 'summary'){
                        $('#exit-contact').show();
                }
        }



});

function submitContactInfo()
{
    var callOpt = 0;
    var textOpt = 0;
    var emailOpt = 0;
    var textMainOpt = 0;
    $('#contact-submit').hide();

    var email = $('input[name="email"]').val();
    if(email.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/) == null){
        $('input[name="email"]').css('border','2px solid red');
        $('#contact-submit').show();
        return;
    }else{
        $('input[name="email"]').css('border','1px solid #ced4da');
    }


    var data =  {
        fname: $('input[name="fname"]').val(),
        lname: $('input[name="lname"]').val(),
        phone: $('input[name="phone"]').val(),
        email: $('input[name="email"]').val(),
        text_opt_in: 1,
        user_token: '<?php echo e($user_token); ?>'
    }


    var error = false;
    for (const key in data) {
        if($.inArray( key, [ "fname", "lname", "phone" ] ) > -1){
            var value = data[key];
            if(value.match(/^\s*$/)){
                error = true;
                if(key == "phone"){
                    $('input[name="phone"]').css('border','2px solid red');
                }
                if(key == "fname"){
                    $('input[name="fname"]').css('border','2px solid red');
                }
                if(key == "lname"){
                    $('input[name="lname"]').css('border','2px solid red');
                }
            }else{
                if(key == "phone"){
                    $('input[name="phone"]').css('border','1px solid #ced4da');
                }
                if(key == "fname"){
                    $('input[name="fname"]').css('border','1px solid #ced4da');
                }
                if(key == "lname"){
                    $('input[name="lname"]').css('border','1px solid #ced4da');
                }
            }
        }

    }

    if(error){
        //showError("You are missing a required field.  Please check the form and submit again.");
        $('#contact-submit').show();
        return;
    }

    if($('input[name="text_opt_in"]').prop('checked'))
    {
        $(".text-opt-in").css('border','1px solid #ced4da');
    }else{
        $(".text-opt-in").css('border','2px solid red');
        $('#contact-submit').show();
        return;
    }

    $.ajax({
        data: data,
        type: "POST",
        url: "<?php echo e(url('/contact-information')); ?>",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).fail(function(jqXHR, status){
        $('#contact-submit').show();
        showError('Unable to process request. Try again or skip.');
    }).done(function(data) {
        $('#contact-submit').show();

        hideContactModal();
        contactCheck = 'true';
        $('#contactCheck').val('true');
        //setTimeout(function(){


         //}, 1000);
    });
}

function submitSalespersonQuestion()
{
    $('#question-submit').hide();


    if($('#modal-salesperson-optin').prop('checked'))
    {
        $('#modal-salesperson-optin-block').css('border','0px solid red');
    }else{
        $('#modal-salesperson-optin-block').css('border','2px solid red');
        showError('Please click the checkbox to continue or cancel.');
        $('#question-submit').show();
        return;
    }

    var data =  {
        fname: $('#modal-salesperson-fname').val(),
        lname: $('#modal-salesperson-lname').val(),
        phone: $('#modal-salesperson-phone').val(),
        question: $('#modal-salesperson-question').val(),
        user_token: '<?php echo e($deal->user_token); ?>'

    }


    var error = false;
    for (const key in data) {
        if($.inArray( key, [ "fname", "lname", "phone", "question" ] ) > -1){
            var value = data[key];
            if(value.match(/^\s*$/)){
                error = true;
                if(key == "phone"){
                    $('#modal-salesperson-phone').css('border','2px solid red');
                }
                if(key == "lname"){
                    $('#modal-salesperson-lname').css('border','2px solid red');
                }
                if(key == "fname"){
                    $('#modal-salesperson-fname').css('border','2px solid red');
                }
                if(key == "question"){
                    $('#modal-salesperson-question').css('border','2px solid red');
                }
            }else{
                if(key == "phone"){
                    $('#modal-salesperson-phone').css('border','1px solid #ced4da');
                }
                if(key == "lname"){
                    $('#modal-salesperson-lname').css('border','1px solid #ced4da');
                }
                if(key == "fname"){
                    $('#modal-salesperson-fname').css('border','1px solid #ced4da');
                }
                if(key == "question"){
                    $('#modal-salesperson-question').css('border','1px solid #ced4da');
                }
            }

            if(key == "phone" && isNaN(value)){
                error = true;
                $('#modal-salesperson-phone').css('border','2px solid red');
            }

        }

    }

    if(error){
        $('#question-submit').show();
       // showError("You are missing a required field.  Please check the form and submit again.");
        return;
    }

    $.ajax({
        data: data,
        type: "POST",
        url: "<?php echo e(url('/salesperson-question')); ?>",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).fail(function(jqXHR, status){
        $('#question-submit').show();
        showError('Unable to process request. Try again or cancel.');
    }).done(function(data) {
        //setTimeout(function(){
        hideSalespersonModal();
        $('#question-submit').show();
        showMessage("Your message was successfully sent.");
        // }, 2500);
    });
}


</script>

<?php echo $__env->yieldContent('page-js'); ?>
</body>
</html>
<?php /**PATH D:\OpenServer\domains\ttt\resources\views/layout.blade.php ENDPATH**/ ?>