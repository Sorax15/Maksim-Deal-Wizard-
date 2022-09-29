@extends('layout')

@section('page-css')
    <style>
        .form-range{width:100%;height:1.5rem;padding:0;background-color:transparent;-webkit-appearance:none;-moz-appearance:none;appearance:none}
        .form-range:focus{outline:0}
        .form-range:focus::-webkit-slider-thumb{box-shadow:0 0 0 1px #fff,0 0 0 .25rem rgba(13,110,253,.25)}
        .form-range:focus::-moz-range-thumb{box-shadow:0 0 0 1px #fff,0 0 0 .25rem rgba(13,110,253,.25)}
        .form-range::-moz-focus-outer{border:0}
        .form-range::-webkit-slider-thumb{width:1rem;height:1rem;margin-top:-.25rem;background-color:#039BE5;border:0;border-radius:1rem;-webkit-transition:background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;transition:background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;-webkit-appearance:none;appearance:none

        }@media (prefers-reduced-motion:reduce){.form-range::-webkit-slider-thumb{-webkit-transition:none;transition:none}}
        .form-range::-webkit-slider-thumb:active{background-color:#b6d4fe}
        .form-range::-webkit-slider-runnable-track{width:100%;height:.5rem;color:transparent;cursor:pointer;background-color:#dee2e6;border-color:transparent;border-radius:1rem}
        .form-range::-moz-range-thumb{width:1rem;height:1rem;background-color:#039BE5;border:0;border-radius:1rem;-moz-transition:background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;transition:background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;-moz-appearance:none;appearance:none}
        @media (prefers-reduced-motion:reduce){.form-range::-moz-range-thumb{-moz-transition:none;transition:none}}.form-range::-moz-range-thumb:active{background-color:#b6d4fe}.form-range::-moz-range-track{width:100%;height:.5rem;color:transparent;cursor:pointer;background-color:#dee2e6;border-color:transparent;border-radius:1rem}.form-range:disabled{pointer-events:none}
        .form-range:disabled::-webkit-slider-thumb{background-color:#adb5bd}.form-range:disabled::-moz-range-thumb{background-color:#adb5bd}

    </style>

    <link rel="stylesheet" href="{{ asset('js/jqueryui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('top-nav-item')
    <h3><i class="fa fa-user"></i>PAYMENT CALCULATOR</h3>
@endsection

@section('footer-btns')
    <div class="back">
    <a class="btn btn-primary back-btn" href="{{ url('/value-trade?user_token=' . $user_token) }}">Back</a>
    </div>
    <div class="bot-buttons">
        <a class="btn btn-primary skip-btn" href="{{ url('/schedule-appointment?user_token=' . $user_token) }}">SKIP</a>
        <a class="btn btn-primary next-step-btn" onclick="submitPaymentsCheck()">CONTINUE</a>
    </div>
@endsection

@section('footer')
    @include('includes.footer')
@endsection


@php
$cal_price = (empty($vehicle->price)) ? 0 : number_format($vehicle->price * .10,0,"","");

@endphp

@section('content')

    <div class="nav-main-container col-md-10">
        <div class="payment-info">
            <div class="payment-info-header-section">
            @if(isset($salesperson) && !empty($salesperson))
                    <div style = "display: flex">
                        <img class = "personal_image" src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
                        <h3 style = "margin-top: 20px;">Payment Calculator</h3>
                    </div>
                    <div onclick="showSalespersonModal()" style="padding-top:10px;width:fit-content;cursor:pointer;font-weight:800;font-size: 15px; color:#039BE5 ">
                        <i style="color: #039BE5;" class="fas fa-asterisk fa-question-circle"></i>
                        Ask a Question
                    </div>



                @else
                    <h3>Payment Calculator</h3>
                    @endif

                    <h6 style="margin-bottom: 35px;">
                    Use the selections below to determine your estimated payments.
                 </h6>


            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div class="payment-form-section">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12" style = "margin-bottom:20px">
                                @if(isset($vehicle) && !empty($vehicle))
                                    <h4>Vehicle Price</h4>
                                    <span class="currencyInput">$<input type="text" class="vehicle-price form-control" onkeyup="changeVP(this);" name="vehicle_price" value="{{ isset($vehicle->price) ? $vehicle->price : '' }}"></span>
                                @else
                                    <h4>Vehicle Price</h4>
                                    <span class="currencyInput">$<input type="text" class="vehicle-price form-control" onkeyup="changeVP(this);" name="vehicle_price" value="0"></span>
                                @endif
                            </div>
                            <div class="col-xl-12 col-lg-12" style = "margin-bottom:20px">
                                <h4>Estimated Trade In Value</h4>
                                @if(isset($deal->trade_value) && !empty($deal->trade_value))
                                    <span class="currencyInput">$<input type="text" class="vehicle-price form-control" onkeyup="calculatePayment();" name="trade_value" value="{{ number_format($deal->trade_value,0,'','') }}"></span>
                                @else
                                    <span class="currencyInput">$<input type="text" class="vehicle-price form-control" onkeyup="calculatePayment();" name="trade_value" value="0"></span>
                                @endif
                            </div>
                            <div class="col-xl-12 col-lg-12">
                                <h4>Payoff</h4>
                                @if(isset($deal->trade_payoff) && !empty($deal->trade_payoff))
                                    <span class="currencyInput">$<input type="text" class="vehicle-price form-control" onkeyup="calculatePayment();" name="trade_payoff" value="{{ $deal->trade_payoff }}"></span>
                                @else
                                    <span class="currencyInput">$<input type="text" class="vehicle-price form-control" onkeyup="calculatePayment();" name="trade_payoff" value="0"></span>
                                @endif
                            </div>
                        </div>
                        <div class="down-payment">
                            <h4>Down Payment</h4>
                            <div class="dp-title">
                                <h5 class="clearfix"><span class="range-value"><span class="dp-value">$<span class="dp-max">{{$cal_price}}</span></span></span></h5>
                            </div>


                            <input type="range" onmousemove="dpChange(this.value)" onchange="dpChange(this.value)" class="form-range" min="0" max="{{isset($vehicle->price) ? $vehicle->price :  0}}" value="{{$cal_price}}" id="dpSlider">

                            <input type="hidden" name="dpValue" id="dpValue" value="{{$cal_price}}">
                        </div>
                        <div class="interest-rate">
                            <h4>Interest Rate</h4>
                            <div class="ir-title">
                                <h5 class="clearfix"><span class="range-value"><span class="dp-value"><span class="ir-max">{{isset($deal->payment_interest_rate) ? $deal->payment_interest_rate :  number_format(4.9,1,".","")}}</span>%</span></span></h5>
                            </div>
                            <input type="range" onmousemove="irChange(this.value)" onchange="irChange(this.value)" step="0.1" class="form-range" min="0" max="30" value="{{isset($deal->payment_interest_rate) ? number_format($deal->payment_interest_rate,1,'.','') :  number_format(4.9,1,'.','')}}" id="irSlider">

                            <input type="hidden" id="irValue" name="irValue" value="{{isset($deal->payment_interest_rate) ? number_format($deal->payment_interest_rate,1,'.','') :  number_format(4.9,1,'.','')}}">
                        </div>
                        <div class="loan-term">
                            <h4>Term (Months)</h4>
                            <div class="row term-btns" onclick="calculatePayment()">
                                <a  class="btn btn-primary term-btn {{$deal->payment_term == 12 ? 'active':''}}">12</a>
                                <a  class="btn btn-primary term-btn {{$deal->payment_term == 24 ? 'active':''}}">24</a>
                                <a  class="btn btn-primary term-btn {{$deal->payment_term == 36 ? 'active':''}}">36</a>
                                <a  class="btn btn-primary term-btn {{$deal->payment_term == 48 ? 'active':''}}">48</a>
                                <a  class="btn btn-primary term-btn btn-default-term {{$deal->payment_term == 60 ? 'active':''}}">60</a>
                                <a class="btn btn-primary term-btn {{$deal->payment_term == 72 ? 'active':''}} ">72</a>
                                <a  class="btn btn-primary term-btn {{$deal->payment_term == 84 ? 'active':''}}">84</a>
                            </div>
                            <input type="hidden"  name="termValue" value="{{$deal->payment_term == 12 ? $deal->payment_term: 60}}">
                        </div>
                        <div style="margin-top: 35px;">
                            <a class="btn btn-primary calculate-btn" onclick="calculatePayment()">Calculate Payments</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div class="payments-section">
                        <h2>Your Total Payment Will Be</h2>
                        <div class="monthly-payment">
                            <span class="mpayment"><span class="mp-dollar">$</span> <span class="monthly"></span><span class="month">/month</span></span>
                        </div>
                        <div class="row">
                            <div class="col-md-6" style="padding:10px">
                                <h5>Total Purchase Price</h5>
                                <span>$ <span class="totalPP"></span></span>
                            </div>
                            <div class="col-md-6" style="padding:10px">
                                <h5>Total With Interest</h5>
                                <span>$ <span class="totalF"></span></span>
                            </div>
                        </div>
                        <div class="disclaimer">
                            <p>Payments Exclude Taxes and Fees</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="{{ asset('js/jqueryui/jquery-ui.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            if("{{$deal->payment_term}}" == "" || "{{$deal->payment_term}}" == "null" || "{{$deal->payment_term}}" == "undefinded"){
                $(".btn-default-term").addClass("active");
            }

            $('.term-btn').on('click', function() {
                $('.term-btn').removeClass('active');
                $(this).addClass('active');
                $('input[name="termValue"]').val($(this).text());
            });
            $("#dpSlider").on("touchstart touchmove touchend", function(e) {

               calculatePayment();
            });

            $('.ir-slider').slider({
                range: "min",
                step: 0.1,
                min: 0,
                max: 30,
                value: {{isset($deal->payment_interest_rate) ? number_format($deal->payment_interest_rate,1,".","") :  number_format(4.9,1,".","")}},
                slide: function(event, ui) {
                    $('input[name="irValue"]').val(ui.value);
                    $('.ir-max').text(ui.value);

                },
                change: function( event, ui ) {
                    calculatePayment();
                }
            });



        });

        function dpChange(val){
            $('#dpValue').val(val);
            $('.dp-max').html(val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            calculatePayment();
        }
        function irChange(val){
            $('#irValue').val(val);
            $('.ir-max').html(val);
            calculatePayment();
        }

        function changeVP(e)
        {
            if($(e).val() < 1)return;
            var price = $(e).val() * .10;
            $('input[name="dpValue"]').val(price);
            $('.dp-max').text(price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#dpSlider').prop('max', $(e).val());
            $('#dpSlider').val(price);


            calculatePayment();
        }

        function calculatePayment()
        {
            var vprice = parseFloat($('input[name="vehicle_price"]').val());
            var tvalue = parseFloat($('input[name="trade_value').val());
            var tpayoff = parseFloat($('input[name="trade_payoff').val());
            var dpvalue = parseFloat($('input[name="dpValue"]').val());
            var amount = parseInt(((vprice + tpayoff) - tvalue) - dpvalue);
            var apr = parseFloat($('input[name="irValue"]').val());
            if(apr < 1 || isNaN(apr)){
                apr = 0;
            }
            var term = parseFloat($('input[name="termValue"]').val());
            var interest = apr / 100 / 12;

            var x = Math.pow(1 + interest, term);

            var monthly = (amount*x*interest)/(x-1);
            var monthlyValue = parseInt(monthly.toFixed(2));
            if(monthlyValue < 1 || monthlyValue == NaN){
                monthlyValue = 0;
            }
            var total = parseInt((monthly * term).toFixed(2));
            if(total < 1 || total == 'NaN'){
                total = 0;
            }
            var totalInterest = ((monthly*term)-amount).toFixed(2);

            if(monthlyValue < 1)monthlyValue=0;
            if(amount < 1)amount=0;
            if(total < 1)total =0;

            $('.payments-section').show();
            $('.monthly').text(monthlyValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('.totalPP').text(amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('.totalF').text(total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        }

        function submitPaymentsCheck(){
            if(contactCheck == 'false'){
                continue_action = true;
                 showContactModal();

            }else{
                submitPayments();
            }
        }

        function continueAction(){
            if(contactCheck == 'true' && continue_action == true){
                continue_action = false;
                submitPayments();
            }
        }


        function submitPayments()
        {
            pageProcessingShow();
            var vprice = parseFloat($('input[name="vehicle_price"]').val());
            var tvalue = parseFloat($('input[name="trade_value').val());
            var tpayoff = parseFloat($('input[name="trade_payoff').val());

            var dpvalue = parseFloat($('input[name="dpValue"]').val());
            var amount = ((vprice + tpayoff) - tvalue) - dpvalue;
            var apr = parseFloat($('input[name="irValue"]').val());
            var term = parseFloat($('input[name="termValue"]').val());
            var interest = apr / 100 / 12;

            var x = Math.pow(1 + interest, term);

            var monthly = (amount*x*interest)/(x-1);
            var monthlyValue = monthly.toFixed(2);
            var total = (monthly * term).toFixed(2);

            // if(dpvalue == 0){
            //     showError('Please Add A Down Payment');
            //     return;
            // }

            if(vprice == 0 || vprice < 0 || isNaN(vprice)){
                pageProcessingHide();
                showError('Please set the vehicle price field. Try Again Or Skip.');
                return;
            }

            var data =  {
                payment_monthly: monthlyValue,
                payment_down_payment: dpvalue,
                payment_trade_value: tvalue,
                payment_trade_payoff: tpayoff,
                payment_interest_rate: apr,
                payment_total: total,
                payment_term: term,
                payment_price: vprice,
                user_token: '{{ $user_token }}'
            }

            $.ajax({
                data: data,
                type: "POST",
                url: '{{ url('payments') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).fail(function(jqXHR, status){
                //var msg = jqXHR.responseJSON.status;
                pageProcessingHide();
                showError('Unable To Process Request. Try Again Or Skip.');
            }).done(function() {

                //setTimeout(function(){
                    window.location.href = '{{ url('schedule-appointment') }}?user_token={{ $user_token }}';

                //}, 2500);


            });

        }
    </script>
@endsection
