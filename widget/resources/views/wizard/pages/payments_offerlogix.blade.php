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
    <link rel="stylesheet" href="{{ asset('css/payment_offerlogix.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('top-nav-item')
    <h3><i class="fa fa-user"></i>PAYMENT CALCULATOR</h3>
@endsection

@section('footer-btns')
    <div class="back">
    <a class="btn btn-primary back-btn" href="{{ url('/value-trade?user_token=' . $user_token ) }}">Back</a>
    </div>
    <div class="bot-buttons">
        <a class="btn btn-primary skip-btn" href="{{ url('/schedule-appointment?user_token=' . $user_token) }}">SKIP</a>
        <a class="btn btn-primary next-step-btn" onclick="saveLoanCheck()">SAVE</a>
    </div>
@endsection

@section('footer')
    @include('includes.footer')
@endsection


@php
$cal_price = (empty($vehicle->price)) ? 0 : number_format($vehicle->price * .10,0,"","");

@endphp

@section('content')

    @php
        $financing = '';
        $leasing = '';
        $cash = '';

        if(empty($offerlogix['selected']) || $offerlogix['selected'] == 'leasing' ){
            $leasing = 'active';
        }else if($offerlogix['selected'] == 'financing'){
            $financing = 'active';
        }else if($offerlogix['selected'] == 'cash'){
            $cash = 'active';
        }

    @endphp



    <div class="nav-main-container col-md-10">
        <div class = "section_container">

            <div class = "section1">
            @if(isset($salesperson) && !empty($salesperson))
                    <div style = "display: flex">
                        <img class = "personal_image" src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
                        <h3 style = "margin-top: 20px;">Customize Payments</h3>
                    </div>
                    <div onclick="showSalespersonModal()" style="padding-top:10px;width:fit-content;cursor:pointer;font-weight:800;font-size: 15px; color:#039BE5 ">
                        <i style="color: #039BE5;" class="fas fa-asterisk fa-question-circle"></i>
                        Ask a Question
                    </div>



                @else
                    <h3>Customize Payments</h3>
                    @endif

                    <h6 style="margin-bottom: 35px;">
                    We work with 5 lenders to provide you the lowest payments possible. Don’t forget to check for available incentives.
                 </h6>
                <p><strong>Payments based on Stock # {{$vehicle->stockNumber}} {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->modelName}}.
                    <span><a href="{{ url('/vehicle-select?user_token=' . $user_token) }}">Change Vehicle</a></span></strong>
                </p>
            </div>


            <div class = "section2">
                <div class = "section2_1">
                    <div class = "section_row_v2">
                        <div class = "gray_label">Vehicle Price</div>
                        <div class="price_label">${{ isset($vehicle->price) && !empty($vehicle->price) ? number_format($vehicle->price,0,"",",") : '0' }}</div>
                    </div>
                    <div style = "display:none;" id = "fee_section" class = "section_row_v2">
                        <div class = "gray_label">Fees<span id = "fee_type"></span></div>
                        <div class="price_label" id = "fee_amount"></div>
                    </div>
                    <div style = "display:none;" id = "tax_section" class = "section_row_v2">
                        <div class = "gray_label">Taxes<span id="tax_type"></span></div>
                        <div class="price_label" id = "tax_amount"></div>
                    </div>

                    <div class = "section_row_v2">
                        <div class = "gray_label">Trade-In Value</div>
                        <div class="price_label">${{ !empty($deal->trade_value) ?  number_format($deal->trade_value,0,'',',') : '0' }}</div>
                    </div>
                    <div class = "section_row_v2">
                        <div class = "gray_label">Payoff</div>
                        <div class="price_label">
                            <i style="color: black;margin-left: 5px;position: absolute;margin-top: 10px;" class="fas fa-dollar-sign"></i>
                                <input class="form-control" style = "padding-left:20px;display: unset;max-width: 150px;"  type = "text" pattern="[0-9]+" type = "number" name = "payoff" id="payoff" value="0" />

                        </div>
                    </div>
                    <div class = "section_row_v2">
                        <div class = "gray_label">Down Payment</div>
                        <div class="price_label">
                            <i style="color: black;margin-left: 5px;position: absolute;margin-top: 10px;" class="fas fa-dollar-sign"></i>
                                <input class="form-control" style = "padding-left:20px;display: unset;max-width: 150px;"  type = "text" pattern="[0-9]+" type = "number" name = "downpayment" id="downpayment" value="{{$offerlogix['downpayment']}}" />

                        </div>
                    </div>
                </div>
                <div class = "section2_2">
                    <div class = "section_row">
                        <div class = "black_label">Incentives/Rebates</div>
                        <div class="price_label">$<span id="rebates_value"></span></div>
                    </div>
                    <div class = "section_row rebates_row1">
                        <div id="rebate1" class = "gray_label_light rebate1">&nbsp;</div>
                        <div id="rebate_amount1" class="gray_label_light rebate_amount1">&nbsp;</div>
                    </div>
                    <div class = "section_row rebates_row2">
                        <div id="rebate2" class = "gray_label_light rebate2">&nbsp;</div>
                        <div id="rebate_amount2" class="gray_label_light rebate_amount2">&nbsp;</div>
                    </div>
                    <!-- <div class = "section_row">
                        <div class = "rebates-full-width">

                            <p class="link" >Change Rebates</p>
                        </div>
                    </div> -->
                    <div class = "section_row">
                        <div class = "rebates-full-width rebates_row1">
                            <span class = "black_label">
                                Zip Code &nbsp;&nbsp; <i style="color: gray;margin-left: 5px;position: absolute;margin-top: 10px;" class="fas fa-map-marker-alt"></i>
                                <input class="form-control" style = "padding-left:20px;display: unset;max-width: 100px;"  type = "text" pattern="[0-9]+" maxlength="5" id="zip" name="zip" value = "{{$offerlogix['zip']}}" />
                                <input style = "max-width: 75px;display: inline; border-radius:0px" onclick="changeZip()" type = "button" class = "form-control" value = "Update" />
                            </span>
                           <br>
                            <span class="gray_label_light">
                            Taxes & Incentives based on provided zip code
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <div class = "section6">
            <button type="button" class="btn btn-danger" onclick="recalculateLoan()">RECALCULATE LOAN</button>
            </div>


            <div class = "section3">
                <br>
                <p style = "font-size: 1.75rem;font-weight: 700;margin-top: 20px;">
                    Choose Your Payment Option
                </p>
            </div>



            <div class = "section4">

                <ul style="background-color:white;border-radius:1.25rem" class="nav nav-pills mb-3 nav-fill" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a data-type="leasing" style = "font-size: 20px;border-radius: 1.25rem;font-weight:700;padding: 0.2rem 0.5rem;" class="nav-link {{$leasing}}" id="lease-tab" data-toggle="pill" href="#lease-section" role="tab" aria-controls="lease-section" aria-selected="true">Lease</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a data-type="financing" style = "font-size: 20px;border-radius: 1.25rem;font-weight:700;padding: 0.2rem 0.5rem;" class="nav-link {{$financing}}" id="finance-tab" data-toggle="pill" href="#finance-section" role="tab" aria-controls="finance-section" aria-selected="false">Finance</a>
                </li>
                <!-- <li class="nav-item" role="presentation">
                    <a data-type="cash" style = "font-size: 20px;border-radius: 1.25rem;font-weight:700;padding: 0.2rem 0.5rem;" class="nav-link {{$cash}}" id="cash-tab" data-toggle="pill" href="#cash-section" role="tab" aria-controls="cash-section" aria-selected="false">Cash</a>
                </li> -->
                </ul>
                <div class="tab-content" id="pills-tabContent" style="padding:20px">
                    <div class="tab-pane fade show" id="lease-section" role="tabpanel" aria-labelledby="lease-tab">
                        <div class = "payment_section" id = "lease_payment_section">
                            <div>
                                <div class = "black_label">
                                    Monthly Pament
                                </div>
                                <div class = "interest_rate" id = "lease_monthly_payment">
                                    ${{(!empty($offerlogix['leasing'])?number_format($offerlogix['leasing']['monthlyPayment'],0,"",",") : 'NULL')}}
                                </div>
                            </div>
                            <div class = "lease_section" id = "lease_section">
                                <div id = "lease_interest">
                                    <div class = "black_label">
                                        Interest Rate
                                    </div>
                                    <div class = "interest_rate" id = "lease_interest_rate">
                                        {{(!empty($offerlogix['leasing'])?$offerlogix['leasing']['interestRate']:'NULL')}}%
                                    </div>
                                </div>

                            </div>

                        </div>


                        <div class = "lease_creditscore">
                            <div class = "lease_creditscore_header">
                                <div class="black_label">
                                    Credit Score
                                </div>

                            </div>
                            <div class = "lease_creditscore_items">
                                <div>
                                    <div data-type = "lease" data-rating = "poor" class = "creditscore_item">
                                        600 - 659
                                    </div>
                                    <div class = "credit_label">
                                        POOR
                                    </div>
                                </div>
                                <div>
                                    <div data-type = "lease" data-rating = "fair"  class = "creditscore_item">
                                        660 - 699
                                    </div>
                                    <div class = "credit_label">
                                        FAIR
                                    </div>
                                </div>
                                <div>
                                    <div data-type = "lease" data-rating = "good"  class = "creditscore_item">
                                        700 - 739
                                    </div>
                                    <div class = "credit_label">
                                        GOOD
                                    </div>
                                </div>
                                <div>
                                    <div data-type = "lease" data-rating = "excellent"  class = "creditscore_item">
                                        740 - 850
                                    </div>
                                    <div class = "credit_label">
                                        EXCELLENT
                                    </div>

                                </div>

                            </div>


                        </div>


                        <div class = "terms_section" id = "terms_section">
                            <div id = "lease_terms">
                                <div class = "black_label">
                                    Terms
                                </div>
                                <div>
                                    <select required class = "form-control" id = "lease_term">
                                        <option value = "">--Select Term--</option>
                                        @if(!empty($offerlogix['leasing']))
                                        @foreach($offerlogix['leasing']['terms'] as $term)
                                            @if($term == $offerlogix['leasing']['term'])
                                            <option selected value = "{{$term}}"> {{$term}} months  </option>
                                            @else
                                            <option value = "{{$term}}"> {{$term}} months  </option>
                                            @endif


                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class = "miles_section" id = "miles_section">
                            <div id = "lease_miles">
                                <div class = "black_label">
                                    Lease (miles/year)
                                </div>

                            </div>
                            <div class = "miles_container">
                                <div id = "miles_items" class = "miles_items">
                                    @if(!empty($offerlogix['leasing']))
                                    @foreach($offerlogix['leasing']['miles'] as $mile)

                                    <div>
                                        <div data-miles="{{$mile}}" class = "mile_item">
                                            {{number_format($mile,0,"",",")}}
                                        </div>

                                    </div>

                                    @endforeach
                                    @endif


                                </div>

                            </div>
                        </div>




                    </div>









                    <div class="tab-pane fade show" id="finance-section" role="tabpanel" aria-labelledby="finance-tab">
                        <div class = "payment_section" id = "finance_payment_section">
                            <div>
                                <div class = "black_label">
                                    Monthly Pament
                                </div>
                                <div class = "interest_rate" id = "finance_monthly_payment">
                                    ${{number_format($offerlogix['financing']['monthlyPayment'],0,"",",")}}
                                </div>
                            </div>
                            <div class = "lease_section" id = "lease_section">
                                <div id = "lease_interest">
                                    <div class = "black_label">
                                        Interest Rate
                                    </div>
                                    <div class = "interest_rate" id = "finance_interest_rate">
                                        {{$offerlogix['financing']['interestRate']}}%
                                    </div>
                                </div>

                            </div>

                        </div>


                        <div class = "lease_creditscore">
                            <div class = "lease_creditscore_header">
                                <div class="black_label">
                                    Credit Score
                                </div>

                            </div>
                            <div class = "lease_creditscore_items">
                                <div>
                                    <div data-type = "finance" data-rating = "poor" class = "creditscore_item">
                                        600 - 659
                                    </div>
                                    <div class = "credit_label">
                                        POOR
                                    </div>
                                </div>
                                <div>
                                    <div data-type = "finance" data-rating = "fair"  class = "creditscore_item">
                                        660 - 699
                                    </div>
                                    <div class = "credit_label">
                                        FAIR
                                    </div>
                                </div>
                                <div>
                                    <div data-type = "finance" data-rating = "good"  class = "creditscore_item">
                                        700 - 739
                                    </div>
                                    <div class = "credit_label">
                                        GOOD
                                    </div>
                                </div>
                                <div>
                                    <div data-type = "finance" data-rating = "excellent"  class = "creditscore_item">
                                        740 - 850
                                    </div>
                                    <div class = "credit_label">
                                        EXCELLENT
                                    </div>

                                </div>

                            </div>


                        </div>


                        <div class = "terms_section" id = "terms_section">
                            <div id = "lease_terms">
                                <div class = "black_label">
                                    Terms
                                </div>
                                <div>
                                    <select required class = "form-control" id = "finance_term">
                                        <option value = "">--Select Term--</option>
                                        @foreach($offerlogix['financing']['terms'] as $term)
                                            @if($term == $offerlogix['financing']['term'])
                                            <option selected value = "{{$term}}"> {{$term}} months  </option>
                                            @else
                                            <option value = "{{$term}}"> {{$term}} months  </option>
                                            @endif


                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>






                    </div>

                    <div class="tab-pane fade" id="cash-section" role="tabpanel" aria-labelledby="cash-tab">

                </div>



            </div>

            <div class = "section6">
            <button type="button" class="btn btn-danger" onclick="recalculateLoan()">RECALCULATE LOAN</button>
            </div>









        </div>












    </div>



