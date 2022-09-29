@extends('layout')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/sales-people.css?v=').time() }}">
@endsection

@section('top-nav-item')
    <h3><i class="fa fa-user"></i>CHOOSE AN EXPERT</h3>
@endsection

@section('footer-btns')
    <div class="back">
        <a class="btn btn-primary back-btn" href="{{ url('/welcome?user_token=' . $user_token) }}">Back</a>
    </div>
    <div class="bot-buttons">
        <a class="btn btn-primary next-step-btn salesperson_selected" href="{{ url('/selected-sales-person?user_token=' . $user_token . '&s_id=' . $s_id) }}">SELECT EXPERT</a>
    </div>
@endsection

@section('footer')
    @include('includes.footer')
@endsection




@section('content')

<template id="confirm-template">
    <swal-html>


        </div>
        <div class = "row">
            <div style = "padding: 20px;margin: 20px;max-width:96%" class = "col-md-12 text-center">
                <i style="color: #17c40c;font-size:30px;padding-bottom: 5px;" class="fas fa-check-circle"></i>
                <div style = "padding-bottom: 10px;">
                    Do you want to continue with
                </div>
                <div style = "font-size: 24px;font-weight: 800;padding-bottom: 15px;">

                    <div class="" style = "display:grid; grid-template-columns: 150px auto;justify-content:center">
                        <div class="">
                            <img width="140px" src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
                        </div>
                        <div style = "width:fit-content" class=" profile-info profile-padding">
                            <h5>{{ $salesperson->first }} {{ $salesperson->last }}</h5>
                            <h6>{{ $salesperson->title }}</h6>
                            <h6 class="review-count"><span class="review-number">{{ $salesperson->review_count }}</span> <span class="review-label">Reviews</span></h6>
                        </div>
                    </div>


                </div>
                <div>
                    <span class = "close_confirm" onclick="closeConfirm()" style = "cursor:pointer; color:#039be5;margin-right:40px;">Back</span>
                    <a style = "margin-left:40px;width: 175px;font-weight: 800;" class="btn btn-primary next-step-btn" onclick="selectExpert()">SELECT EXPERT</a>
                </div>
            </div>

        </div>

    </swal-html>
