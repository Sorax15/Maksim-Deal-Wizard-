@extends('layout')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/summary.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('top-nav-item')
    <h3><i class="fas fa-cubes"></i>SUMMARY</h3>
@endsection

@section('footer-btns')
    <div class="back">
    <a class="btn btn-primary back-btn" href="{{ url('/pre-approved?accessToken=' . $accessToken ) }}">Back</a>
    </div>
    <div class="bot-buttons">
       <!-- <a class="btn btn-primary skip-btn" href="{{ url('/welcome?accessToken=' . $accessToken . '&d_id=' . $d_id . '&deal_id=' . $deal->id) }}">SKIP</a>
        <a class="btn btn-primary next-step-btn" onclick="submitSummaryInfo()">CONTINUE</a>-->
    </div>
@endsection

@section('footer')
    @include('includes.footer')
@endsection

@section('content')
    <div class="nav-main-container col-md-10">

        <div class = "area1">
            <div class="summary-title-header">
                <p>Thanks {{$deal->fname}}!</p>
            </div>
            @if(isset($salesperson) && !empty($salesperson))
                    <div style = "display: flex">
                        <img class = "personal_image" src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
                        <h3 style = "margin-top: 20px;">Your Progress Has Been Saved!</h3>
                    </div>

                <div onclick="showSalespersonModal()" style="padding-top:10px;width:fit-content;cursor:pointer;font-weight:800;font-size: 15px; color:#039BE5 ">
                    <i style="color: #039BE5;" class="fas fa-asterisk fa-question-circle"></i>
                    Ask a Question
                </div>



            @else
                    <h3>Your Progress Has Been Saved!</h3>
                    @endif

                    <h6 class = "message1" style="margin-bottom: 35px;">
                    I’m here to help! You can pickup where you left off anytime using the Text/Email button. If you have a question, just let text or call me.
                 </h6>
        </div>

        @if($contactCheck == true)
        <div class="area2">
            <p class="message1">
                We can inform you about all progress you've done.
            </p>
            <p  style ="display:inline;cursor:pointer"  onclick="sendSummary(1)"><i class="fas fa-envelope icon-action"></i> <span class="call-action">Send Via E-mail</span></p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <p  style="display:inline;cursor:pointer" onclick="sendSummary(2)"><i class="fas fa-comment-alt icon-action"></i> <span class="call-action">Send Via Text</span></p>
            <div id="success_block" style = "display:none;margin-top:20px" class="alert alert-success alert-dismissible fade show text-center" role="alert">
                <strong id = "success_block_msg"></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        @endif
        <div class="area3">
            <p class = "progress-text">Your Progress</p>
            <hr>
        </div>
        <div class="area4">

            <div>
                <p class="car-label">Vehicle</p>
                <p class="car-edit"><a class = "edit" href="{{ url('/vehicle-detail?user_token=' . $user_token . '&vehicle_id='. $deal->vehicle_id) }}">Edit</a></p>
            </div>
            @if($vehicle != "")
            <div  style = " width:100%;padding-top:0px;max-height:200px">
                <div class="area4_inner" style = "width:100%;float:left">
                    <img style="float:left;margin-right:5px"  class="car-image" src ="{{$photo}}" />

                    <div style="padding-left:10px;padding-top:5px" class ="car-data">
                        <p><b>{{$vehicle->year}} {{$vehicle->make}} {{$vehicle->modelName}} {{$vehicle->trim}}</b></p>
                        <p>${{$display_price}}</p>
                        <p>Mileage: {{$vehicle->odometer}}</p>
                    </div>
                </div>

            </div>
            @endif

        </div>
        <div class="area5">
            <div>
                <p class="car-label">Salesperson</p>
                <p class="car-edit"><a class="edit" href="{{ url('/salesperson-detail?user_token=' . $user_token . '&s_id='. $salesperson->s_id) }}">Edit</a></p>
            </div>
            @if($salesperson != "")
            <div  style = " padding-top:0px;max-height:200px;float:left;width:100%">
                <div class="area5_inner" style = "width:fit-content">
                        <img style = "margin-right:10px;border-radius: 5px;width:150px;float:left" src = "{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{$salesperson->photo}}" />                </div>
                    <div style="padding-left:10px;padding-top:10px" class ="car-data">
                        <span class = "sales-text"><b>{{$salesperson->first}} {{$salesperson->last}}</b></span><br>
                        <span style = "color: #BDBDBD;">Phone:</span> <span class = "sales-text">{{$sales_number_display}}</span><br>
                        <span style = "color: #BDBDBD;">Email:</span> <span style ="text-transform:lowercase" class = "sales-text">{{$salesperson->email}}</span>
                    </div>
            </div>
            @endif
        </div>
        <div class="area6">
             <div>
                <p class="car-label">Appointment</p>
                <p class="car-edit"><a class="edit" href="{{ url('/schedule-appointment?user_token=' . $user_token ) }}">Edit</a></p>
            </div>
            @if($appointmentCheck == true)
                <div class ="car-data">
                    <p><b>{{$date_display}}</b></p>
                    <p>Beverage: {{$deal->td_beverage}}</p>
                </div>
            @endif

        </div>
        <div class="area7">
            <div>
                <p class="car-label">Trade-in Value</p>
                <p class="car-edit"><a class = "edit" href="{{ url('/value-trade?user_token=' . $user_token ) }}">Edit</a></p>
            </div>
            @if($tradeCheck == true)
                <div class ="car-data">
                    <p><b>{{$deal->trade_year}} {{$deal->trade_make}} {{$deal->trade_model}} {{$deal->trade_trim}}</b></p>
                    <p>Value: {{$deal->trade_value ? '$'.number_format($deal->trade_value,2,'.',',') : 'Undetermined'}}</p>
                </div>
            @endif
        </div>





    </div>
