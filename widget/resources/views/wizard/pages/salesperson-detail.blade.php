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
    @if($deal->entry != null  && $deal->entry == 'salesperson')
    <div class="bot-buttons">

        <a class="btn btn-primary next-step-btn" href="{{ url('/vehicle-select?user_token=' . $user_token ) }}">CONTINUE</a>
    </div>
    @else
    <div class="bot-buttons">
        <a class="btn btn-primary skip-btn" href="{{ url('/start-sales-person?user_token=' . $user_token . '&s_id=' . $s_id) }}">CHANGE EXPERT</a>
        <a class="btn btn-primary next-step-btn" href="{{ url('/selected-sales-person?user_token=' . $user_token . '&s_id=' . $s_id) }}">SELECT EXPERT</a>
    </div>
    @endif
@endsection

@section('footer')
    @include('includes.footer')
@endsection

@section('content')
    <div class="nav-main-container col-md-12">
        <div class="row sp-row" style = "padding:20px">
            <div class="col-xl-4 col-lg-12">
            <div class="salesperson-container">

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
                                <div class="col-md-6">
                                    <h6>Total Reviews</h6>
                                    <h4>{{ $salesperson->review_count }}</h4>
                                </div>
                                <div class="col-md-6">
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
                                if(  preg_match( '/^\+\d(\d{3})(\d{3})(\d{4})$/', $salesperson->IndividualPhoneNumber,  $matches ) )
                                {
                                    $nicePhone = '('. $matches[1] . ')' . ' ' .$matches[2] . '-' . $matches[3];
                                }
                                else
                                {
                                    $nicePhone = $salesperson->IndividualPhoneNumber;
                                }
                            @endphp
                            <a href="tel:{{ $salesperson->IndividualPhoneNumber }}"><img src="{{ asset('imgs/sp-phone-icon.png') }}"><span class="phone-number">{{ $nicePhone }}</span></a>
                        </div>
                        @if(isset($salesperson) && !empty($salesperson))
                            <div onclick="showSalespersonModal()" style="padding-top:10px;text-align:center;cursor:pointer;font-weight:800;font-size: 15px; color:#039BE5 ">
                                <i style="color: #039BE5;" class="fas fa-asterisk fa-question-circle"></i>
                                Ask a Question
                            </div>
                        @endif
                    </div>
                    </div>
                    <div class="col-xl-8 col-lg-12">
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

                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                </div>
            </div>

@endsection

@section('page-js')

    <script>
    Tracking = {
        user_token: '{{$user_token}}',
        page: '{{$currentPage}}',
        s_id: '{{$s_id}}'
    };

        $(document).ready(function() {
            //var html = $(".salesperson-container").html();
            //$("#mobile_block").html(html);
            $('.nav-link').click(function(){

                Tracking['type'] = 'salesperson_start_tab_view';
                Tracking['env'] = 'app';
                Tracking['info'] = $(this).prop('id');
                sendTracking();

            });



        });
    </script>
@endsection
