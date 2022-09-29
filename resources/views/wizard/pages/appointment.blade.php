@extends('layout')

@section('page-css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
@endsection

@section('top-nav-item')
    <h3><i class="fa fa-car"></i>SCHEDULE YOUR TEST DRIVE</h3>
@endsection

@section('footer-btns')
    <div class="back">
    <a class="btn btn-primary back-btn" href="{{ url('/vehicle-select?user_token=' . $user_token) }}">Back</a>
    </div>
    <div class="bot-buttons">
        <a class="btn btn-primary skip-btn" href="{{ url('/pre-approved?user_token=' . $user_token) }}">SKIP</a>
        <a class="btn btn-primary next-step-btn" onclick="submitTestDriveInfoCheck()">CONTINUE</a>
    </div>
@endsection

@section('footer')
    @include('includes.footer')
@endsection

@section('content')

    <div class="nav-main-container col-md-10">
        <div class="td-info">
            <div class="td-info-header">
            @if(isset($salesperson) && !empty($salesperson))
                    <div style = "display: flex">
                        <img class = "personal_image" src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
                        <h3 style = "margin-top: 20px;">Set Date and Time</h3>
                    </div>
                    <div onclick="showSalespersonModal()" style="padding-top:10px;width:fit-content;cursor:pointer;font-weight:800;font-size: 15px; color:#039BE5 ">
                        <i style="color: #039BE5;" class="fas fa-asterisk fa-question-circle"></i>
                        Ask a Question
                    </div>



                @else
                    <h3>Set Date and Time</h3>
                    @endif

                    <h6 style="margin-bottom: 35px;">
                    I can’t wait to get you behind the wheel of this vehicle. Just let me know when!
                 </h6>

            </div>
            <div class="row">
                <div class="col-md-8 appointment-info">
                    <div class="date-time">
                        <h3>Date and Time</h3>
                        <div class="row">
                            <div class="col-md-6">
                                @if(!empty($deal->td_date))
                                    <div id="datepicker" data-date="{{ $deal->td_date }}" data-date-start-date="{{ \Carbon\Carbon::now() }}" data-date-today-highlight="true" data-date-days-of-week-disabled="[{{ implode(',', $daysClosed) }}]"></div>
                                    <input type="hidden" name="td_date" id="dateCalendar" value="{{ $deal->td_date }}">
                                @else
                                    <div id="datepicker" data-date="{{ \Carbon\Carbon::now() }}" data-date-start-date="{{ \Carbon\Carbon::now() }}" data-date-today-highlight="true" data-date-days-of-week-disabled="[{{ implode(',', $daysClosed) }}]"></div>
                                    <input type="hidden" name="td_date" id="dateCalendar" value="{{ \Carbon\Carbon::now()->format('m/d/Y') }}">
                                @endif

                            </div>
                            <div class="col-md-6">
                                @foreach($hours as $hour)
                                    @if($hour->is_open)
                                        <div class="row day-hours {{ $hour->name }}-hours" data-toggle="buttons">
                                            @php
                                                $hourFrom = (int)str_replace(':00', '',$hour->from);
                                                $hourTo = (int)str_replace(':00', '',$hour->to);
                                            @endphp
                                            @for($hourCount = $hourFrom; $hourCount <= $hourTo; $hourCount++)
                                                @php
                                                    $hourCountString = $hourCount . ':00';
                                                @endphp
                                                <div class="col-lg-3 col-sm-4 btn-group">
                                                    @if($hourCountString === $deal->td_time && \Carbon\Carbon::parse($deal->td_date)->format('l') === $hour->name)

                                                        <label style="min-width:60px" class="btn btn-default time-radio active">
                                                            @if($hourCount > 12)
                                                                <input type="radio" period="pm" name="td_time" value="{{ $hourCount }}:00" checked>
                                                                <div>{{ $hourCount - 12 }}:00</div>
                                                            @elseif($hourCount == 12)
                                                                <input type="radio" period="pm" name="td_time" value="{{ $hourCount }}:00" checked>
                                                                <div>{{ $hourCount }}:00</div>
                                                            @else
                                                                <input type="radio" period="am" name="td_time" value="{{ $hourCount }}:00" checked>
                                                                <div>{{ $hourCount}}:00</div>
                                                            @endif
                                                        </label>
                                                    @else
                                                        <label style="min-width:60px" class="btn btn-default time-radio">
                                                            @if($hourCount > 12)
                                                                <input type="radio" period="pm" name="td_time" value="{{ $hourCount }}:00">
                                                                <div>{{ $hourCount - 12 }}:00</div>
                                                            @elseif($hourCount == 12)
                                                                <input type="radio" period = "pm" name="td_time" value="{{ $hourCount }}:00">
                                                                <div>{{ $hourCount }}:00</div>
                                                            @else
                                                                <input type="radio" period="am" name="td_time" value="{{ $hourCount }}:00">
                                                                <div>{{ $hourCount}}:00</div>
                                                            @endif
                                                        </label>
                                                    @endif
                                                </div>
                                            @endfor
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                        </div>

                    </div>
                    <div style = "padding:20px">
                                    <div style = "font-weight: bold;font-size: 25px;">My Vehicle</div>
                                    @if($vehicleCheck)
                                        <div style = "font-size: 18px;">{{$vehicle->name}}  <a href="vehicle-select?user_token={{$user_token}}" style = "padding-left:20px;font-size:15px;font-weight:bold" >Change Vehicle</a> </div>
                                    @else
                                        <div style = "font-size: 18px;">No vehicle selected. <a href="vehicle-select?user_token={{$user_token}}" style = "padding-left:20px;font-size:15px;font-weight:bold" >Add Vehicle</a> </div>
                                    @endif
                                </div>
                </div>
                <div class="col-md-4 bv-section">
                    <div class="beverages">
                        <h3>Beverage</h3>
                        <div class="btn-group-pill" data-toggle="buttons">
                            @foreach($beverages as $beverage)
                                @if($beverage->title === $deal->td_beverage)
                                    <label style = "white-space: normal;" class="btn btn-default beverage-btn active">
                                        <img style = "float:left;max-width:75px" src="{{$beverage->photoUrl}}">
                                        <input type="radio" name="td_beverage" value="{{ $beverage->title }}" checked>
                                        <div style = "text-align: center;width: 100%;margin-left: -75px;">{{ $beverage->title }}</div>
                                        <p style = "font-size: small;color: black;">{{$beverage->description}}</p>
                                    </label>
                                @elseif(!$beverage->title)

                                @else
                                    <label style = "white-space: normal;" class="btn btn-default beverage-btn">
                                        <img style = "float:left;max-width:75px" src="{{$beverage->photoUrl}}">
                                        <input type="radio" name="td_beverage" value="{{ $beverage->title }}">
                                        <div style = "text-align: center;width: 100%;margin-left: -75px;">{{ $beverage->title }}</div>
                                        <p style = "font-size: small;color: black;">{{$beverage->description}}</p>
                                    </label>
                                @endif
                            @endforeach


                        </div>
                        <div class="bv-comment">
                            <label>Your Comments</label>
                            <textarea class="form-control" name="td_comments">{{ $deal->td_comments }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
         Tracking = {
            user_token: '{{$user_token}}',
            page: '{{$currentPage}}'
        };

        $(document).ready(function() {
            $('.day-hours').hide();
            @if(!empty($deal->td_date))
                $('.{{ \Carbon\Carbon::parse($deal->td_date)->format('l') }}-hours').show();
            @else
                $('.{{ \Carbon\Carbon::now()->format('l') }}-hours').show();
            @endif

            $('#datepicker').datepicker();
            $('#datepicker').on('changeDate', function() {
                var date = $('#datepicker').datepicker('getFormattedDate');
                var day = new Date($('#datepicker').datepicker('getDate'));
                $('.day-hours').hide();
                if(day.getDay() === 0)
                {
                    $('.Sunday-hours').show();
                } else if(day.getDay() === 1) {
                    $('.Monday-hours').show();
                } else if(day.getDay() === 2) {
                    $('.Tuesday-hours').show();
                } else if(day.getDay() === 3) {
                    $('.Wednesday-hours').show();
                } else if(day.getDay() === 4) {
                    $('.Thursday-hours').show();
                } else if(day.getDay() === 5) {
                    $('.Friday-hours').show();
                } else if(day.getDay() === 6) {
                    $('.Saturday-hours').show();
                }
                $('#dateCalendar').val(date);
            });

            $('.time-radio').on('click', function() {
               $('.time-radio').removeClass('active');
               $(this).addClass('active');
            });
        });

        function submitTestDriveInfoCheck(){
            if(contactCheck == 'false'){
                continue_action = true;
                showContactModal();

            }else{
                submitTestDriveInfo();
            }
        }

         function continueAction(){
             if(contactCheck == 'true' && continue_action == true){
                 continue_action = false;
                 submitTestDriveInfo();
             }
         }



         function submitTestDriveInfo()
        {

            pageProcessingShow();
            var textShare = 0;
            var emailShare = 0;
            var calendar = 0;


            if($('input[name="td_time"]:checked').val() == "" || $('input[name="td_time"]:checked').val() == undefined){
                pageProcessingHide();
                showError('Please Select A Time');
                return;
            }

            // if($('input[name="td_beverage"]:checked').val() == "" || $('input[name="td_beverage"]:checked').val() == undefined){
            //     processingHide();
            //     showError('Please Select A Beverage');
            //     return;
            // }

            // if($('input[name="td_share_text"]').prop('checked'))
            // {
            //     textShare = 1;
            // }
            // if($('input[name="td_share_email"]').prop('checked'))
            // {
            //     emailShare = 1;
            // }
            if($('input[name="td_calendar"]').prop('checked'))
            {
                calendar = 1;
            }

            var data =  {
                td_date: $('input[name="td_date"]').val(),
                td_time: $('input[name="td_time"]:checked').val(),
                td_beverage: $('input[name="td_beverage"]:checked').val(),
                td_period: $('input[name="td_time"]:checked').attr('period'),
               // td_share_text: textShare,
               // td_share_email: emailShare,
                td_comments: $('textarea[name="td_comments"]').val(),
                td_calendar: calendar,
                user_token: '{{ $user_token }}'
            }

            $.ajax({
                data: data,
                type: "POST",
                url: '{{ url('schedule-appointment') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).fail(function(jqXHR, status){
                pageProcessingHide();
                showError('There was an error processing request. Try again or skip.');
            }).done(function() {

                //setTimeout(function(){
                    window.location.href = '{{ url('pre-approved') }}?user_token={{ $user_token }}';

                //}, 2500);


            });
        }
    </script>
@endsection
