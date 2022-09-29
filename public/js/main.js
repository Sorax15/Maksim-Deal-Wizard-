let Tracking = {};
var isIframe = null;
var isMobile = null;
let ToastError = null;
let PageProgress = null;



if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
    isMobile = true;
}else{
  isMobile = false;
}



$(document).ready(function() {

    adjustWindow();

    ToastError = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })


    $('.mobile-menu-icon').click(function() {



        $('.sidenav').width('350px');
        $('.sidenav').show();

    });

    $('.closebtn').click(function(){
        $('.sidenav').width('0px');
        $('.sidenav').hide();
    });

    $('.mobile-menu-icon-sales').click(function() {



        $('.sidenav').width('350px');
        $('.sidenav').show();

    });

    $('.closebtnsales').click(function(){
        $('.sidenav').width('0px');
        $('.sidenav').hide();
    });

    // $('.closebtn-filter').click(function(){
    //     $('.sidenav').width('0px');
    //     $('.sidenav').hide();
    // });

    $( window ).resize(function() {
        adjustWindow();
    });


    function adjustWindow(){
        var url = window.location.pathname;

        if(!url.match("vehicle-select")) {
            if ($(window).width() <= 1500) {
                $('.nav-main-container').addClass('col-md-12').removeClass('col-md-10');
            } else {
                $('.nav-main-container').addClass('col-md-10').removeClass('col-md-12');
            }

        }else{
            $('.nav-main-container').addClass('col-md-10').removeClass('col-md-12');
        }
    }

    //Tracking Navigation Left navigation
    $('.side-nav-content a').click(function(event){


        //var url = $(this).prop('href');
        Tracking['type'] = 'navigation';
        Tracking['env'] = 'app';
        Tracking['info'] = 'link click left menu';
        sendTracking();
       // window.location.href = url;
    });
     //Tracking Navigation Left navigation
    $('.welcome-links a').click(function(event){


        //var url = $(this).prop('href');
        Tracking['type'] = 'navigation';
        Tracking['env'] = 'app';
        Tracking['info'] = 'link click right menu';
        sendTracking();
       // window.location.href = url;
    });
     //Tracking Navigation exit finish later
    $('#exit-now, #exit-contact, #exit-summary').click(function(event){


        //var url = $(this).prop('href');
        Tracking['type'] = 'navigation';
        Tracking['env'] = 'app';
        Tracking['info'] = 'link click exit/finish later';
        sendTracking();
       // window.location.href = url;
    });
     //Tracking Navigation exit finish later
    $('.back-btn').click(function(event){


        //var url = $(this).prop('href');
        Tracking['type'] = 'navigation';
        Tracking['env'] = 'app';
        Tracking['info'] = 'link click back button';
        sendTracking();
       // window.location.href = url;
    });

    $('.vehicle-link').click(function(event){

       // var url = $(this).prop('href');
        Tracking['type'] = 'navigation';
        Tracking['env'] = 'app';
        Tracking['info'] = 'link click vehicle';
        sendTracking();
        //window.location.href = url;
    });

});

function sendTracking(){
    let data = {data: Tracking}
    fetch('api/tracking-client', {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  });
}


let ProcessingAlert = null;
let SalespersonModal = null;
let ContactModal = null;
let continue_action = false;

function showContactModal(){
    ContactModal = Swal.fire({
        template: '#contact_form_modal',
        backdrop: 'rgb(0 0 0 / 88%)',
        allowOutsideClick: false,
        showConfirmButton: false,
        allowEscapeKey: false,
        didDestroy: (toast) => {
            if (typeof continueAction === 'function') {
                continueAction();
            }
        }

    });

}

function showSalespersonModal(){
   SalespersonModal = Swal.fire({
        template: '#contact_salesperson',
        backdrop: 'rgb(0 0 0 / 88%)',
        allowOutsideClick: false,
        showConfirmButton: false,
        allowEscapeKey: false

   });

}

function hideContactModal(){
    ContactModal.close();
}

function hideSalespersonModal(){
    SalespersonModal.close();
}

function processingShow(msg){
    msg = msg || 'Please wait...';
    ProcessingAlert = Swal.fire({
        title: 'Processing Request',
        text: msg,
        icon: 'info',
        width: 600,
        padding: '3em',
        backdrop: 'rgb(0 0 0 / 88%)',
        allowOutsideClick: false,
        showConfirmButton: false,
        allowEscapeKey: false

    })
}

function processingHide(){
    ProcessingAlert.close();
}

function pageProcessingShow(){
    PageProgress = Swal.fire({
        template: '#progress-template',
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false
        })
}

function pageProcessingHide(){
    PageProgress.close();
}




function showError(msg,seconds){
    seconds = seconds || 5000;
    ToastError.fire({
        icon: 'error',
        title: msg,
        timer: seconds
    })
}

function showMessage(msg, seconds){
    seconds = seconds || 5000;
    ToastError.fire({
        icon: 'info',
        title: msg,
        timer: seconds
    })
}

function hideError(){
    $('#error_msg').html('');
    $('#error_block').hide();
}


