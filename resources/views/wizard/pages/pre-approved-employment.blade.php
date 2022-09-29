@extends('layout')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/preapproved.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>

    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('top-nav-item')
    <h3><i class="fa fa-user"></i>GET PRE-APPROVED</h3>
@endsection

@section('footer-btns')
    <div class="back">
        <a class="btn btn-primary back-btn" href="{{ url('/pre-approved?user_token=' . $user_token) }}">Back</a>
    </div>
    <div class="bot-buttons">
        <a class="btn btn-primary skip-btn" href="{{ url('/summary?user_token=' . $user_token) }}">SKIP</a>
        <a class="btn btn-primary next-step-btn" onclick="submitPreApprovedInfo()">CONTINUE</a>
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
                                $employeeStatus = ' pa-current';
                                $employeeImg = '<img src="' . asset('imgs/pa-blue2.png') . '">';
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
                        <h3>Employment</h3>
                        <hr />
                        <div class="basic-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Employment Status <span class="required">*</span></label>
                                        <select id="employment_status" name="employment_status" class="form-control" required>
                                            <option></option>
                                            <option {{ $deal->employment_status == "Self_Employed" ? 'selected':'' }}  value="Self_Employed">Self Employed</option>
                                            <option {{ $deal->employment_status == "Full_Time" ? 'selected':'' }} value="Full_Time">Full-Time</option>
                                            <option {{ $deal->employment_status == "Part_Time" ? 'selected':'' }} value="Part_Time">Part-Time</option>
                                            <option {{ $deal->employment_status == "Unemployed" ? 'selected':'' }} value="Unemployed">Unemployed</option>
                                            <option {{ $deal->employment_status == "Active_Military" ? 'selected':'' }} value="Active_Military">Active Military</option>
                                            <option {{ $deal->employment_status == "Contract" ? 'selected':'' }} value="Contract">Contract</option>
                                            <option {{ $deal->employment_status == "Student" ? 'selected':'' }} value="Student">Student</option>
                                            <option {{ $deal->employment_status == "Retired" ? 'selected':'' }} value="Retired">Retired</option>
                                            <option {{ $deal->employment_status == "Not_Applicable" ? 'selected':'' }} value="Not_Applicable">Not Applicable</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Job Title <span class="required">*</span></label>
                                    <input type="text" name="job_title" class="form-control" value="{{ old('job_title', $deal->job_title) }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company Name <span class="required">*</span></label>
                                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $deal->company_name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company Phone <span class="required">*</span></label>
                                         <input id="company_phone" type="tel" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"  name="company_phone" value="{{ old('company_phone', $deal->company_phone) }}" class="form-control" />

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Company Address <span class="required">*</span></label>
                                        <input type="text" name="company_address" class="form-control" value="{{ old('company_address', $deal->company_address) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Years worked at Company <span class="required">*</span></label>
                                        <input type="number" name="years_company" class="form-control" value="{{ old('years_company', $deal->years_company) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Months worked at Company <span class="required">*</span></label>
                                        <input type="number" name="months_company" class="form-control" value="{{ old('months_company', $deal->months_company) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Monthly Income Before Taxes <span class="required">*</span></label>
                                        <input type="number" name="income" class="form-control" value="{{ old('income', $deal->income) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                      <!--  <div class="license-form">
                            <h3>License Information</h3>
                            <hr />
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Drivers License No. <span class="required">*</span></label>
                                        <input type="text" name="dl_no" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Drivers License State <span class="required">*</span></label>
                                        <input type="text" name="dl_state" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>SSN (Social Sec #) <span class="required">*</span></label>
                                        <input type="number" name="ssn" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        <div class="residence-form">
                            <h3>Residence</h3>
                            <hr />
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Rent/Own <span class="required">*</span></label>
                                        <select name="rent_own" class="form-control">
                                            <option></option>
                                            <option {{ $deal->rent_own == "Rent" ? 'selected':'' }} value = "Rent">Rent</option>
                                            <option {{ $deal->rent_own == "Own" ? 'selected':'' }} value="Own">Own</option>
                                            <option {{ $deal->rent_own == "Military" ? 'selected':'' }} value="Military">Military</option>
                                            <option {{ $deal->rent_own == "Living_with_family" ? 'selected':'' }} value = "Living_with_family">Living with Family</option>
                                            <option {{ $deal->rent_own == "Other" ? 'selected':'' }} vlaue="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Monthly Payment Amount <span class="required">*</span></label>
                                        <input type="number" name="rent_own_amount" class="form-control" value="{{ old('rent_own_amount', $deal->rent_own_amount) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Years at Residence <span class="required">*</span></label>
                                        <input type="number" name="years_address" class="form-control" value="{{ old('years_address', $deal->years_address) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Months at Residence <span class="required">*</span></label>
                                        <input type="number" name="months_address" class="form-control" value="{{ old('months_address', $deal->months_address) }}" required>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <script>

       var phoneInput = null;
         $(document).ready(function() {
            const phoneInputField = document.querySelector("#company_phone");
            phoneInput = window.intlTelInput(phoneInputField, {
                onlyCountries: ["us"],
                autoPlaceholder: "off",
                utilsScript:
                "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            });

        });

        function submitPreApprovedInfo()
        {
            var error = false;
            pageProcessingShow();
            var data =  {
                employment_status: $('select[name="employment_status"]').val(),
                job_title: $('input[name="job_title"]').val(),
                company_name: $('input[name="company_name"]').val(),
                company_phone: phoneInput.getNumber(),
                company_address: $('input[name="company_address"]').val(),
                years_company: $('input[name="years_company"]').val(),
                months_company: $('input[name="months_company"]').val(),
                income: $('input[name="income"]').val(),
                rent_own: $('select[name="rent_own"]').val(),
                rent_own_amount: $('input[name="rent_own_amount"]').val(),
                years_address: $('input[name="years_address"]').val(),
                months_address: $('input[name="months_address"]').val(),
               // dl_no: $('input[name="dl_no"]').val(),
               // dl_state: $('input[name="dl_state"]').val(),
               // ssn: $('input[name="ssn"]').val(),
                user_token: '{{ $user_token }}'
            }

            for (const key in data) {
                if($.inArray( key, [ "employment_status", "job_title", "company_name", "company_phone", "company_address", "years_company", "months_company",
                    "income", "rent_own", "rent_own_amount", "years_address", "months_address" ] ) > -1){
                    var value = data[key];
                    if(value.match(/^\s*$/)){
                        console.log('error-> '+key);
                        error = true;
                    }
                }

            }

            if($('select[name="employment_status"]').val() == "" ){
                $('select[name="employment_status"]').addClass('fielderror');
            }else{
                $('select[name="employment_status"]').removeClass('fielderror');
            }

            if($('select[name="rent_own"]').val() == "" ){
                $('select[name="rent_own"]').addClass('fielderror');
            }else{
                $('select[name="rent_own"]').removeClass('fielderror');
            }

            if(error){
                pageProcessingHide();
                showError("You are missing a required field.  Please check the form and submit again.");
                return;
            }

            $.ajax({
                data: data,
                type: "POST",
                url: '{{ url('pre-approved/employee') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                statusCode: {
                    400: function() {
                        processingHide();
                        $('.errors').show();
                        $('.errors span').html('You are missing a required field.  Please check the form and submit again.');
                        showError('Check the required fields.');

                    },
                    200: function() {
                       // setTimeout(function(){
                            window.location.href = "{{ url('summary') }}?user_token={{ $user_token }}";

                        //}, 2500);
                    }
                }
            }).fail(function(jqXHR, status){
                pageProcessingHide();
                showError('There was an error processing request. Try again or skip');

            });
        }
    </script>
@endsection
