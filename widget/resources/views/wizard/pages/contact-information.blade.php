@extends('layout')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/contact-info.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>

    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('top-nav-item')
    <h3><i class="fa fa-user"></i>YOUR CONTACT INFORMATION</h3>
@endsection

@section('footer-btns')
    <div class="back">
    <a class="btn btn-primary back-btn" href="{{ url('/vehicle-select?user_token=' . $user_token) }}">Back</a>
    </div>
    <div class="bot-buttons">
        <a class="btn btn-primary skip-btn contact_skip" href="{{ url('/value-trade?user_token=' . $user_token) }}">SKIP</a>
        <a class="btn btn-primary next-step-btn" onclick="submitContactInfo()">CONTINUE</a>
    </div>
@endsection


@section('footer')
    @include('includes.footer')
@endsection

@section('content')
    @php
        if($previousPage == 'trade')
        {
            $previousUrl = 'value-trade';
        } elseif($previousPage == 'preapproved1')
        {
            $previousUrl = 'pre-approved';
        } elseif($previousPage == 'appointment')
        {
            $previousUrl = 'schedule-appointment';
        } else {
            $previousUrl = 'welcome';
        }
    @endphp
    <div class="nav-main-container col-md-10">
        <div class="contact-info">
            <div class="contact-info-header-section">
            @if(isset($salesperson) && !empty($salesperson))
                    <div style = "display: flex">
                        <img class = "personal_image" src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
                        <h3 style = "margin-top: 20px;">Contact Details</h3>
                    </div>


                    @else
                    <h3>Contact Details</h3>
                    @endif

                    <h6 style="margin-bottom: 35px;">
                    Thanks for entering your contact information below in order for me to assist you.
                 </h6>

            </div>
            <div class="contact-info-form-section">
                <h3>Contact Information</h3>
                <hr />
                <div class="contact-form">
                    <input type="hidden" name="previousPage" value="{{ $previousPage }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>First Name</label>
                                <input style="max-width: 500px;" type="text" name="fname" required class="form-control" value="{{ old('fname', $deal->fname) }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input style="max-width: 500px;" type="text" name="lname" required class="form-control" value="{{ old('lname', $deal->lname) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Email</label>
                            <input style="max-width: 500px;" type="email" name="email" required class="form-control" value="{{ old('email', $deal->email) }}">
                        </div>
                        <div class="col-md-12" style="padding-top: 30px;">
                            <label>Phone Number</label>
                            <input style="max-width: 500px;" id="phone" type="tel" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"  name="phone" value="{{ old('phone', $deal->phone) }}" class="form-control" />

                        </div>
                    </div>

                    <div class="text-opt-in">
                        <div class="checkbox">

                        <label for="text_opt_in">
                            <input type="checkbox" name="text_opt_in" id="text_opt_in" checked>
                            <span class="contact-box"></span>
                            <span class="cb-text" style="margin-left: 35px!important;">I agree to receiving Text Messages from {{ $dealer->dealer_name }}.</span>
                        </label>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <script>
             Tracking = {
            user_token: '{{$user_token}}',
            page: '{{$currentPage}}'
        };
        var phoneInput = null;
         $(document).ready(function() {




            const phoneInputField = document.querySelector("#phone");
            phoneInput = window.intlTelInput(phoneInputField, {
                onlyCountries: ["us"],
                autoPlaceholder: "off",
                utilsScript:
                "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            });

            setTimeout(function(){
                if(phoneInput.getNumber() == "" || phoneInput.getNumber() == undefined){
                    $('#phone').css('border', '2px solid red');
                }
            }, 500);




        }            );

        function submitContactInfo()
        {
            pageProcessingShow();
            var callOpt = 0;
            var textOpt = 0;
            var emailOpt = 0;
            var textMainOpt = 0;

            var email = $('input[name="email"]').val();
            if(email.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/) == null){
                pageProcessingHide();
                showError("Incorrect email format. Please try again.");
                return;
            }


            if($('input[name="text_opt_in"]').prop('checked'))
            {
                textMainOpt = 1;
            }else{
                pageProcessingHide();
               showError('Please click the checkbox to continue or skip.');
               return;
            }

            var data =  {
                fname: $('input[name="fname"]').val(),
                lname: $('input[name="lname"]').val(),
                phone: phoneInput.getNumber(),
                email: $('input[name="email"]').val(),
                text_opt_in: textMainOpt,
                user_token: '{{ $user_token }}',
                previousPage: $('input[name="previousPage"]').val()
            }


            var error = false;
            for (const key in data) {
                if($.inArray( key, [ "fname", "lname", "phone" ] ) > -1){
                    var value = data[key];
                    if(value.match(/^\s*$/)){
                        error = true;
                        if(key == "phone"){
                            $('#phone').css('border','2px solid red');
                        }
                    }else{
                        if(key == "phone"){
                            $('#phone').css('border','1px solid #ced4da');
                        }
                    }
                }

            }

            if(error){
                pageProcessingHide();
                showError("You are missing a required field.  Please check the form and submit again.");
                return;
            }

            $.ajax({
                data: data,
                type: "POST",
                url: "{{ url('/contact-information') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).fail(function(jqXHR, status){
                pageProcessingHide();
                showError('Unable to process request. Try again or skip.');
            }).done(function(data) {
                //setTimeout(function(){

                   window.location.href = '{{ url($previousPage) }}?user_token={{ $user_token }}';

               // }, 2500);
            });
        }
    </script>
@endsection
