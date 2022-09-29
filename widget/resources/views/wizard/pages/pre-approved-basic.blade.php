@extends('layout')

@section('page-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>

    <link rel="stylesheet" href="{{ asset('css/preapproved.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('top-nav-item')
    <h3><i class="fa fa-user"></i>GET PRE-APPROVED</h3>
@endsection

@section('footer-btns')
    <div class="back">
    <a class="btn btn-primary back-btn" href="{{ url('/schedule-appointment?user_token=' . $user_token) }}">Back</a>
    </div>
    <div class="bot-buttons">
        <a class="btn btn-primary skip-btn" href="{{ url('/summary?user_token=' . $user_token) }}">SKIP</a>
        <a class="btn btn-primary next-step-btn">CONTINUE</a>
    </div>
@endsection

@section('footer')
    @include('includes.footer')
@endsection

@section('content')
    <div class="nav-main-container col-md-10">
        <div class="preapproved-info">
            <div class="preapproved-info-header">
            @if(isset($salesperson) && !empty($salesperson))
                    <div style = "display: flex">
                        <img class = "personal_image" src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
                        <h3 style = "margin-top: 20px;">Get Pre-Approved</h3>
                    </div>
                    <div onclick="showSalespersonModal()" style="padding-top:10px;width:fit-content;cursor:pointer;font-weight:800;font-size: 15px; color:#039BE5 ">
                        <i style="color: #039BE5;" class="fas fa-asterisk fa-question-circle"></i>
                        Ask a Question
                    </div>


                @else
                    <h3>Get Pre-Approved</h3>
                    @endif

                    <h6 style="margin-bottom: 35px;">
                    Let’s get the credit application started! Our system protects your information so you have no worries!
                 </h6>
                <div class="row">
                    <div class="col-md-6">
                        @php
                            if($paCheckOne)
                            {
                                $basicStatus = ' pa-complete';
                                $basicImg = '<img src="' . asset('imgs/pa-green.png') . '">';
                            }
                            else
                            {
                                $basicStatus = ' pa-current';
                                $basicImg = '<img src="' . asset('imgs/pa-blue1.png') . '">';
                            }
                            if($paCheckTwo)
                            {
                                $employeeStatus = ' pa-complete';
                                $employeeImg = '<img src="' . asset('imgs/pa-green.png') . '">';
                            }
                            else
                            {
                                $employeeStatus = ' pa-next';
                                $employeeImg = '<img src="' . asset('imgs/pa-gray2.png') . '">';
                            }
                        @endphp
                        <div class="row">
                            <div class="col-md-6{{ $basicStatus }}">
                                <div class="pa-step">
                                    {!! $basicImg !!}
                                    <span class="pa-step-text">Contact and Address Details</span>
                                </div>
                            </div>
                            <div class="col-md-6{{ $employeeStatus }}">
                                <div class="pa-step">
                                    {!! $employeeImg !!}
                                    <span class="pa-step-text">Employment Information</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="preapproved-info-form-section">
                <div class="errors"><span></span></div>
                <div class="row">
                    <div class="col-md-6">
                        <h3>Contact Information</h3>
                        <hr />
                        <div class="basic-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname">First Name <span class="required">*</span></label>
                                        <input type="text" name="fname" id="fname" class="form-control" value="{{ old('fname', $deal->fname) }}" required>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="mname">Middle Name(Optional)</label>
                                    <input type="text" name="mname" id="mname" class="form-control" value="{{ old('mname', $deal->mname) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lname">Last Name <span class="required">*</span></label>
                                        <input type="text" name="lname" id="lname" class="form-control" value="{{ old('lname', $deal->lname) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="birthday">Birthdate <span class="required">*</span></label>
                                     <div class="input-group date" data-provide="datepicker">
                                        <input type="text" id="birthday"  class="form-control" placeholder="MM/DD/YYYY" value="{{ old('birthday', $deal->birthday) }}" required>
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone Number <span class="required">*</span></label>
                                        <input id="phone" type="tel" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"  name="phone" value="{{ old('phone', $deal->phone) }}" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email">Email <span class="required">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $deal->email) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3>Address</h3>
                        <hr />
                        <div class="address-form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address">Address <span class="required">*</span></label>
                                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $deal->address) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="apt_unit">Apt/Unit</label>
                                        <input type="text" name="apt_unit" id="apt_unit" class="form-control" value="{{ old('apt_unit', $deal->apt_unit) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city">City <span class="required">*</span></label>
                                        <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $deal->city) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="state">State <span class="required">*</span></label>
                                        <input type="text" name="state" id="state" class="form-control" value="{{ old('state', $deal->state) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="zipcode">Zipcode <span class="required">*</span></label>
                                        <input type="text" name="zipcode" id="zipcode" class="form-control" value="{{ old('zipcode', $deal->zipcode) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script>

        $(document).ready(function() {

            const phoneInputField = document.querySelector("#phone");
            const phoneInput = window.intlTelInput(phoneInputField, {
                onlyCountries: ["us"],
                autoPlaceholder: "off",
                utilsScript:
                "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            });

            $('.datepicker').datepicker({
                format: 'mm/dd/yyyy',
                startDate: '-18y',
                minViewMode: "year"
            });


            $('.next-step-btn').on('click', function() {
                processingShow();
                var error = false;
                var email = $('input[name="email"]').val();
                if(email.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/) == null){
                    processingHide();
                    showError("Incorrect email format. Please try again.");
                    return;
                }
                //
                var data =  {
                    fname: $('input[name="fname"]').val(),
                    lname: $('input[name="lname"]').val(),
                    mname: $('input[name="mname"]').val(),
                    phone: phoneInput.getNumber(),
                    birthday: $('#birthday').val(),
                    email: $('input[name="email"]').val(),
                    address: $('input[name="address"]').val(),
                    apt_unit: $('input[name="apt_unit"]').val(),
                    city: $('input[name="city"]').val(),
                    state: $('input[name="state"]').val(),
                    zipcode: $('input[name="zipcode"]').val(),
                    user_token: '{{ $user_token }}'
                }

                for (const key in data) {
                    if($.inArray( key, [ "fname", "lname", "phone", "address", "city", "state", "birthday" ] ) > -1){
                        var value = data[key];
                        if(value.match(/^\s*$/)){
                            console.log('error-> '+key);
                            error = true;
                        }
                    }

                }

                if(error){
                   processingHide();
                    showError("You are missing a required field.  Please check the form and submit again.");
                    return;
                }

                $.ajax({
                    data: data,
                    type: "POST",
                    url: '{{ url('pre-approved') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    statusCode: {
                        400: function() {
                            processingHide();
                            $('.errors').show();
                            $('.errors span').html('You are missing a required field.  Please check the form and submit again.');
                            showError('Check the requireds fields.');
                        },
                        200: function() {
                            window.location.href = '{{ url('pre-approved/employee') }}?user_token={{ $user_token }}';
                        }
                    }
                }).fail(function(jqXHR, status){
                processingHide();
                showError('There was an error processing request. Try again or skip');
            });
            });
        });
    </script>
@endsection
