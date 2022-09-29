    <div class="side-nav-content">

        <div class="dealer-logo">
            <img src="{{ $dealer->brandLogo }}">
        </div>
        <div class="welcome">
            <div class="side-nav-row {{$currentPage == 'welcome' ? 'active':''}}">
                <div class="sp-nav-img pa-icon">
                    <img class="complete" src="{{ asset('imgs/icons/welcome.png') }}" />
                </div>
                <div class="sp-nav-text welcome">
                    @if($deal->fname != '')
                        <a href="{{ url('welcome?user_token=' . $user_token) }}"><h6>WELCOME, {{ $deal->fname }}</h6></a>
                    @else
                        <a href="{{ url('welcome?user_token=' . $user_token ) }}"><h6>WELCOME</h6></a>
                    @endif
                </div>
            </div>
        </div>
        @if(isset($salesperson) && !empty($salesperson))
            <div class="salesperson-selected">
                <div class="side-nav-row {{$currentPage == 'sales' || $currentPage == 'sales_start' ? 'active':''}}">
                    <a href="{{ url('salesperson-detail?user_token=' . $user_token . '&s_id=' . $salesperson->s_id) }}">
                        <div class="sp-nav-img sp-icon">
                            <img src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}" />
                        </div>
                    </a>
                    <a href="{{ url('salesperson-detail?user_token=' . $user_token . '&s_id=' . $salesperson->s_id) }}">
                        <div class="sp-nav-text">
                            <h6>SALESPERSON</h6>
                            <p>{{ $salesperson->first }} {{ $salesperson->last }}</p>
                            <span class="time-saved">10 Min</span>
                        </div>
                    </a>
                </div>
            </div>
        @else
            <div class="salesperson-selected">
                <div class="side-nav-row {{$currentPage == 'sales' || $currentPage == 'sales_start' ? 'active':''}}">
                    <div class="sp-nav-img sp-icon">
                        <img src="{{ asset('imgs/icons/salesperson.png') }}" />
                    </div>
                    <div class="sp-nav-text">
                        <h6>SALESPERSON</h6>
                        <a href="{{ url('start-sales-person?user_token=' . $user_token ) }}">Start Now
                            @if($next == 'salesperson' && $currentPage != 'sales_start' && $currentPage != 'sales')
                                &nbsp;<i style="color: #039BE5;" class="fas fa-asterisk fa-spin"></i>
                            @endif
                        </a>
                        <span class="time-saved">10 Min </span>

                    </div>
                </div>
            </div>
        @endif

        @if($vehicleCheck)
            <div class="vehicle-selected">
                <div class="side-nav-row {{$currentPage == 'vehicle' ? 'active':''}}">
                    <a href="{{ url('vehicle-detail?user_token=' . $user_token . '&vehicle_id=' . $deal->vehicle_id) }}">
                        <div class="sp-nav-img">
                            <img class="complete" src="{{ asset('imgs/icons/vehicle.png') }}" />
                        </div>
                    </a>
                    <a href="{{ url('vehicle-detail?user_token=' . $user_token . '&vehicle_id=' . $deal->vehicle_id) }}">
                        <div class="sp-nav-text">
                            <h6>VEHICLE</h6>
                            <!-- Added $v_name because there is a flow where $vehicle->model->name is empty but populated in controller. -->
                            @if($vehicle->name)
                                @php
                                    $v_name = '';
                                @endphp
                                <p style="max-width: 130px;white-space: nowrap;">{{$vehicle->name }}</p>
                            @else
                                <p></p>
                            @endif
                            <span class="time-saved">10 Min</span>
                        </div>
                    </a>
                </div>
            </div>
        @else
            <div class="vehicle-selected">
                <div class="side-nav-row {{$currentPage == 'vehicle' ? 'active':''}}">
                    <div class="sp-nav-img">
                        <img src="{{ asset('imgs/icons/vehicle.png') }}" />
                    </div>
                    <div class="sp-nav-text">
                        <h6>VEHICLE</h6>
                        <a href="{{ url('vehicle-select?user_token=' . $user_token) }}">Start Now
                            @if($next == 'vehicle' && $currentPage != 'vehicle')
                                &nbsp;<i style="color: #039BE5;" class="fas fa-asterisk fa-spin"></i>
                            @endif
                        </a>
                        <span class="time-saved">30 Min</span>
                    </div>
                </div>
            </div>
        @endif

         @if($contactCheck)
            <!--<div class="salesperson-selected">
                <div class="side-nav-row {{$currentPage == 'contact' ? 'active':''}}">
                    <a href="{{ url('contact-information?user_token=' . $user_token) }}">
                        <div class="sp-nav-img sp-icon">
                            <img class="complete" src="{{ asset('imgs/icons/salesperson.png') }}" />
                        </div>
                    </a>
                    <a href="{{ url('contact-information?user_token=' . $user_token) }}">
                        <div class="sp-nav-text">
                            <h6>CONTACT</h6>
                            <p>{{ $deal->fname }} {{ $deal->lname }}</p>
                            <span class="time-saved">10 Min</span>
                        </div>
                    </a>
                </div>
            </div>-->
        @else
           <!-- <div class="salesperson-selected">
                <div class="side-nav-row {{$currentPage == 'sales' || $currentPage == 'sales_start' ? 'active':''}}">
                    <div class="sp-nav-img sp-icon">
                        <img src="{{ asset('imgs/icons/salesperson.png') }}" />
                    </div>
                    <div class="sp-nav-text">
                        <h6>CONTACT</h6>
                    <a href="{{ url('contact-information?user_token=' . $user_token) }}">Start Now
                            @if($next == 'contact' && $currentPage != 'contact')
                                &nbsp;<i style="color: #039BE5;" class="fas fa-asterisk fa-spin"></i>
                            @endif
                        </a>
                        <span class="time-saved">10 Min </span>

                    </div>
                </div>
            </div>-->
        @endif



