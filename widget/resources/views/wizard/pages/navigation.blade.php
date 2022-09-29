
@extends('layout')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
    <link href="//amp.azure.net/libs/amp/2.3.9/skins/amp-default/azuremediaplayer.min.css" rel="stylesheet">
    <style>

    </style>

@endsection

@section('top-nav-item')
    <h3>EXPRESS BUYING EXPERIENCE AT {{ strtoupper($dealer->dealer_name) }}</h3>

@endsection



@section('content')

    <div class="nav-main-container col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="welcome-section">
                    @if(!is_null($deal->fname))
                        <h1 style="text-transform: uppercase">WELCOME {{ $deal->fname }}!</h1>
                    @else
                        <h1 style="text-transform: uppercase">WELCOME!</h1>
                    @endif

                    @if(isset($salesperson) && !empty($salesperson))
                    <div style = "display: flex">
                        <img class = "personal_image" src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
                        <h3 style = "margin-top: 20px;">
                            @if(isset($welcomeInfo->header))
                                {{$welcomeInfo->header}}
                            @else
                                Glad to See You Here.
                            @endif

                            <div onclick="showSalespersonModal()" style="width:fit-content;cursor:pointer;font-weight:800;font-size: 15px; color:#039BE5 ">
                                <i style="color: #039BE5;" class="fas fa-asterisk fa-question-circle"></i>
                                Ask a Question
                            </div>

                            

                        </h3>

                    </div>

                    @else
                    <h3>
                        @if(isset($welcomeInfo->header))
                            {{$welcomeInfo->header}}
                        @else
                            Glad to See You Here.
                        @endif
                    </h3>
                    @endif

                    <h6 style="margin-bottom: 35px;">

                        @if(isset($welcomeInfo->message))
                            {{$welcomeInfo->message}}
                        @else
                            @if(isset($salesperson) && !empty($salesperson))
                                This is {{ $salesperson->first }} {{ $salesperson->last }}! I’m here to help you in your journey to find the best vehicle and to make this process easy. <br><br>
                            @endif
                            In this Express Buying site you can customize your payments, get a immediate value for your trade, start the credit application, schedule a test drive and more.

                        @endif


                    </h6>

                    <div class="video-container" style="text-align: center;">

                        @if(isset($welcomeInfo->media->resourceUrl))

                            @if($welcomeInfo->media->videoId == null || empty($welcomeInfo->media->videoId))

                            <img style = "max-width:100%" src="{{ $welcomeInfo->media->resourceUrl }}"/>

                            @else
                                <video id="vid1" class="azuremediaplayer amp-default-skin" autoplay controls width="100%" height="400" fluid="true" poster="{{$welcomeInfo->media->thumbnail}}" data-setup='{"logo": { "enabled": false },"techOrder": ["azureHtml5JS", "flashSS", "html5FairPlayHLS","silverlightSS", "html5"], "nativeControlsForTouch": false}'>
                                    <source src="{{$welcomeInfo->media->resourceUrl}}" type="application/vnd.ms-sstr+xml" />
                                    <p class="amp-no-js">
                                        To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video
                                    </p>
                                </video>
                            @endif

                        @else
                            <img style = "max-width:100%" src="{{ $dealer->brandImage }}"/>
                        @endif



                    </div>
                </div>
            </div>
            <div class="col-md-6" >
                <div class="welcome-links">
                    <h4>Save Time At The Dealership...</h4>
                    <div class="btn-group-vertical" role="group" aria-label="Basic outlined example">
                    @if(isset($salesperson) && !empty($salesperson))
                       <!-- <a href="{{ url('salesperson-detail?user_token=' . $user_token . '&s_id=' . $salesperson->s_id . '&d_id=' . $d_id . '&deal_id=' . $deal->id) }}">
                           <span style="background-color:#039BE5;width:100%;height: 40px;padding-top: 10px;" class="badge badge-pill badge-primary">
                            @if($next == "salesperson")
                                &nbsp;<i style="color: white;" class="fas fa-asterisk fa-spin fa-xs"></i>
                            @endif
                            Select Your Salesperson
                            </span>


                    </a>-->
                    @else
                        <a href="{{ url('start-sales-person?user_token=' . $user_token) }}">
                            <span style="background-color:#039BE5;width:100%;height: 40px;padding-top: 10px;" class="badge badge-pill badge-primary">
                            @if($next == "salesperson")
                                &nbsp;<i style="color: white;" class="fas fa-asterisk fa-spin fa-xs"></i>
                            @endif> Select Your Salesperson

                            </span>

                    </a>
                    @endif


                    @if($vehicleCheck)
                       <!-- <a href="{{ url('vehicle-detail?user_token=' . $user_token . '&d_id=' . $d_id . '&deal_id=' . $deal->id . '&vehicle_id=' . $deal->vehicle_id) }}">
                            <span style="background-color:#039BE5;width:100%;height: 40px;padding-top: 10px;" class="badge badge-pill badge-primary">
                            @if($next == "vehicle")
                                &nbsp;<i style="color: white;" class="fas fa-asterisk fa-spin fa-xs"></i>
                            @endif
                            Select Your Vehicle</span></a>-->
                    @else
                        <a href="{{ url('vehicle-select?user_token=' . $user_token ) }}">
                            <span style="background-color:#039BE5;width:100%;height: 40px;padding-top: 10px;" class="badge badge-pill badge-primary">
                            @if($next == "vehicle")
                                &nbsp;<i style="color: white;" class="fas fa-asterisk fa-spin fa-xs"></i>
                            @endif
                            Select Your Vehicle</span></a>
                    @endif



                    <a href="{{ url('schedule-appointment?user_token=' . $user_token) }}">
                        <span style="background-color:#039BE5;width:100%;height: 40px;padding-top: 10px;" class="badge badge-pill badge-primary">
                        @if($next == "appointment")
                                &nbsp;<i style="color: white;" class="fas fa-asterisk fa-spin fa-xs"></i>
                            @endif
                            Schedule An Appointment</span></a>
                    <a href="{{ url('pre-approved?user_token=' . $user_token ) }}">
                        <span style="background-color:#039BE5;width:100%;height: 40px;padding-top: 10px;" class="badge badge-pill badge-primary">
                        @if($next == "preapproved")
                                &nbsp;<i style="color: white;" class="fas fa-asterisk fa-spin fa-xs"></i>
                            @endif
                            Get Pre-Approved</span></a>

                </div>
            </div>
            </div>
        </div>

    </div>



@endsection
    <style>
    .vjs-big-play-button{
        top:100px!important;
        position: relative!important;
        height: 75px!important;
        width: 75px!important;
    }
</style>

@section('page-js')
    <script src= "//amp.azure.net/libs/amp/2.3.9/azuremediaplayer.min.js"></script>
<script>
Tracking = {
    user_token: '{{$user_token}}',
    page: '{{$currentPage}}',
    s_id: '{{$s_id}}'
};

</script>

@endsection
