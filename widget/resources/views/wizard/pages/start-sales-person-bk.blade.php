@extends('layout')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/sales-people.css') }}">
@endsection

@section('top-nav-item')
    <h3><i class="fa fa-user"></i>CHOOSE AN EXPERT</h3>
@endsection

@section('footer-btns')
    <div class="back">
        <a class="btn btn-primary back-btn" href="{{ url()->previous() }}">Back</a>
    </div>
    <div class="bot-buttons">
        <a class="btn btn-primary skip-btn" href="{{ url('/skip-sales-person?accessToken=' . $accessToken . '&d_id=' . $d_id) }}">SKIP</a>
        <a class="btn btn-primary next-step-btn" href="{{ url('/selected-sales-person?accessToken=' . $accessToken . '&d_id=' . $d_id . '&s_id=' . $salesperson->s_id) }}">SELECT EXPERT</a>
    </div>
@endsection

@section('footer')
    @include('includes.footer')
@endsection

@section('content')
    <div class="main-container">
        <div class="main-row">
            <div class="sales-people-container">
                <div class="selected-sales-person">
                    <div class="sales-person active">
                        <div class="row">
                            <div class="col-md-3 profile-img">
                                <img src="https://beta-toolkit-api.vassdp.net/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
                            </div>
                            <div class="col-md-9 profile-info">
                                <h5>{{ $salesperson->first }} {{ $salesperson->last }}</h5>
                                <h6>{{ $salesperson->title }}</h6>
                                <h6 class="review-count"><span class="review-number">{{ $salesperson->review_count }}</span> <span class="review-label">Reviews</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sales-people">
                    @foreach($salespeople as $sp)
                        @if($sp->s_id != $salesperson->s_id)
                            <a href="{{ url('start-sales-person?accessToken=' . $accessToken . '&d_id=' . $d_id . '&s_id=' . $sp->s_id) }}">
                                <div class="sales-person">
                                    <div class="row">
                                        <div class="col-md-3 profile-img">
                                            <img src="https://beta-toolkit-api.vassdp.net/uploads/media/profile_images/cropped/{{ $sp->photo }}">
                                        </div>
                                        <div class="col-md-9 profile-info">
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
            <div class="salesperson-container">
                <div class="row sp-row">
                    <div class="col-md-4">
                        <div class="sp-img" style="background-image: url('https://beta-toolkit-api.vassdp.net/uploads/media/profile_images/cropped/{{ $salesperson->photo }}'); background-size: cover; background-position: center;">
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
                    </div>
                    <div class="col-md-8">
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
                                <p class="bio-info">{{ $salesperson->info }}</p>
                                <div class="sp-video">
                                    <div class="video-container">
                                        <iframe src="{{ $salesperson->youtube }}"></iframe>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <div class="reviews">
                                    @foreach($sp_reviews as $review)
                                        <div class="review">
                                            <div class="row">
                                                <div class="col-md-3 review-photo">
                                                    @if($review->review_photo != '')
                                                        <img src="https://beta-toolkit-api.vassdp.net/uploads/media/customer_photos/{{ $review->review_photo }}" />
                                                    @endif
                                                </div>
                                                <div class="col-md-9 review-info">
                                                    <div class="review-title">
                                                        <h6>{{ $review->customer_name }}'s experience purchasing from {{ $salesperson->first }} {{ $salesperson->last }}</h6>
                                                    </div>
                                                    <div class="review-subtitle">
                                                        <h6><span class="review-date">{{ \Carbon\Carbon::parse($review->created_date)->format('d M Y') }}</span><span class="review-vehicle">{{ $review->year . ' ' . $review->make . ' ' . $review->model}}</span></h6>
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
                                            <img src="https://beta-toolkit-api.vassdp.net/uploads/media/sales_slides_images/{{ $photo->filename }}" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