@endsection

@section('page-js')
    <script src="{{ asset('js/jqueryui/jquery-ui.min.js') }}"></script>

    <script>
        var zipOptions = {};
        var zip = null;
        var zip_string = '';
        var rebates = {};
        var selectedOptionLocation = {};
        var selected = null;
        var leaseCredit = null;
        var financeCredit = null;
        var leaseMiles = null;
        var leaseTerm = null;
        var financeTerm = null;
        var payoff = null;
        var offerlogix_format = String('{{$offerlogix_object}}');
        var offerlogix = JSON.parse(offerlogix_format.replace(/&quot;/g, '"'));
        let dollarUS = Intl.NumberFormat("en-US");
        var user_token = '{{$user_token}}';
        $(document).ready(function() {
            console.log(offerlogix.selected);



            if(offerlogix.selected == 'financing'){
                $('#finance-section').addClass('active');
            }else if(offerlogix.selected == 'leasing'){
                $('#lease-section').addClass('active');
            }else if(offerlogix.selected == 'cash'){
                $('#cash-section').addClass('active');
            }else{
                $('#finance-section').addClass('active');
            }

            initialTabPopulation();

            $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
                selected = $(this).attr('data-type');
                if(selected == 'leasing'){
                    $('#fee_type').html('&nbsp;&nbsp;<i onclick="showAmounts(\'lease\', \'fee\')" style="color: #039BE5;cursor:pointer;" class="fas fa-asterisk fa-question-circle"></i>');
                    $('#fee_amount').html('$'+parseFloat(offerlogix.leasing.fees+offerlogix.doc_fee).toFixed(2));
                    $('#tax_type').html('&nbsp;&nbsp;<i onclick="showAmounts(\'lease\', \'tax\')" style="color: #039BE5;cursor:pointer;" class="fas fa-asterisk fa-question-circle"></i>');
                    $('#tax_amount').html('$'+parseFloat(offerlogix.leasing.taxes).toFixed(2));
                    $('#tax_section').show();
                    $('#fee_section').show();
                }else if(selected == 'financing'){
                    $('#fee_type').html('&nbsp;&nbsp;<i onclick="showAmounts(\'finance\', \'fee\')" style="color: #039BE5;cursor:pointer;" class="fas fa-asterisk fa-question-circle"></i>');
                    $('#fee_amount').html('$'+parseFloat(offerlogix.financing.fees+offerlogix.doc_fee).toFixed(2));
                    $('#tax_type').html('&nbsp;&nbsp;<i onclick="showAmounts(\'finance\', \'tax\')" style="color: #039BE5;cursor:pointer;" class="fas fa-asterisk fa-question-circle"></i>');
                    $('#tax_amount').html('$'+parseFloat(offerlogix.financing.taxes).toFixed(2));
                    $('#tax_section').show();
                    $('#fee_section').show();
                }else{
                    $('#fee_type').html("");
                    $('#fee_amount').html("");
                    $('#tax_type').html("");
                    $('#tax_amount').html("");
                    $('#tax_section').hide();
                    $('#fee_section').hide();
                }

                displayRebates();

            })


            $(document).on('change', '#lease_term', function(){
                leaseTerm = $(this).val();
            });

            $(document).on('change', '#finance_term', function(){
                financeTerm = $(this).val();
            });

            $(document).on('click', '.creditscore_item', function(){
                console.log($(this));
                var type = $(this).attr('data-type');
                var rating = $(this).attr('data-rating');
                leaseCredit = null;
                financeCredit = null;
                if(type == 'lease'){
                    leaseCredit = rating;
                }else{
                    financeCredit = rating;
                }


                console.log('lease - '+leaseCredit);
                console.log('finance - '+financeCredit);

                $('.creditscore_item').css('background-color','#8080805e');
                $('.creditscore_item').css('color','#212529');
                $('.creditscore_item').css('box-shadow', 'none');


                $(this).css('background-color','#007bff');
                $(this).css('color','#fff');
                $(this).css('box-shadow', '3px 2px 6px #0000006b');
            })

            $(document).on('click', '.mile_item', function(){
                var miles = $(this).attr('data-miles');
                leaseMiles = miles;


                $('.mile_item').css('background-color','#8080805e');
                $('.mile_item').css('color','#212529');
                $('.mile_item').css('box-shadow', 'none');


                $(this).css('background-color','#007bff');
                $(this).css('color','#fff');
                $(this).css('box-shadow', '3px 2px 6px #0000006b');
            })

            if("{{$deal->payment_term}}" == "" || "{{$deal->payment_term}}" == "null" || "{{$deal->payment_term}}" == "undefinded"){
                $(".btn-default-term").addClass("active");
            }

            $('.term-btn').on('click', function() {
                $('.term-btn').removeClass('active');
                $(this).addClass('active');
                $('input[name="termValue"]').val($(this).text());
            });






        });

        function initialTabPopulation(){

            var ele = $('.creditscore_item');
            $.each(ele, function(i, obj){
                var element = $('.creditscore_item:eq('+i+')');
                var type = element.attr('data-type');
                var rating = element.attr('data-rating');
                if(rating == offerlogix.credit_score ){
                    element.css('background-color','#007bff');
                    element.css('color','#fff');
                    element.css('box-shadow', '3px 2px 6px #0000006b');
                    leaseCredit = rating;
                    financeCredit = rating;
                }
            })


            if(offerlogix.selected == 'leasing'){


                if(offerlogix.lease_miles > 0){
                    var ele = $('.mile_item');
                    $.each(ele, function(i, obj){
                        var element = $('.mile_item:eq('+i+')');
                        var miles = element.attr('data-miles');
                        if(miles == offerlogix.lease_miles){
                            element.css('background-color','#007bff');
                            element.css('color','#fff');
                            element.css('box-shadow', '3px 2px 6px #0000006b');
                            leaseMiles = miles;
                        }
                    })
                }else{
                    if(offerlogix.leasing.mileageAllowed > 0){
                        var ele = $('.mile_item');
                        $.each(ele, function(i, obj){
                            var element = $('.mile_item:eq('+i+')');
                            var miles = element.attr('data-miles');
                            if(miles == offerlogix.leasing.mileageAllowed){
                                element.css('background-color','#007bff');
                                element.css('color','#fff');
                                element.css('box-shadow', '3px 2px 6px #0000006b');
                                leaseMiles = miles;
                            }
                        })
                    }
                }



                $('#fee_type').html('&nbsp;&nbsp;<i onclick="showAmounts(\'lease\', \'fee\')" style="color: #039BE5;cursor:pointer;" class="fas fa-asterisk fa-question-circle"></i>');
                $('#fee_amount').html('$'+parseFloat(offerlogix.leasing.fees+offerlogix.doc_fee).toFixed(0));
                $('#tax_type').html('&nbsp;&nbsp;<i onclick="showAmounts(\'lease\', \'tax\')" style="color: #039BE5;cursor:pointer;" class="fas fa-asterisk fa-question-circle"></i>');
                $('#tax_amount').html('$'+parseFloat(offerlogix.leasing.taxes).toFixed(0));
                $('#tax_section').show();
                $('#fee_section').show();
                selected = 'leasing';
            }else if(offerlogix.selected == 'financing'){

                $('#fee_type').html('&nbsp;&nbsp;<i onclick="showAmounts(\'finance\', \'fee\')" style="color: #039BE5;cursor:pointer;" class="fas fa-asterisk fa-question-circle"></i>');
                $('#fee_amount').html('$'+parseFloat(offerlogix.financing.fees+offerlogix.doc_fee).toFixed(0));
                $('#tax_type').html('&nbsp;&nbsp;<i onclick="showAmounts(\'finance\', \'tax\')" style="color: #039BE5;cursor:pointer;" class="fas fa-asterisk fa-question-circle"></i>');
                $('#tax_amount').html('$'+parseFloat(offerlogix.financing.taxes).toFixed(0));
                $('#tax_section').show();
                $('#fee_section').show();

                selected = 'financing';
            }else if(offerlogix.selected == 'cash'){

                selected = 'cash';
            }else{
                selected = 'leasing';
            }

            if(offerlogix.finance_term > 0){
                    financeTerm = offerlogix.finance_term;
            }else{
                if(offerlogix.financing.term > 0){
                    financeTerm = offerlogix.financing.term;
                }
            }
            if(offerlogix.lease_term > 0){
                leaseTerm = offerlogix.lease_term;
            }else{
                if(offerlogix.leasing.term > 0){
                    leaseTerm = offerlogix.leasing.term;
                }
            }

            zip = offerlogix.zip;
            zip_string = offerlogix.zip_string;
            payoff = offerlogix.payoff;
            $('#payoff').val(payoff);
            rebates = offerlogix.rebates;
            displayRebates();
        }

        function rebateInfo(id){
            var rebate_found = false;
            var title = '';
            var cash = 0;
            var program_id = '';
            var category = '';
            var disclaimer = '';
            $.each(rebates, function(i, obj){
                var p_id = obj.program_id;
                if(p_id == id){
                    title = obj.title;
                    cash = obj.cash;
                    program_id = obj.program_id;
                    category = obj.category;
                    disclaimer = obj.disclaimer;
                    rebate_found = true;
                }

            });

            if(rebate_found){
                Swal.fire({
                title: '<strong>'+title+'</strong>',
                icon: 'success',
                html:
                    '<div style = "padding:15px"><p style = "font-weight:700">Cash Off - $'+cash+'</p><br>' +
                    '<p><span style = "font-weight:700">Disclaimer:</span><br>'+disclaimer+'</p><br>' +
                    '<p><span style = "font-weight:700">Program ID:</span>&nbsp;&nbsp;'+program_id+'</p><br>' +
                    '<p><span style = "font-weight:700">Category:</span>&nbsp;&nbsp;'+category+'</p><br></div>',
                showCloseButton: false,
                focusConfirm: true

            })
            }

        }

        function displayRebates(){
            var ct = 1;
            var rebate_total = 0;

            $('#rebate1').html('&nbsp;');
            $('#rebate_amount1').html('&nbsp;');
            $('#rebate2').html('&nbsp;');
            $('#rebate_amount2').html('&nbsp;');
            $.each(rebates, function(i, obj){
                var type = obj.type;
                if(type == selected && ct <= 2){
                    var title = obj.title;
                    var cash = obj.cash;
                    var program_id = obj.program_id;
                    rebate_total = rebate_total + parseInt(cash);

                    $('#rebate'+ct).html(title);
                    $('#rebate_amount'+ct).html('$'+cash+'&nbsp; <i onclick="rebateInfo(\''+program_id+'\')" style="cursor:pointer;color: #007bff;" class="fas fa-question-circle"></i>');

                    ct = ct + 1;
                }

            });

            $('#rebates_value').html(rebate_total);
        }

        function changeZip(){

            var input = $('#zip').val();
            Swal.fire({
            title: 'Rebate Finder',
            text: 'Check Zip Code',
            input: 'text',
            inputValue: input,
            allowOutsideClick: false,
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Look up',
            showLoaderOnConfirm: true,
            preConfirm: (zip) => {
                var error = false;
                if(isNaN(zip) || zip.length != 5){
                    error = true;
                }

                if(error){
                    Swal.showValidationMessage(
                    `Zip Code must be a five digit number`
                    )
                }else{

                    zipOptions = {};
                    let data = {zip: zip,user_token: user_token}
                    return fetch('api/offerlogix-zip-lookup', {
                        method: 'POST',
                        headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => {

                        if (!response.ok) {
                        throw new Error('We were unable to find results for given zip code. Please try another.')
                        }

                        response.json().then(data => {
                            zipOptions = data.data;
                            var options = {};
                                $.each( zipOptions, function( key, value ) {

                                    options[key] = `${value.city}, ${value.state} - ${value.county}`;
                                });
                            showZipModal(options);

                        });
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                        `${error}`
                        )
                    })
                }
            }
            })


        }

        function showZipModal(options){
            Swal.fire({
                title: 'Select A Location',
                input: 'select',
                inputOptions: options,
                inputPlaceholder: '--Select Location--',
                showCancelButton: true,
                inputValidator: (value) => {
                    return new Promise((resolve) => {

                    if (value == "" || value == undefined || value == 'undefined') {
                        resolve('Please Select A Location')
                    } else {
                        selectedOptionLocation = {};
                        selectedOptionLocation = zipOptions[value];
                        zip_string = `${selectedOptionLocation.city}, ${selectedOptionLocation.state} - ${selectedOptionLocation.county}`;
                        selectedOptionLocation['zip_string'] = zip_string;
                        zip = selectedOptionLocation.zipCode;
                        $('#zip').val(zip);
                        resolve();
                    }
                    })
                }
            })
        }

        function recalculateLoan(){
            var data = {};
            processingShow();
            data['selected'] = selected;
            data['zip_location'] = selectedOptionLocation;
            data['zip'] = zip;
            data['zip_string'] = zip_string;
            data['lease_credit'] = leaseCredit;
            data['finance_credit'] = financeCredit;
            data['lease_miles'] = leaseMiles;
            data['lease_term'] = leaseTerm;
            data['finance_term'] = financeTerm;
            data['downpayment'] = $('#downpayment').val();
            data['payoff'] = $('#payoff').val();
            data['user_token'] = '{{ $user_token }}';
            data['financing'] = offerlogix.financing;
            data['leasing'] = offerlogix.leasing;
            data['rebates'] = offerlogix.rebates;

            if(selected == 'leasing'){
                if(leaseCredit == "" || leaseCredit == undefined || leaseCredit == 'undefined'){
                    showError('Please select your leasing credit score.');
                    return;
                }
                if(leaseTerm == "" || leaseTerm == undefined || leaseTerm == 'undefined'){
                    showError('Please select your leasing term.');
                    return;
                }
                if(leaseMiles == "" || leaseMiles == undefined || leaseMiles == 'undefined'){
                    showError('Please select your leasing miles/year.');
                    return;
                }

            }else if(selected == 'financing'){
                if(financeCredit == "" || financeCredit == undefined || financeCredit == 'undefined'){
                    showError('Please select your financing credit score.');
                    return;
                }
                if(financeTerm == "" || financeTerm == undefined || financeTerm == 'undefined'){
                    showError('Please select your financing term.');
                    return;
                }

            }


            $.ajax({
                data: data,
                type: "POST",
                url: 'api/offerlogix-calculate',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).fail(function(jqXHR, status){
                //var msg = jqXHR.responseJSON.status;
                $('#lease_monthly_payment').html('N/A');
                $('#lease_interest_rate').html('N/A');
                $('#finance_monthly_payment').html('N/A');
                $('#finance_interest_rate').html('N/A');
                $('#tax_section').hide();
                $('#fee_section').hide();
                $('#rebate1').html('&nbsp;');
                $('#rebate_amount1').html('&nbsp;');
                $('#rebate2').html('&nbsp;');
                $('#rebate_amount2').html('&nbsp;');
                $('#rebates_value').html('0');
                processingHide();
                showError('Unable To Process Request. Try Again Or Skip.');
            }).done(function(rsp) {

                var obj = JSON.parse(rsp);

                offerlogix = obj;
                zip = offerlogix.zip;
                zip_string = offerlogix.zip_string;
                selectedOptionLocation = offerlogix.zip_location;
                selected = offerlogix.selected;
                leaseCredit = offerlogix.credit_score;
                financeCredit = offerlogix.credit_score;
                leaseMiles = (offerlogix.lease_miles == null || offerlogix.lease_miles == "") ? offerlogix.leasing.mileageAllowed : offerlogix.lease_miles;
                leaseTerm =  (offerlogix.lease_term == null || offerlogix.lease_term == "") ? offerlogix.leasing.term : offerlogix.lease_term;
                financeTerm =  (offerlogix.finance_term == null || offerlogix.finance_term == "") ? offerlogix.financing.term : offerlogix.finance_term;
                payoff = offerlogix.payoff;
                rebates = offerlogix.rebates;

                $('#downpayment').val(offerlogix.downpayment);
                $('#zip').val(zip);
                $('#payoff').val(offerlogix.payoff);


                //Leasing prepop
                    $('#lease_monthly_payment').html('$'+parseFloat(offerlogix.leasing.monthlyPayment).toFixed(0));
                    $('#lease_interest_rate').html(offerlogix.leasing.interestRate+'%');


                    $('#lease_term').empty().append('<option val="" >--Select Term--</option>');
                    $.each(offerlogix.leasing.terms, function(i, value){
                        var sel = "";
                        if(value == offerlogix.leasing.term){
                            sel = "selected";
                        }
                        $('#lease_term').append('<option '+sel+' val="'+value+'" >'+value+' months</option>');
                    });

                    $('#miles_items').html('');
                    $.each(offerlogix.leasing.miles, function(i, value){
                        $('#miles_items').append(`
                                    <div>
                                        <div data-miles="${value}" class = "mile_item">
                                            ${dollarUS.format(value)}
                                        </div>

                                    </div>

                        `);
                    });
                    var ele = $('.mile_item');
                    $.each(ele, function(i, obj){
                        var element = $('.mile_item:eq('+i+')');
                        var miles = element.attr('data-miles');
                        if(miles == offerlogix.leasing.mileageAllowed){
                            element.css('background-color','#007bff');
                            element.css('color','#fff');
                            element.css('box-shadow', '3px 2px 6px #0000006b');
                            leaseMiles = miles;
                        }
                    });



                //finance prepop
                    $('#finance_monthly_payment').html('$'+parseFloat(offerlogix.financing.monthlyPayment).toFixed(0));
                    $('#finance_interest_rate').html(offerlogix.financing.interestRate+'%');


                    $('#finance_term').empty().append('<option val="" >--Select Term--</option>');
                    $.each(offerlogix.financing.terms, function(i, value){
                        var sel = "";
                        if(value == offerlogix.financing.term){
                            sel = "selected";
                        }
                        $('#finance_term').append('<option '+sel+' val="'+value+'" >'+value+' months</option>');
                    });


                if(selected == 'leasing'){
                    $('#fee_type').html('&nbsp;&nbsp;<i onclick="showAmounts(\'lease\', \'fee\')" style="color: #039BE5;cursor:pointer;" class="fas fa-asterisk fa-question-circle"></i>');
                    $('#fee_amount').html('$'+parseFloat(offerlogix.leasing.fees+offerlogix.doc_fee).toFixed(0));
                    $('#tax_type').html('&nbsp;&nbsp;<i onclick="showAmounts(\'lease\', \'tax\')" style="color: #039BE5;cursor:pointer;" class="fas fa-asterisk fa-question-circle"></i>');
                    $('#tax_amount').html('$'+parseFloat(offerlogix.leasing.taxes).toFixed(0));
                    $('#tax_section').show();
                    $('#fee_section').show();

                }else if(selected == 'financing'){
                    $('#fee_type').html('&nbsp;&nbsp;<i onclick="showAmounts(\'finance\', \'fee\')" style="color: #039BE5;cursor:pointer;" class="fas fa-asterisk fa-question-circle"></i>');
                    $('#fee_amount').html('$'+parseFloat(offerlogix.financing.fees+offerlogix.doc_fee).toFixed(0));
                    $('#tax_type').html('&nbsp;&nbsp;<i onclick="showAmounts(\'finance\', \'tax\')" style="color: #039BE5;cursor:pointer;" class="fas fa-asterisk fa-question-circle"></i>');
                    $('#tax_amount').html('$'+parseFloat(offerlogix.financing.taxes).toFixed(0));
                    $('#tax_section').show();
                    $('#fee_section').show();
                }else{
                    $('#tax_section').hide();
                    $('#fee_section').hide();
                }


                $('.creditscore_item').css('background-color','#8080805e');
                $('.creditscore_item').css('color','#212529');
                $('.creditscore_item').css('box-shadow', 'none');

                var ele = $('.creditscore_item');
                $.each(ele, function(i, obj){
                    var element = $('.creditscore_item:eq('+i+')');
                    var type = element.attr('data-type');
                    var rating = element.attr('data-rating');
                    if(rating == offerlogix.credit_score  && type == 'lease'){
                        element.css('background-color','#007bff');
                        element.css('color','#fff');
                        element.css('box-shadow', '3px 2px 6px #0000006b');
                        leaseCredit = rating;
                    }
                    if(rating == offerlogix.credit_score  && type == 'finance'){
                        element.css('background-color','#007bff');
                        element.css('color','#fff');
                        element.css('box-shadow', '3px 2px 6px #0000006b');
                        financeCredit = rating;
                    }
                })

                displayRebates();


                $('html, body').animate({
                    scrollTop: $("#pills-tab").offset().top
                }, 1000);

                processingHide();
            });


        }

        function showAmounts(type, fee_type){
            var title = '';
            var obj = {};

            if(type == 'finance'){
                title += 'Financing ';
              if(fee_type == 'fee'){
                  title += 'Fees ';
                  obj = offerlogix.financing.fees_detail;
              }else{
                  title += 'Taxes ';
                  obj = offerlogix.financing.taxes_detail;
              }

            }else{
                title += 'Leasing ';
                if(fee_type == 'fee'){
                    title += 'Fees ';
                    obj = offerlogix.leasing.fees_detail;
                }else{
                    title += 'Taxes ';
                    obj = offerlogix.leasing.taxes_detail;
                }
            }
            var template = '';

            if(fee_type == 'fee'){
                template += '<div style = "padding:10px;justify-items:left;display:grid;grid-template-columns: 200px 1fr;font-weight: 800"><div>Doc Fee</div> <div>$'+offerlogix.doc_fee+'</div></div>';
            }

            $.each(obj, function(i, item){
                template += '<div style = "padding:10px;justify-items:left;display:grid;grid-template-columns: 200px 1fr;font-weight: 800"><div>'+item.name+'</div> <div>$'+item.amount.toFixed(2)+'</div></div>';
            });


            if(template == ''){
                template = '<p>N/A</p>';
            }

            Swal.fire({
                title: title,
                icon: 'info',
                html: template,
                showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false

            })

        }

        function saveLoanCheck(){
            if(contactCheck == 'false'){
                showContactModal();

            }else{
                saveLoan();
            }
        }

        function saveLoan(){
            var data = {};
            processingShow();
            data['selected'] = selected;
            data['zip_location'] = selectedOptionLocation;
            data['zip'] = zip;
            data['zip_string'] = zip_string;
            data['lease_credit'] = leaseCredit;
            data['finance_credit'] = financeCredit;
            data['lease_miles'] = leaseMiles;
            data['lease_term'] = leaseTerm;
            data['finance_term'] = financeTerm;
            data['downpayment'] = $('#downpayment').val();
            data['payoff'] = $('#payoff').val();
            data['user_token'] = '{{ $user_token }}';
            data['financing'] = offerlogix.financing;
            data['leasing'] = offerlogix.leasing;
            data['rebates'] = offerlogix.rebates;

            if(selected == 'leasing'){
                if(leaseCredit == "" || leaseCredit == undefined || leaseCredit == 'undefined'){
                    showError('Please select your leasing credit score.');
                    return;
                }
                if(leaseTerm == "" || leaseTerm == undefined || leaseTerm == 'undefined'){
                    showError('Please select your leasing term.');
                    return;
                }
                if(leaseMiles == "" || leaseMiles == undefined || leaseMiles == 'undefined'){
                    showError('Please select your leasing miles/year.');
                    return;
                }

            }else if(selected == 'financing'){
                if(financeCredit == "" || financeCredit == undefined || financeCredit == 'undefined'){
                    showError('Please select your financing credit score.');
                    return;
                }
                if(financeTerm == "" || financeTerm == undefined || financeTerm == 'undefined'){
                    showError('Please select your financing term.');
                    return;
                }

            }

            pageProcessingShow();
            $.ajax({
                data: data,
                type: "POST",
                url: 'api/offerlogix-save',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).fail(function(jqXHR, status){
                //var msg = jqXHR.responseJSON.status;
                pageProcessingHide();
                showError('Unable To Process Request. Try Again Or Skip.');
            }).done(function(rsp) {

                window.location.href = '{{ url('schedule-appointment') }}?user_token={{ $user_token }}';


            });

        }



    </script>
@endsection