{{--        @if($tradeCheck)--}}
{{--            <div class="schedule-info">--}}
{{--                <div class="side-nav-row {{$currentPage == 'trade' ? 'active':''}}">--}}
{{--                    <a href="{{ url('value-trade?user_token=' . $user_token ) }}">--}}
{{--                        <div class="sp-nav-img">--}}
{{--                            <img class="complete" src="{{ asset('imgs/icons/value-trade.png') }}" />--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                    <a href="{{ url('value-trade?user_token=' . $user_token) }}">--}}
{{--                        <div class="sp-nav-text">--}}
{{--                            <h6>VALUE YOUR TRADE</h6>--}}
{{--                            <p>{{ $deal->trade_year }} {{ $deal->trade_make }} {{ $deal->trade_model }}</p>--}}
{{--                            <span class="time-saved">20 Min</span>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @else--}}
{{--            <div class="schedule-info">--}}
{{--                <div class="side-nav-row {{$currentPage == 'trade' ? 'active':''}}">--}}
{{--                    <div class="sp-nav-img">--}}
{{--                        <img src="{{ asset('imgs/icons/value-trade.png') }}" />--}}
{{--                    </div>--}}
{{--                    <div class="sp-nav-text">--}}
{{--                        <h6>VALUE YOUR TRADE</h6>--}}
{{--                        <a href="{{ url('value-trade?user_token=' . $user_token) }}">Start Now--}}
{{--                            @if($next == 'trade' && $currentPage != 'trade')--}}
{{--                                &nbsp;<i style="color: #039BE5;" class="fas fa-asterisk fa-spin"></i>--}}
{{--                            @endif--}}

