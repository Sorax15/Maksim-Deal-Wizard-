@extends('layout')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/trade-value.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('top-nav-item')
    <h3><i class="fa fa-user"></i>VALUE YOUR TRADE</h3>
@endsection

@section('footer-btns')
    <div class="back">
    <a class="btn btn-primary back-btn" href="{{ url('/contact-information?user_token=' . $user_token ) }}">Back</a>
    </div>
    <div class="bot-buttons">
       <!-- <a class="btn btn-primary skip-btn" href="{{ url('/payments?user_token=' . $user_token) }}">SKIP</a>-->
        <a class="btn btn-primary next-step-btn" onclick="submitValueTradeInfo()">CONTINUE</a>
    </div>
@endsection

@section('footer')
    @include('includes.footer')
@endsection

@section('content')
    <div class="nav-main-container col-md-10">
        <div class="row">
            <div class="col-lg-6 col-sm-12">
                <div class="trade-info">
                    <div class="trade-info-header">
                    @if(isset($salesperson) && !empty($salesperson))
                    <div style = "display: flex">
                        <img class = "personal_image" src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
                        <h3 style = "margin-top: 20px;">Value Your Trade</h3>
                    </div>
                            <div onclick="showSalespersonModal()" style="padding-top:10px;width:fit-content;cursor:pointer;font-weight:800;font-size: 15px; color:#039BE5 ">
                                <i style="color: #039BE5;" class="fas fa-asterisk fa-question-circle"></i>
                                Ask a Question
                            </div>



                        @else
                    <h3>Value Your Trade</h3>
                    @endif

                    <h6 style="margin-bottom: 35px;">
                    Let’s get a value for your trade-in! Enter your vehicle and our system will provide you a full report on your vehicle’s estimated trade-in value.
                 </h6>


                    </div>
                    <div class="trade-info-form-section">
                        <h3>Vehicle Information</h3>
                        <hr />
                        <div class="trade-form">
                            <div class="row" style = "padding-bottom: 10px;">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Year</label>
                                        <select class="form-control" id="trade_year" onchange="changeYear(this)" name="trade_year">
                                            <option value=""></option>
                                            @for($x = date('Y'); $x >= 1990; $x--)
                                                @if($x == $deal->trade_year)
                                                    <option value="{{ $x }}" selected>{{ $x }}</option>
                                                @else
                                                    <option value="{{ $x }}">{{ $x }}</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Make</label>
                                        <select class="form-control" id="trade_make" onchange="changeMake(this)" name="trade_make">
                                            <option value =""></option>
                                            @if($deal->trade_make != '')
                                                <option value="{{ $deal->trade_make }}" selected>{{ $deal->trade_make }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Model</label>
                                        <select class="form-control" id="trade_model" onchange="changeModel(this)" name="trade_model">
                                            <option value=""></option>
                                            @if($deal->trade_model != '')
                                                <option value="{{ $deal->trade_model }}" selected>{{ $deal->trade_model }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="padding-bottom: 10px;">

                                <div class="col-md-6" style="padding-bottom:20px;display: {{$deal->trade_trim != '' ? 'block':'none'}} " id="trim_block">
                                    <label>Style or Trim</label>
                                    <select class="form-control" id="trade_trim" onchange="changeTrim(this)"  name="trade_trim">
                                        <option value=""></option>
                                        @if($deal->trade_trim != '')
                                            <option value="{{ $deal->trade_trim }}" selected>{{ $deal->trade_trim }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6" style="padding-bottom:20px;display: {{$deal->trade_body != '' ? 'block':'none'}} " id="body_block">
                                    <label>Body</label>
                                    <select class="form-control" id="trade_body" onchange="changeBody(this)"  name="trade_body">
                                        <option value=""></option>
                                        @if($deal->trade_body != '')
                                            <option value="{{ $deal->trade_body }}" selected>{{ $deal->trade_body }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="">
                                <div class="col-md-6" style="padding-bottom:20px;display: {{$deal->trade_drivetrain != '' ? 'block':'none'}} " id="drivetrain_block">
                                     <label>Drivetrain</label>
                                    <select class="form-control" id="trade_drivetrain" onchange="changeDrivetrain(this)"  name="trade_drivetrain">
                                        <option value=""></option>
                                        @if($deal->trade_drivetrain != '')
                                            <option value="{{ $deal->trade_drivetrain }}" selected>{{ $deal->trade_drivetrain }}</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-6" style="padding-bottom:20px;display: {{$deal->trade_engine != '' ? 'block':'none'}} " id="engine_block">
                                     <label>Engine</label>
                                    <select class="form-control" id="trade_engine" onchange="changeEngine(this)"  name="trade_engine">
                                        <option value=""></option>
                                        @if($deal->trade_engine != '')
                                            <option value="{{ $deal->trade_engine }}" selected>{{ $deal->trade_engine }}</option>
                                        @endif
                                    </select>
                                </div>

                            </div>
                            <div class="row" style="">

                                <div class="col-md-6" style="padding-bottom:20px;display: {{$deal->trade_fuel != '' ? 'block':'none'}} " id="fuel_type_block">
                                     <label>Fuel Type</label>
                                    <select class="form-control" id="trade_fuel_type" onchange="changeFuel(this)"  name="trade_fuel_type">
                                        <option value=""></option>
                                        @if($deal->trade_fuel != '')
                                            <option value="{{ $deal->trade_fuel }}" selected>{{ $deal->trade_fuel }}</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="width:100%;text-align:left">Miles</label>
                                        <input type="text" id="trade_miles" name="trade_miles" class="form-control" value="{{ old('trade_miles', $deal->trade_miles) }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div id="submit" class="calculate-btn">
                            <input type="hidden" name="trade_value" value="0">
                            <a class="calculate-trade-btn btn" onclick="calculateTradeCheck();">Get Estimated Trade Value</a>
                        </div><br>

                         <div id="error_section" style = "display:none" class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            <strong id = "error_section_msg"></strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12">

                <div id="trade-frame" style = "height:1000px;border: 3px solid #1517196e;border-radius: 5px;margin: 5px;display:none" class="trade-value-iframe">


                </div>
                <div id="trade-img" style = "height:1000px;border: 3px solid #1517196e;border-radius: 5px;margin: 5px;display:block" class="trade-value-iframe">
                    <img style = "width:100%;" src="{{ asset('imgs/tradein.jpg') }}" />
                </div>

            </div>
        </div>

    </div>
@endsection

@section('page-js')
    <script>


        let reportGenerated = false;


        function changeYear(e)
        {
            $.get('https://snap-api.tradepending.com/api/v4/select?country=US&partner_id=LSTXvyra7CZgqCdYX&year=' + $(e).val(), function(data) {
                var makes = data.choices;

                $('select[name="trade_make"]').html('');
                $('select[name="trade_make"]').append('<option></option>');

                $('select[name="trade_model"]').html('');
                $('select[name="trade_model"]').append('<option></option>');

                $('select[name="trade_trim"]').html('');
                $('select[name="trade_trim"]').append('<option></option>');
                $('#trim_block').hide();

                $('select[name="trade_drivetrain"]').html('');
                $('select[name="trade_drivetrain"]').append('<option></option>');
                $('#drivetrain_block').hide();

                $('select[name="trade_body"]').html('');
                $('select[name="trade_body"]').append('<option></option>');
                $('#body_block').hide();

                $('select[name="trade_engine"]').html('');
                $('select[name="trade_engine"]').append('<option></option>');
                $('#engine_block').hide();

                $('select[name="trade_fuel_type"]').html('');
                $('select[name="trade_fuel_type"]').append('<option></option>');
                $('#fuel_type_block').hide();

                for(let i = 0; i < makes.length; i++)
                {
                    $('select[name="trade_make"]').append('<option value="' + makes[i] + '">' + makes[i] + '</option>');
                }
            })
            .fail(function(){
                showError('Unable to Load Makes');
            });
        }

        function changeMake(e)
        {
            $.get('https://snap-api.tradepending.com/api/v4/select?country=US&partner_id=LSTXvyra7CZgqCdYX&year=' + $('select[name="trade_year"]').val() + '&make=' + $(e).val() , function(data) {
                var models = data.choices;

                $('select[name="trade_model"]').html('');
                $('select[name="trade_model"]').append('<option></option>');

                $('select[name="trade_trim"]').html('');
                $('select[name="trade_trim"]').append('<option></option>');
                $('#trim_block').hide();

                $('select[name="trade_drivetrain"]').html('');
                $('select[name="trade_drivetrain"]').append('<option></option>');
                $('#drivetrain_block').hide();

                $('select[name="trade_body"]').html('');
                $('select[name="trade_body"]').append('<option></option>');
                $('#body_block').hide();

                $('select[name="trade_engine"]').html('');
                $('select[name="trade_engine"]').append('<option></option>');
                $('#engine_block').hide();

                $('select[name="trade_fuel_type"]').html('');
                $('select[name="trade_fuel_type"]').append('<option></option>');
                $('#fuel_type_block').hide();

                for(let i = 0; i < models.length; i++)
                {
                    $('select[name="trade_model"]').append('<option value="' + models[i] + '">' + models[i] + '</option>');
                }
            })
                .fail(function(){
                    showError('Unable to Load Models');
                });
        }

        function changeModel(e)
        {
            $('select[name="trade_trim"]').html('');
            $('select[name="trade_trim"]').append('<option></option>');
            $('#trim_block').hide();

            $('select[name="trade_drivetrain"]').html('');
            $('select[name="trade_drivetrain"]').append('<option></option>');
            $('#drivetrain_block').hide();

            $('select[name="trade_body"]').html('');
            $('select[name="trade_body"]').append('<option></option>');
            $('#body_block').hide();

            $('select[name="trade_engine"]').html('');
            $('select[name="trade_engine"]').append('<option></option>');
            $('#engine_block').hide();

            $('select[name="trade_fuel_type"]').html('');
            $('select[name="trade_fuel_type"]').append('<option></option>');
            $('#fuel_type_block').hide();

            processItem('model');
        }

        function changeTrim(e)
        {
            processItem('trim');
        }

        function changeBody(e)
        {
            processItem('body');
        }

        function changeDrivetrain(e)
        {
            processItem('drivetrain');
        }

        function changeEngine(e)
        {
            processItem('engine');
        }

        function changeFuel(e)
        {
            processItem('fuel');
        }

        function processItem(type){
            $.get('https://snap-api.tradepending.com/api/v4/select?country=US&partner_id=LSTXvyra7CZgqCdYX&year=' + $('select[name="trade_year"]').val() +
                    '&make=' + $('select[name="trade_make"]').val() + '&model='+$('select[name="trade_model"]').val()+'&trim=' + $('select[name="trade_trim"]').val()+
                        '&drivetrain=' + $('select[name="trade_drivetrain"]').val()+ '&body=' + $('select[name="trade_body"]').val()+'&fuel_type=' + $('select[name="trade_fuel_type"]').val()+'&engine=' + $('select[name="trade_engine"]').val() , function(data) {
                var title = 'trade_'+type;
                var block = '';
                if(type == "trim")block = "trim_block";
                if(type == "drivetrain")block = "drivetrain_block";
                if(type == "body")block = "body_block";
                if(type == "engine")block = "engine_block";
                if(type == "fuel")block = "fuel_type_block";

                if('id' in data == false){
                //     if(type != ''){
                //         $('select[name="'+title+'"]').html('');
                //         $('select[name="'+title+'"]').append('<option></option>');
                //         $('#'+block+'').hide();
                //     }

                // }else{
                    var choices = data.choices;
                    var selected = data.select;
                    $('select[name="trade_'+selected+'"]').html('');
                    $('select[name="trade_'+selected+'"]').append('<option></option>');

                    for(let i = 0; i < choices.length; i++)
                    {
                        $('select[name="trade_'+selected+'"]').append('<option value="' + choices[i] + '">' + choices[i] + '</option>');
                    }
                    $('#'+selected+'_block').show();
                }


            })
            .fail(function(){
                showError('Unable to Load next dropdown. Please try again.');
            });
        }

        function calculateTradeCheck(){
            if(contactCheck == 'false'){
                continue_action = true;
                showContactModal();

            }else{
                calculateTrade();
            }

        }

        function continueAction(){
            if(contactCheck == 'true' && continue_action == true){
                var year = $('select[name="trade_year"]').val();
                var make = $('select[name="trade_make"]').val();
                var model = $('select[name="trade_model"]').val();
                var mileage = $('input[name="trade_miles"]').val();

                if(year != '' && make != '' && model != '' && mileage != '') {
                    continue_action = false;
                    calculateTrade();
                }


            }
        }

        function calculateTrade()
        {

            reportGenerated = false;
            $('#error_section').hide();
            $('.trade-value-iframe').empty();
            $('#trade-frame').hide();
            var year = $('select[name="trade_year"]').val();
            var make = $('select[name="trade_make"]').val();
            var model = $('select[name="trade_model"]').val();
            var trim = $('select[name="trade_trim"]').val();
            var mileage = $('input[name="trade_miles"]').val();
            var body = $('input[name="trade_body"]').val();

            if(year == '' || make == '' || model == '' || mileage == '')
            {

                showError('Please enter all values to get your Trade Value');
            } else {
                $('input[name="trade_value"]').val('0');
                $.get('https://snap-api.tradepending.com/api/v4/select?country=US&partner_id=LSTXvyra7CZgqCdYX&year=' + $('select[name="trade_year"]').val()+ '&engine=' + $('select[name="trade_engine"]').val() + '&make=' + $('select[name="trade_make"]').val() + '&fuel_type=' + $('select[name="trade_fuel_type"]').val() + '&model=' + $('select[name="trade_model"]').val() + '&trim=' + $('select[name="trade_trim"]').val()+ '&drivetrain=' + $('select[name="trade_drivetrain"]').val()+ '&body=' + $('select[name="trade_body"]').val() , function(data) {
                    var vehicleId = data.id;
                    var url = 'https://snap-api.tradepending.com/api/v4/report-html?vehicle_id=' + vehicleId + '&url=vip.buildabrand.com&zip_code={{ $dealer->zip }}&partner_id=LSTXvyra7CZgqCdYX&mileage=' + mileage;
                    console.log(url);

                    //Test url for frame to see if it is okay
                    $.get(url)
                     .fail(function(){
                        showError('Unable to Get Vehicle Report');

                    }).done(function(){

                        $.get('https://snap-api.tradepending.com/api/v4/report?country=US&partner_id=LSTXvyra7CZgqCdYX&url=vip.biuldabrand.com&zip_code={{ $dealer->zip }}&vehicle_id=' + vehicleId + '&mileage=' + mileage)
                        .fail(function(){
                            showError('Unable to Get Vehicle Report');

                        }).done(function(data){
                            if('target' in data.report.tradein){
                                $('#trade-img').hide();
                                $('.trade-value-iframe').append('<iframe src="' + url + '"></iframe>');
                                $('input[name="trade_value"]').val(data.report.tradein.target);
                                $('#trade-frame').show();
                                reportGenerated =true;
                                console.log('report generated');
                                submitValueTradeInfo('false');
                            }else{
                                if('error_message' in data.report){
                                    showError(data.report.error_message, 10000);
                                }else{
                                    showError('Unable to generate report, try again or skip.');
                                }
                            }

                        });
                    });




                })
                .fail(function(){
                    showError('Unable to Get Vehicle Report');

                });
            }
        }



        function submitValueTradeInfo(redirect)
        {
            var pagechange = redirect || 'true';

            if(reportGenerated == false){
                showError("Please click the 'GET TRADE VALUE' button before continuing and generate a successful report", 10000);
                return;
            }
            pageProcessingShow();
            var data =  {
                trade_year: $('select[name="trade_year"]').val(),
                trade_make: $('select[name="trade_make"]').val(),
                trade_model: $('select[name="trade_model"]').val(),
                trade_trim: $('select[name="trade_trim"]').val(),
                trade_drivetrain: $('select[name="trade_drivetrain"]').val(),
                trade_body: $('select[name="trade_body"]').val(),
                trade_engine: $('select[name="trade_engine"]').val(),
                trade_miles: $('input[name="trade_miles"]').val(),
                trade_fuel: $('select[name="trade_fuel_type"]').val(),
                trade_value: parseInt($('input[name="trade_value"]').val()),
                user_token: '{{ $user_token }}'
            }

            if(data.trade_value == "" || data.trade_value == undefined){
                showError('No Trade Report Generated. Change Vehicle Or Skip Section');
                pageProcessingHide();
                return;
            }

            $.ajax({
                data: data,
                type: "POST",
                url: "{{ url('value-trade') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).fail(function(jqXHR, status){
                //var msg = jqXHR.responseJSON.status;
                pageProcessingHide();
                showError('Error Processing Request. Change Vehicle Or Skip Section.');
            }).done(function() {

                //setTimeout(function(){

                    if(pagechange == 'true'){
                        window.location.href = "{{ url('payments') }}?user_token={{ $user_token }}";

                    }else{
                        pageProcessingHide();
                        showMessage('Your information was successfully saved. Please continue to the next step.', 10000);
                    }

               // }, 2500);


            });
        }

        $(document).ready(function(){
            var year = $('select[name="trade_year"]').val();
            var make = $('select[name="trade_make"]').val();
            var model = $('select[name="trade_model"]').val();
            //var trim = $('select[name="trade_trim"]').val();
            var mileage = $('input[name="trade_miles"]').val();
            //var body = $('input[name="trade_body"]').val();

            if(year == '' || make == '' || model == '' || mileage == '')
            {

            } else {

                calculateTrade('false');
            }

          
        });

    </script>
@endsection