</template>

    <div class="nav-main-container col-md-12">

           <div id="mobile_block" class="">
                <div class="row" style="padding:20px">

                            <div class="sales-people-container">
                                @if($salesperson != "")
                                <a class = "salesperson_list" href="{{ url('salesperson-detail?user_token=' . $user_token . '&s_id=' . $salesperson->s_id) }}">

                                <div class="selected-sales-person">
                                    <div class="sales-person active">

                                        <div class="" style = "display:grid; grid-template-columns: 150px auto">
                                            <div class="">
                                                <img width="140px" src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
                                            </div>
                                            <div class=" profile-info profile-padding">
                                                <h5>{{ $salesperson->first }} {{ $salesperson->last }}</h5>
                                                <h6>{{ $salesperson->title }}</h6>
                                                <h6 class="review-count"><span class="review-number">{{ $salesperson->review_count }}</span> <span class="review-label">Reviews</span></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </a>
                                @endif
                                <div class="sales-people">
                                    @foreach($salespeople as $sp)
                                        @if($sp->s_id != $s_id)
                                            <a class="salesperson_list" href="{{ url('salesperson-detail?user_token=' . $user_token . '&s_id=' . $sp->s_id) }}">
                                                <div class="sales-person">
                                                    <div class="row" style = "display:grid; grid-template-columns: 150px auto">
                                                        <div class=" profile-img">
                                                            <img  width="140px" src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $sp->photo }}">
                                                        </div>
                                                        <div style="padding:10px" class=" profile-info profile-padding">
                                                            <h5>{{ $sp->first }} {{ $sp->last }}</h5>
                                                            <h6>{{ $sp->title }}</h6>
                                                            <h6 class="review-count"><span class="review-number">{{ $sp->review_count }}</span> <span class="review-label">Reviews</span></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>



            </div>
        </div>






            <div id="desktop_block" class="row" style="padding:20px;display:grid;grid-template-columns: 25% 30% 1fr;grid-gap:20px">
                <div class ="sec1">

                    <div class="sales-people-container">
                        @if($salesperson != "")
                        <div class="selected-sales-person">
                            <div class="sales-person active">
                                @if($salesperson != "")
                                <div class="row">
                                    <div class="col-md-4 profile-img">
                                        <img src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
                                    </div>
                                    <div class="col-md-8 profile-info profile-padding">
                                        <h5>{{ $salesperson->first }} {{ $salesperson->last }}</h5>
                                        <h6>{{ $salesperson->title }}</h6>
                                        <h6 class="review-count"><span class="review-number">{{ $salesperson->review_count }}</span> <span class="review-label">Reviews</span></h6>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        <div class="sales-people">
                            @foreach($salespeople as $sp)
                                @if($sp->s_id != $s_id)
                                    <a class="salesperson_list" href="{{ url('start-sales-person?user_token=' . $user_token . '&s_id=' . $sp->s_id) }}">
                                        <div class="sales-person">
                                            <div class="row">
                                                <div class="col-md-4 profile-img">
                                                    <img src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $sp->photo }}">
                                                </div>
                                                <div class="col-md-8 profile-info profile-padding">
                                                    <h5>{{ $sp->first }} {{ $sp->last }}</h5>
                                                    <h6>{{ $sp->title }}</h6>
                                                    <h6 class="review-count"><span class="review-number">{{ $sp->review_count }}</span> <span class="review-label">Reviews</span></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>

                </div>





             <!--  Div is populated on dom load of .salesperson-container using clone jquery
                    The mobile version is full width
            -->









             <div  class="sec2">

                    <div class="" >
                        @php
                            $image1 = $api_endpoints['image'].'/uploads/media/profile_images/cropped/'.$salesperson->photo;
                        @endphp
                        <div class="sp-img" style="background-image: url({{$image1}}); background-size: cover; background-position: center;">
                            <div class="profile-img-overlay"></div>
                            <h4 class="sp-name">{{ $salesperson->first }} {{ $salesperson->last }}</h4>
                            <h6 class="sp-title">{{ $salesperson->title }}</h6>
                        </div>
                        <div class="sp-review-counts">
                            <div class="row">
                                <div style = "text-align:center" class="col-md-6">
                                    <h6>Total Reviews</h6>
                                    <h4>{{ $salesperson->review_count }}</h4>
                                </div>
                                <div style="text-align:center" class="col-md-6">
                                    <h6>Last 30 Days</h6>
                                    <h4>{{ $salesperson->review30_count }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="need-help">
                            <h4>Need Some Help?</h4>
                            <p>Let me help you with finding your perfect vehicle, scheduling a test drive, or answering your questions</p>
                        </div>
                        <div class="sp-phone">
                            @php

                                if(  preg_match( '/^\+\d(\d{3})(\d{3})(\d{4})$/', $salesperson->phone,  $matches ) )
                                {
                                    $nicePhone = '('. $matches[1] . ')' . ' ' .$matches[2] . '-' . $matches[3];
                                }
                                else
                                {
                                    $nicePhone = $salesperson->phone;
                                }
                            @endphp
                            <a href="tel:{{ $salesperson->phone }}"><img src="{{ asset('imgs/sp-phone-icon.png') }}"><span class="phone-number">{{ $nicePhone }}</span></a>
                        </div>

                        @if(isset($salesperson) && !empty($salesperson))
                            <div onclick="showSalespersonModal()" style="padding-top:10px;width:fit-content;cursor:pointer;font-weight:800;font-size: 15px; color:#039BE5 ">
                                <i style="color: #039BE5;" class="fas fa-asterisk fa-question-circle"></i>
                                Ask a Question
                            </div>
                        @endif

                    </div>
                 </div>

                    <div class="sec3">
                        <ul class="nav nav-tabs" id="salesPersonTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="bio-tab" data-toggle="tab" href="#bio" role="tab" aria-controls="bio" aria-selected="true">Bio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Reviews</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="gallery-tab" data-toggle="tab" href="#gallery" role="tab" aria-controls="gallery" aria-selected="false">Gallery</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="salesPersonContent">
                            <div class="tab-pane fade show active" id="bio" role="tabpanel" aria-labelledby="bio-tab">

                                @if(!empty($salesperson->youtube))
                                <div class="sp-video">
                                    <div class="video-container">
                                        <iframe src="{{ $salesperson->youtube }}"></iframe>
                                    </div>
                                </div>
                                @endif
                                <p class="bio-info">{{ $salesperson->info }}</p>
                            </div>
                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <div class="reviews">
                                    @foreach($sp_reviews as $review)
                                        <div class="review">
                                            <div class="row">
                                                <div class="col-md-3 review-photo">
                                                    @if($review->customer_photo != '')
                                                        <img src="{{$api_endpoints['image']}}/uploads/media/customer_photos/{{ $review->customer_photo }}" />
                                                    @endif
                                                </div>
                                                <div class="col-md-9 review-info">
                                                    <span class="review-date" style = "padding-bottom:10px">
                                                    {{!empty($review->name) ? $review->name." wrote:" : (!empty($review->customer_name) ? $review->customer_name.' wrote:' : ''   )}}
                                                    </span>
                                                    <div class="review-title">
                                                        <h6>{{ $review->comment }}</h6>
                                                    </div>
                                                    <div class="review-subtitle">
                                                        <h6><span class="review-date">{{ \Carbon\Carbon::parse($review->created_date)->format('d M Y') }}</span>
                                                            @if(!empty($review->year) && !empty($review->make) && !empty($review->model) )
                                                                <span class="review-vehicle">{{ $review->year . ' ' . $review->make . ' ' . $review->model}}</span>
                                                            @endif
                                                        </h6>
                                                    </div>
                                                    <div class="review-rating">
                                                        @if($review->review_stars == '5')
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                        @elseif($review->review_stars == '4')
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                            <img src="{{ asset('imgs/star-empty.png') }}"/>
                                                        @elseif($review->review_stars == '3')
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                            <img src="{{ asset('imgs/star-empty.png') }}"/>
                                                            <img src="{{ asset('imgs/star-empty.png') }}"/>
                                                        @elseif($review->review_stars == '2')
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                            <img src="{{ asset('imgs/star-empty.png') }}"/>
                                                            <img src="{{ asset('imgs/star-empty.png') }}"/>
                                                            <img src="{{ asset('imgs/star-empty.png') }}"/>
                                                        @elseif($review->review_stars == '1')
                                                            <img src="{{ asset('imgs/star-full.png') }}"/>
                                                            <img src="{{ asset('imgs/star-empty.png') }}"/>
                                                            <img src="{{ asset('imgs/star-empty.png') }}"/>
                                                            <img src="{{ asset('imgs/star-empty.png') }}"/>
                                                            <img src="{{ asset('imgs/star-empty.png') }}"/>
                                                        @endif
                                                    </div>
                                                    <div class="review-testimonial">
                                                        <p>{{ $review->testimonial }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
                                <div class="d-flex flex-row flex-wrap">
                                    @foreach($sp_photos as $photo)
                                        <div class="col-md-3 photo">
                                            <img src="{{$api_endpoints['image']}}/uploads/media/sales_slides_images/{{ $photo->filename }}" />
                                        </div>
                                    @endforeach
                                        <div class="col-md-3 photo">
                                        </div>
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
    Tracking = {
        user_token: '{{$user_token}}',
        page: '{{$currentPage}}',
        s_id: '{{$s_id}}'
    };

    var ConfirmModal = null;
    var currentSid = '{{$deal->s_id}}';

    var salesperson = "{{$salesperson_available}}";
        $(document).ready(function() {
            //var html = $(".salesperson-container").html();
            //$("#mobile_block").html(html);
            $('.nav-link').click(function(){

                Tracking['type'] = 'salesperson_start_tab_view';
                Tracking['env'] = 'app';
                Tracking['info'] = $(this).prop('id');
                sendTracking();

            });

            $('.salesperson_list').click(function(){

                Tracking['type'] = 'salesperson_change';
                Tracking['env'] = 'app';
                Tracking['info'] = 'changed salesperson';
                sendTracking();

            });

            $('.salesperson_selected').click(function(){

            Tracking['type'] = 'salesperson_selected';
            Tracking['env'] = 'app';
            Tracking['info'] = 'salesperson selected';
            sendTracking();

            });

            if(currentSid != '{{request()->get("s_id")}}'){

                $('.side-nav-content  a').click(function(event){
                    event.preventDefault();
                    Tracking['type'] = 'salesperson_dialog';
                    Tracking['env'] = 'app';
                    Tracking['info'] = 'salesperson outside click';
                    sendTracking();
                    ConfirmModal = Swal.fire({
                    template: '#confirm-template',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    width: '80%'
                    })

                });
            }






        });

        function closeConfirm(){
            Tracking['type'] = 'salesperson_dialog';
            Tracking['env'] = 'app';
            Tracking['info'] = 'salesperson selected back';
            sendTracking();
            ConfirmModal.close();
            ConfirmModal = null;
        }

        function selectExpert(){
            Tracking['type'] = 'salesperson_dialog';
            Tracking['env'] = 'app';
            Tracking['info'] = 'salesperson selected';
            sendTracking();
            window.location = "selected-sales-person?user_token={{$user_token}}&s_id={{$s_id}}";

        }
    </script>
@endsection