{{--                        </a>--}}
{{--                        <span class="time-saved">20 Min</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}
{{--        @if($paymentCheck)--}}
{{--            <div class="schedule-info">--}}
{{--                <div class="side-nav-row {{$currentPage == 'payments' ? 'active':''}}">--}}
{{--                    <a href="{{ url('payments?user_token=' . $user_token) }}">--}}
{{--                        <div class="sp-nav-img">--}}
{{--                            <img class="complete" src="{{ asset('imgs/icons/payment.png') }}" />--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                    <a href="{{ url('payments?user_token=' . $user_token) }}">--}}
{{--                        <div class="sp-nav-text">--}}
{{--                            <h6 style="max-width: 130px;white-space: nowrap;">PAYMENT CALCULATOR</h6>--}}
{{--                            <span class="time-saved">20 Min</span>--}}
{{--                            <br>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}





{{--        @else--}}
{{--            <div class="schedule-info">--}}
{{--                <div class="side-nav-row {{$currentPage == 'payments' ? 'active':''}}">--}}
{{--                    <div class="sp-nav-img">--}}
{{--                        <img src="{{ asset('imgs/icons/payment.png') }}" />--}}
{{--                    </div>--}}
{{--                    <div class="sp-nav-text">--}}
{{--                        <h6 style="max-width: 130px;white-space: nowrap;">PAYMENT CALCULATOR</h6>--}}
{{--                        <a href="{{ url('payments?user_token=' . $user_token) }}">Start Now--}}
{{--                            @if($next == 'payment' && $currentPage != 'payments')--}}
{{--                                &nbsp;<i style="color: #039BE5;" class="fas fa-asterisk fa-spin"></i>--}}
{{--                            @endif--}}
{{--                        </a>--}}
{{--                        <span class="time-saved">20 Min</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}
        @if($appointmentCheck)
            <div class="schedule-info">
                <div class="side-nav-row {{$currentPage == 'appointment' ? 'active':''}}">
                    <a href="{{ url('schedule-appointment?user_token=' . $user_token) }}">
                        <div class="sp-nav-img">
                            <img class="complete" src="{{ asset('imgs/icons/appointment.png') }}" />
                        </div>
                    </a>
                    <a href="{{ url('schedule-appointment?user_token=' . $user_token ) }}">
                        <div class="sp-nav-text">
                            <h6>SCHEDULE APPOINTMENT</h6>
                            <p style="max-width: 130px;white-space: nowrap;">{{ \Carbon\Carbon::parse($deal->td_date . ' ' . $deal->td_time)->format('M d, Y h:i A') }}</p>
                            <span class="time-saved">30 Min</span>
                        </div>
                    </a>
                </div>
            </div>
        @else
            <div class="schedule-info">
                <div class="side-nav-row {{$currentPage == 'appointment' ? 'active':''}}">
                    <div class="sp-nav-img">
                        <img src="{{ asset('imgs/icons/appointment.png') }}" />
                    </div>
                    <div class="sp-nav-text">
                        <h6>SCHEDULE APPOINTMENT</h6>
                        <a href="{{ url('schedule-appointment?user_token=' . $user_token) }}">Start Now
                            @if($next == 'appointment' && $currentPage != 'appointment')
                                &nbsp;<i style="color: #039BE5;" class="fas fa-asterisk fa-spin"></i>
                            @endif
                        </a>
                        <span class="time-saved">30 Min</span>
                    </div>
                </div>
            </div>
        @endif
        @if($preapprovedCheck)
            <div class="schedule-info">
                <div class="side-nav-row {{$currentPage == 'preapproved' ? 'active':''}}">
                    <a href="{{ url('pre-approved?user_token=' . $user_token ) }}">
                        <div class="sp-nav-img">
                            <img class="complete" src="{{ asset('imgs/icons/pre-approved.png') }}" />
                        </div>
                    </a>
                    <a href="{{ url('pre-approved?user_token=' . $user_token) }}">
                        <div class="sp-nav-text">
                            <h6>GET PRE-APPROVED</h6>
                            <span class="time-saved">40 Min</span>
                            <br>
                        </div>
                    </a>
                </div>
            </div>
        @else
            <div class="schedule-info">
                <div class="side-nav-row {{$currentPage == 'preapproved' ? 'active':''}} ">
                    <div class="sp-nav-img">
                        <img src="{{ asset('imgs/icons/pre-approved.png') }}" />
                    </div>
                    <div class="sp-nav-text">
                        <h6>GET PRE-APPROVED</h6>
                        <a href="{{ url('pre-approved?user_token=' . $user_token) }}">Start Now
                            @if($next == 'preapproved' && $currentPage != 'preapproved')
                                &nbsp;<i style="color: #039BE5;" class="fas fa-asterisk fa-spin"></i>
                            @endif
                        </a>
                        <span class="time-saved">40 Min</span>
                    </div>
                </div>
            </div>
        @endif
        @if($vehicleCheck && $preapprovedCheck && $paymentCheck && $tradeCheck && $appointmentCheck && isset($salesperson) && !empty($salesperson))
            <div class="schedule-info">
                <div class="side-nav-row {{$currentPage == 'summary' ? 'active':''}}">
                    <a href="{{ url('summary?user_token=' . $user_token ) }}">
                        <div class="sp-nav-img pa-icon">
                            <img class="complete" src="{{ asset('imgs/icons/summary.png') }}" />
                        </div>
                    </a>
                    <a href="{{ url('summary?user_token=' . $user_token) }}">
                        <div class="sp-nav-text">
                            <h6>SUMMARY</h6>
                        </div>
                    </a>
                </div>
            </div>
        @else
            <div class="schedule-info">
                <div class="side-nav-row {{$currentPage == 'summary' ? 'active':''}}">
                    <a href="{{ url('summary?user_token=' . $user_token) }}">
                        <div class="sp-nav-img pa-icon">
                            <img src="{{ asset('imgs/icons/summary.png') }}" />
                        </div>
                        <div class="sp-nav-text summary">
                            <h6>SUMMARY
                                @if($next == 'summary' && $currentPage != 'summary')
                                &nbsp;<i style="color: #039BE5;" class="fas fa-asterisk fa-spin"></i>
                            @endif
                            </h6>
                        </div>
                    </a>
                </div>
            </div>
        @endif
        <br>
        <div class="progress-div">
            <div class="side-nav-row">
                <span class ="progressbar-text"><i class="fas fa-check-circle"></i>&nbsp;My Progress</span>
                <div class="progress" style = "background-color:#72c2effa; border-radius:.50rem">
                    <div class="progress-bar" role="progressbar" style="width: {{$percentage}}%;" aria-valuenow="{{$percentage}}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="progressbar-text2" style = "float:left">{{$percentage}}%</span>
                <span class="progressbar-text2" style = "float:right">{{$minSaved}} min saved</span>
            </div>
        </div>
    </div>