@endsection

@section('page-js')
    <script>
        Tracking = {
            user_token: '{{$user_token}}',
            page: '{{$currentPage}}'
        };

        $(document).ready(function() {
            $('.edit').click(function(){

                Tracking['type'] = 'edit_item_summary';
                Tracking['env'] = 'app';
                Tracking['info'] = 'navigation edit item from summary';
                sendTracking();

            });
        });

        function sendSummary(type){
            $('#success_block_msg').html('');
            $('#success_block_msg').html('Sending Request........');
            $('#success_block').show();

             var data =  {

                user_token: '{{$user_token}}',
                message_type : type
            }

            $.ajax({
                data: data,
                type: "POST",
                url: "{{ url('send-summary') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).fail(function(jqXHR, status){
                var msg = 'Request Failed. Please Try Again.';
                $('#success_block_msg').html(msg);
            }).done(function() {
                if(type == 1){
                    Tracking['type'] = 'summary_email_sent';
                    Tracking['env'] = 'app';
                    Tracking['info'] = 'summary email sent';
                    sendTracking();
                }else{
                    Tracking['type'] = 'summary_text_sent';
                    Tracking['env'] = 'app';
                    Tracking['info'] = 'summary text sent';
                    sendTracking();
                }
               $('#success_block_msg').html('The Request Was Successfully Sent!');

            });
        }

        function submitSummaryInfo()
        {
            var data =  {
                trade_year: $('select[name="trade_year"]').val(),
                trade_make: $('select[name="trade_make"]').val(),
                trade_model: $('select[name="trade_model"]').val(),
                trade_trim: $('select[name="trade_trim"]').val(),
                trade_miles: $('input[name="trade_miles"]').val(),
                trade_payoff: $('input[name="trade_payoff"]').val(),
                //trade_condition: $('select[name="trade_condition"]').val(),
                trade_value: $('input[name="trade_value"]').val(),
                trade_vin: $('input[name="trade_vin"]').val(),
                user_token: '{{ $user_token }}'
            }

            $.ajax({
                data: data,
                type: "POST",
                url: "{{ url('value-trade') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function() {
                window.location.href = "{{ url('payments') }}?user_token={{ $user_token }}";
            });
        }



    </script>
@endsection
