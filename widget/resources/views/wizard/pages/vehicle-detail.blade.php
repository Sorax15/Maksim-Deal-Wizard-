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


        /* pictures block */
        .pictures_block{
            display: grid;
            grid-template-areas: 'pic1 pic1 pic2 pic4 pic4'
                             'pic1 pic1 pic3 pic5 pic6';
            gap: 5px;
            margin-bottom: 10px;

        }
        .picture1{
            grid-area: pic1;
            height: 405px;
        }
        .picture1 > img{
            width: 100%;
            height: 405px;
            object-fit: cover;
        }


        .picture2{
            grid-area: pic2;
            height: 200px;
        }

        .picture2 > img{
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .picture3{
            grid-area: pic3;
            height: 200px;
        }

        .picture3 > img{
            width: 100%;
            height: 200px;
            object-fit: cover;
        }



        .picture4{
            grid-area: pic4;
            height: 200px;
        }

        .picture4 > img{
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .picture5{
            grid-area: pic5;
            height: 200px
        }

        .picture5 > img{
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .picture6{
            grid-area: pic6;
            height: 200px;
        }

        .picture6 > img{
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .picture_view_button{
            text-align: center;
            margin: 20px 20px 20px 0px;
            max-width: 200px;
            background-color: #bcc5cb;
            font-size: 20px;
            font-weight: 800;
            box-shadow: 0px 6px 10px 0px #7090b078;
            line-height: 37px;
            cursor: pointer;
        }

        @media only screen and (min-width: 1500px) and (max-width: 1600px) {
            .pictures_block{
                grid-template-areas:    'pic1 pic1 pic2 . .'
                                    'pic1 pic1 pic3 . .';
            }
            .picture4, .picture4, .picture5, .picture6{
                display:none;
            }
        }

        @media only screen and (min-width: 981px) and (max-width: 1340px) {
            .pictures_block{
                grid-template-areas:    'pic1 pic1 pic2 . .'
                                    'pic1 pic1 pic3 . .';
            }

            .picture4, .picture4, .picture5, .picture6{
                display:none;
            }
        }

        @media only screen and (min-width: 801px) and (max-width: 980px) {
            .pictures_block{
                grid-template-areas:    'pic1 pic1 pic2 . .'
                                    'pic1 pic1 pic3 . .';
            }

            .picture4, .picture4, .picture5, .picture6{
                display:none;
            }
        }

        @media only screen and (min-width: 100px) and (max-width: 800px) {
            .pictures_block{
                grid-template-areas:    'pic1 . . . . .'
                                    'pic1 . . . . .';
            }

            .picture1{
                grid-area: pic1;
                width: 100%!important;
                height: auto!important;
            }
            .picture1 > img{
                width: 100%!important;
                height: auto!important;
                object-fit: cover;
            }

            .picture2, .picture3, .picture4, .picture5, .picture6{
                display:none;
            }
        }



        /* photo viewer */


        /* Slideshow container */


        /* Hide the images by default */
        .vehicleImage {
            display: none;
            padding:60px;
        }
        .vehicleImage img {
            border-radius: 15px;
            width: 100%;
        }

        /* Next & previous buttons */
        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 48%;
            width: auto;
            margin-top: -22px;
            padding: 16px;
            color: black!important;
            font-size: 60px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;

        }

        /* Position the "next button" to the right */
        .next {
            right: 15px;
            border-radius: 3px 0 0 3px;

        }

        .prev{
            left: 15px;
        }

        /* Fading animation */
        .fade {
            -webkit-animation-name: fade;
            -webkit-animation-duration: 1.5s;
            animation-name: fade;
            animation-duration: 1.5s;
        }

        .swal2-container.swal2-backdrop-show, .swal2-container.swal2-noanimation{
            background-color: #000000e6;
        }


        /* section 2*/
        .section2{
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            z-index: 56;
            box-shadow: 5px 2px 15px 3px rgb(112 144 176 / 64%);
            padding: 10px;
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            background-color: white;

        }

        @media only screen and  (max-width: 1000px) {
            .section2{
                grid-template-columns: 1fr;
            }
        }

        .section2_class_new{
            padding: 2px 0px 0px 0px;
            background: #37B34A;
            border-radius: 10px;
            color: #fff;
            width: 50px;
            height: 21px;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            margin-right: 5px;
        }

        .section2_class_used{
            padding: 2px 0px 0px 0px;
            background: #FF8F00;
            border-radius: 10px;
            color: #fff;
            width: 50px;
            height: 21px;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            margin-right: 5px;
        }

        .stock_label{
            color: #21212180;
            font-size: 12px;
            font-weight: 400;
            margin-right: 3px;
            margin-top: 2px;
        }

        .stock_number{
            color: #212121;
            font-size: 12px;
            font-weight: 500;
            margin-right: 12px;
            margin-top: 2px;
        }

        .car_label{
            font-weight: 700;
            font-size: 28px;
            line-height: 35px;
            color: #212121;
            width: fit-content;
            margin-top: 5px;
        }

        .payment_box{
            background: #F0F3F5;
            box-shadow: 0px 6px 10px rgba(112, 144, 176, 0.1);
            border-radius: 4px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            padding: 5px;
        }

        .payment_box_inner{
            border-right: 1px solid #C4C4C4;
            padding: 5px;
        }

        .payment_box_inner_label{
            font-weight: 600;
            font-size: 12px;
            line-height: 15px;
            margin-bottom: 5px;
            color: #212121;
        }

        .payment_box_inner_price{
            font-weight: 700;
            font-size: 20px;
            line-height: 25px;

            color: #2A98E3;
        }


        /* calculation section */
        .section_3{
            width: fit-content;
            box-shadow: 0px 6px 10px rgba(112, 144, 176, 0.1);
            margin-top: 5px;
        }
        .cal_block{
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            padding: 10px;
            max-width: 945px;
            margin-right: 20px;
        }

        .cal_block_label{
            font-weight: 700;
            font-size: 20px;
            line-height: 25px;
            margin-top: 40px;
            margin-bottom: 20px;
            margin-left: 20px;
            color: #000000;
        }

        .cal_block_mobile{
            display: flex;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .cal_block_mobile_label{
            font-weight: 700;
            font-size: 14px;
            align-items: center;
            width: 33%;
            text-align: center;
            color: rgba(0, 0, 0, 0.54);
            border-bottom: 2px solid #D7D7D7;
            cursor: pointer;
        }

        .cal_mobile_selected{
            color : #039BE5;
            border-bottom: 2px solid #039BE5;
        }

        .cal_block_mobile_price{
            font-weight: 700;
            font-size: 24px;
            line-height: 30px;
            color: #212121;
            text-align: center;
        }

        .cal_block_mobile_msg{
            font-weight: 500;
            font-size: 12px;
            line-height: 16px;
            color: #000000;
            opacity: 0.5;
            text-align: center;
        }



        @media only screen and (min-width: 1500px) and (max-width: 1600px), only screen and (min-width: 100px) and (max-width: 1340px)  {
            .block1{
                display: block!important;
            }
        }

        .cal_term{
            width: 300px;
            height: 100px;
            background: #FFFFFF;
            box-shadow: 0px 6px 10px rgba(112, 144, 176, 0.1);
            border-radius: 4px;
            cursor: pointer;
        }

        .cal_term_label{
            position: relative;
            width: 300px;
            height: 28px;
            left: 0px;
            top: -1px;
            text-align: center;
            background: #F0F3F5;
            border-radius: 4px 4px 0px 0px;
            font-weight: 700;
            font-size: 14px;
            line-height: 18px;
            text-transform: uppercase;
            padding: 4px;
            color: rgba(33, 33, 33, 0.5);
        }

        .cal_term_value{
            font-weight: 700;
            font-size: 24px;
            line-height: 30px;
            color: #212121;
            text-align: center;
            vertical-align: center;
            position: relative;
            top: 20px;

        }

        .cal_term_label_selected{
            background: #FF8F00;
            color: white;
            width: 299px;
        }

        .cal_term_selected{
            border: 1px solid #FF8F00;
        }

        .credit_block, .mileage_block{
            width: 450px;
            margin-top: 5px;
            padding: 10px;
        }

        .term_block, .lease_term_block{
            width: 450px;
            margin-top: 5px;
            padding: 10px;
        }

        .items-block{
            display: grid;
            grid-template-columns: 450px;
            gap: 0px;
        }

        .items-wrapper{
            display: grid;
            grid-template-columns: 60% 40%;
            gap: 0px;
        }

        .item_label{
            font-weight: 500;
            font-size: 14px;
            line-height: 18px;
            color: #212121;
        }

        .credit_block_items{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min-content, 80px));
            gap: 20px;
            padding: 10px;
            max-width: 450px;
            cursor: pointer;
        }

        .mileage_block_items{
            display: grid;
            grid-template-columns: 65px 65px 65px;
            gap: 5px;
            padding: 10px;
            max-width: 450px;
            cursor: pointer;
        }

        .term_block_items, .lease_term_block_items{
            display: grid;
            grid-template-columns: repeat(auto-fit, 60px);
            padding: 10px;
            max-width: 450px;
            cursor: pointer;
            grid-gap: 10px;
        }

        .credit_item{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 8px 24px;
            width: 80px;
            height: 51px;
            background: #F0F3F5;
            border-radius: 6px;
        }

        .mileage_item{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 7px 13px;
            width: 60px;
            height: 37px;
            background: #F0F3F5;
            border-radius: 6px;
        }

        .term_item{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0px 17px;
            width: 60px;
            height: 31px;
            background: #F0F3F5;
            border-radius: 6px;

        }

        .item_selected{
            background: #6396BA;
            color: white;
        }

        .block1{
            display: flex;
        }

        /* payment block */
        .cal_payment_block{
            width: 330px;
            max-width: 330px;
            min-width: 330px;
            padding:20px;
            background: #FFFFFF;
            box-shadow: 0px 6px 10px rgba(112, 144, 176, 0.1);
            border-radius: 4px;
            margin-top: 20px;
        }

        .payment_header{
            font-weight: 700;
            font-size: 20px;
            line-height: 25px;
            margin-bottom: 20px;
            color: #000000;
        }

        .payment_line_item{
            display: flow-root;
            margin-bottom: 5px;
        }

        .payment_line_label{
            font-weight: 600;
            font-size: 14px;
            line-height: 18px;
            color: #393939;
            float: left;
            display: flex;
        }

        .payment_line_value{
            float: right;
            font-weight: 700;
            font-size: 16px;
            color: #393939;
        }

        @media only screen and (min-width: 100px) and (max-width: 1000px)  {
            .cal_block{
                display: none;
            }
            .cal_block_label{
                text-align: center;
            }

            .cal_block_mobile_section{
                display: block!important;
            }
            .block1{
                text-align: -webkit-center;
            }
            .items-block {
                grid-template-columns: 450px;
            }
            .items-wrapper{
                grid-template-columns: 100%;
            }

            .credit_block_items, .mileage_block_items, .term_block_items, .lease_term_block_items{
                justify-content: center;
            }


        }

        .about_block{
            background: #FFFFFF;
            box-shadow: 0px 6px 10px rgba(112, 144, 176, 0.1);
            border-radius: 4px;
            margin-top: 10px;
            padding: 20px;
        }

        .about_label{
            font-weight: 700;
            font-size: 14px;
            align-items: center;
            width: 25%;
            text-align: center;
            color: rgba(0, 0, 0, 0.54);
            border-bottom: 2px solid #D7D7D7;
            cursor: pointer;
        }

        .about_selected{
            color: #039BE5;
            border-bottom: 2px solid #039BE5;
        }

        .downpayment-block{
            padding-top: 40px;
            padding-bottom: 20px;

        }

        .downpayment-verbiage{
            font-family: 'Gilroy';
            font-style: normal;
            font-weight: 500;
            font-size: 12px;
            line-height: 18px;

            display: flex;
            align-items: center;
            margin-top: 10px;
            width: 300px;
            color: #83859A;
        }

        .verbiage1{
            font-weight: 500;
            font-size: 10px;
            line-height: 12px;
            text-align: center;
            color: #000000;
            opacity: 0.5;
            margin-top: 10px;
        }

        .verbiage2{
            font-weight: 500;
            font-size: 10px;
            line-height: 12px;
            text-align: center;
            color: #000000;
            opacity: 0.5;
        }











        /* The Modal (background) */
        .display-modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content/Box */
        .display-modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            max-width: 385px;
        }

        /* The Close Button */
        .display-close {
            color: #aaa;
            float: right;
            font-size: 35px;
            font-weight: bold;
            right: -15px;
            position: relative;
            top: -35px;
        }

        .display-close:hover,
        .display-close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .display-modal-content-header{
            font-weight: 700;
            font-size: 25px;
            line-height: 30px;
            color: #039BE5;
            margin-bottom: 15px;
        }
        .display-modal-label{
            font-weight: 800;
            font-size: 12px;
        }
        .select2-container--default .select2-selection--single{
            height: 40px!important;
        }

        .display-modal-item-main{
            padding-bottom: 10px;
        }

        .display-modal-item{
            padding-bottom: 10px;
            display: none;
        }

    </style>

    <link rel="stylesheet" href="{{ asset('js/jqueryui/jquery-ui.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('top-nav-item')
    <h3><i class="fa fa-user"></i>Vehicle Detail</h3>
@endsection

@section('footer-btns')
    <div class="back">
        <a class="btn btn-primary skip-btn vehicle_changed" href="{{ url('/vehicle-select?user_token=' . $user_token) }}">CHANGE VEHICLE</a>
    </div>
    <div class="bot-buttons">
        <a class="btn btn-primary next-step-btn" onclick="saveVehicle()">SAVE</a>
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

        if(!empty($offerlogix_settings)){
            if($offerlogix_settings['type'] == 'unset'){
                $selected = $offerlogix_settings['selected'];
                $credit_selected = $offerlogix_settings['credit_selected'];
                $downpayment = $offerlogix_settings['downpayment'];
                $fterm = $offerlogix_settings['finance_term'];
                $payoff = 0;
                $tradein = 0;
                $tradein_options = "false";
            }else{
                $selected = $offerlogix_settings['selected'];
                $credit_selected = $offerlogix_settings['credit_selected'];
                $downpayment = $offerlogix_settings['downpayment'];
                if($selected == 'finance'){
                    $fterm = $offerlogix_settings['finance_term'];
                }else{
                    $fterm = $offerlogix_settings['lease_term'];
                    $lease_miles = $offerlogix_settings['lease_miles'];
                }

                $tradein_options = $offerlogix_settings['tradein_options'];
                if($tradein_options == false){
                     $payoff = 0;
                    $tradein = 0;
                }else{
                     $payoff = (is_numeric($tradein_options['payoff'])) ? $tradein_options['payoff']:0;
                    $tradein = (is_numeric($tradein_options['tradein'])) ? $tradein_options['tradein']:0;
                }


                $tradein_options_json = json_encode($tradein_options);
            }


            $financing = '';
            $leasing = '';
            $cash = '';

            if(empty($offerlogix_settings['selected']) || $offerlogix_settings['selected'] == 'lease' ){
            $leasing = 'active';
            }else if($offerlogix_settings['selected'] == 'finance'){
            $financing = 'active';
            }else{
            $cash = 'active';
            }

            $rebates_financing=0;
            $rebates_leasing=0;
            $rebates_cash =0;
            if(!empty($offerlogix['rebates'])){
                foreach($offerlogix['rebates'] as $i => $rebate_data){
                    if($rebate_data['type'] == 'financing'){
                        $rebates_financing += (int) $rebate_data['cash'];
                    }

                    if($rebate_data['type'] == 'leasing'){
                        $rebates_leasing += (int) $rebate_data['leasing'];
                    }

                    if($rebate_data['type'] == 'cash'){
                        $rebates_cash += (int) $rebate_data['cash'];
                    }
                }
            }

            $show_leasing = "false";
            $show_financing = "false";

            if(!empty($offerlogix['leasing'])){
                $show_leasing = "true";
            }

            if(!empty($offerlogix['financing'])){
                $show_financing = "true";
            }
        }else{
            $offerlogix_settings = false;
            $offerlogix = false;
             $show_leasing = "false";
            $show_financing = "false";
            $offerlogix_settings_json ="{}";
            $rebates_financing = 0;
            $rebates_leasing = 0;
            $rebates_cash = 0;
            $zip = '';
            $downpayment = 0;
            $payoff = 0;
            $tradein = 0;
        }
    @endphp



    <template id="picture-viewer">
        <swal-html>


            <div class = "row">
                <div style = "max-width:100%" class = "col-md-12 text-center">
                    <div class="vehicle-imgs">
                        @foreach($vehicle->photos as $image)
                            <div class="vehicleImage">
                                <img src="{{ $image->original }}">
                            </div>
                    @endforeach
                    <!-- Next and previous buttons -->
                        <a class="prev" onclick="plusSlides('back')">&#10094;</a>
                        <a class="next" onclick="plusSlides('forward')">&#10095;</a>
                    </div>
                </div>

            </div>

        </swal-html>
    </template>



    <div class="nav-main-container col-md-10">
        <div style = "" class = "section_container">

            <div class = "section1">
            <!--     @if(isset($salesperson) && !empty($salesperson))
                <div style = "display: flex">
                    <img class = "personal_image" src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
                    <h3 style = "margin-top: 20px;">Vehicle Details</h3>
                </div>
                <div onclick="showSalespersonModal()" style="padding-top:10px;width:fit-content;cursor:pointer;font-weight:800;font-size: 15px; color:#039BE5 ">
                    <i style="color: #039BE5;" class="fas fa-asterisk fa-question-circle"></i>
                    Ask a Question
                </div>



            @else
                <h3>Vehicle Details</h3>
            @endif
                    -->
                <h3 style = "margin-top: 10px;">Vehicle Details</h3>
                <h6 style="margin-bottom: 15px;font-size: 12px">
                    Confirm this Vehicle Choice to Continue
                </h6>

            </div>

            <?php
            $pic1 = (isset($vehicle->photos[0]->original)) ? "<img class =\"pic_image1\" src =\"".$vehicle->photos[0]->original."\">" : '&nbsp;';
            $pic2 = (isset($vehicle->photos[1]->original)) ? "<img class =\"pic_image2\" src =\"".$vehicle->photos[1]->original."\">" : '&nbsp;';
            $pic3 = (isset($vehicle->photos[2]->original)) ? "<img class =\"pic_image3\" src =\"".$vehicle->photos[2]->original."\">" : '&nbsp;';
            $pic4 = (isset($vehicle->photos[3]->original)) ? "<img class =\"pic_image4\" src =\"".$vehicle->photos[3]->original."\">" : '&nbsp;';
            $pic5 = (isset($vehicle->photos[4]->original)) ? "<img class =\"pic_image5\" src =\"".$vehicle->photos[4]->original."\">" : '&nbsp;';
            $pic6 = (isset($vehicle->photos[5]->original)) ? "<img class =\"pic_image6\" src =\"".$vehicle->photos[5]->original."\">" : '&nbsp;';

            ?>
            <div class = "pictures_block">
                <div class = "picture1"><?php echo $pic1 ?></div>
                <div class = "picture2"><?php echo $pic2 ?></div>
                <div class = "picture3"><?php echo $pic3 ?></div>
                <div class = "picture4"><?php echo $pic4 ?></div>
                <div class = "picture5"><?php echo $pic5 ?></div>
                <div class = "picture6"><?php echo $pic6 ?></div>
            </div>
            @if(count($vehicle->photos) > 1)
                <div onclick="showViewer()" class = "picture_view_button">View All Photos</div>
            @endif

            <div class = "section2">
                <div>
                    <div style = "display: -webkit-inline-box">
                        @if($vehicle->saleClass == 'new')
                            <div class = "section2_class_new">
                                New
                            </div>
                        @else
                            <div class = "section2_class_used">
                                Used
                            </div>
                        @endif

                        <div class = "stock_label">
                            Stock#
                        </div>
                        <div class = "stock_number">
                            {{$vehicle->stockNumber}}
                        </div>
                        <div class = "stock_label">
                            VIN
                        </div>
                        <div class = "stock_number">
                            {{$vehicle->vin}}
                        </div>
                    </div>
                    <div class = "car_label">
                        {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->modelName}}
                    </div>
                </div>
                <div class ="payment_box">
                    @if(!empty($offerlogix['financing']))
                        <div class = "payment_box_inner">
                            <div class = "payment_box_inner_label">Finance</div>
                            <div data-type="finance" class = "payment_box_inner_price">${{number_format($offerlogix['financing']['monthlyPayment'],0,"","")}}</div>

                        </div>
                    @endif
                        <div class = "payment_box_inner">
                            <div class = "payment_box_inner_label">Lease</div>
                            <div data-type="lease"  class = "payment_box_inner_price">{{(empty($offerlogix['leasing']))?'N/A':number_format($offerlogix['leasing']['monthlyPayment'],0,"","")}} </div>
                        </div>


                    <div style="padding: 5px">
                        <div class = "payment_box_inner_label">Cash</div>
                        <div data-type="cash"  class = "payment_box_inner_price">{{$vehicle->finalPrice}}</div>
                    </div>
                </div>
            </div>
            <div style = "margin-top:15px;border-bottom: 1px solid rgba(33, 33, 33, 0.3);">

            </div>







            @if(!empty($offerlogix['financing']))
                <div class="cal_block_label">Select your perfect payment</div>
                <div class = "cal_block_mobile_section" style="display: none;">
                    <div class = "cal_block_mobile" >
                        <div data-view="mobile" data-type="cash" class = "cal_block_mobile_label {{(empty($offerlogix['financing']))?'cal_mobile_selected':''}} ">Cash</div>
                        @if(!empty($offerlogix['financing']))
                            <div data-view="mobile" data-type="finance" class = "cal_block_mobile_label cal_mobile_selected">Finance</div>
                        @endif
                            <div data-view="mobile" data-type="lease" class = "cal_block_mobile_label ">Lease</div>
                    </div>
                    <div class = "cal_block_mobile_price">
                        @if(!empty($offerlogix['financing']))
                            ${{$offerlogix['financing']['monthlyPayment']}}
                        @else
                            {{$vehicle->finalPrice}}
                        @endif
                    </div>
                    <div class="cal_block_mobile_msg">

                    </div>
                </div>
            @endif
            @if(!empty($offerlogix['financing']))
                <div class = "block1">
                    <div class = "section_3">



                        <div class = "cal_block">
                            <div data-view="desktop" data-type="cash" class = "cal_term {{(empty($offerlogix['financing']))?'cal_term_selected':''}} ">
                                <div class = "cal_term_label {{(empty($offerlogix['financing']))?'cal_term_label_selected':''}} ">Cash</div>
                                <div class="cal_term_value">{{$vehicle->finalPrice}}</div>
                            </div>
                            @if(!empty($offerlogix['financing']))
                                <div data-view="desktop" data-type="finance" class = "cal_term cal_term_selected ">
                                    <div class = "cal_term_label cal_term_label_selected">Finance</div>
                                    <div style = "top:5px" class="cal_term_value">${{number_format($offerlogix['financing']['monthlyPayment'],0,"","")}}</div>
                                    <div class = "verbiage1">for {{$offerlogix['financing']['term']}} months @ {{$offerlogix['financing']['interestRate']}}% interest with</div>
                                    <div class = "verbiage2">${{number_format($offerlogix['financing']['downPayment'],2,".",",")}} down. Taxes and fees included.</div>
                                </div>
                            @endif
                            @if(!empty($offerlogix['leasing']))
                                <div data-view="desktop" data-type="lease" class = "cal_term ">
                                    <div class = "cal_term_label ">Lease</div>
                                    <div style = "top:5px" class="cal_term_value">${{number_format($offerlogix['leasing']['monthlyPayment'],0,"","")}}</div>
                                    <div class = "verbiage1">for {{$offerlogix['leasing']['term']}} months @ {{$offerlogix['leasing']['interestRate']}}% interest with</div>
                                    <div class = "verbiage2">${{number_format($offerlogix['leasing']['downPayment'],2,".",",")}} down. Taxes and fees included.</div>
                                </div>

                            @else

                                <div onclick="return false" style = "cursor: default" data-view="desktop" data-type="lease" class = "cal_term ">
                                    <div class = "cal_term_label  ">Lease</div>
                                    <div class="cal_term_value">N/A</div>
                                </div>

                            @endif

                        </div>


                        @if(!empty($offerlogix['financing']))
                            <div class = "items-wrapper">
                                <div class="items-block">
                                    <div class = "credit_block">
                                        <div class="item_label">How is your Credit?</div>
                                        <div class = "credit_block_items">
                                            @foreach($available_credit_types as $a_type)
                                                <div data-type = "{{$a_type}}"  class = "credit_item {{($credit_selected == $a_type) ? 'item_selected' : ''}}">{{ucfirst($a_type)}}</div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class = "term_block">
                                        <div  class="item_label financing_term_label">Select your financing term.</div>
                                        <div style = "display:none" class="item_label leasing_term_label">Select your leasing term.</div>
                                        <div class = "term_block_items">
                                            @foreach($offerlogix['financing']['terms'] as $term)
                                                @if(!in_array($term, array(36, 48, 60, 66, 72, 84)) && $offerlogix['financing']['term'] != $term)
                                                    @php
                                                        continue;
                                                    @endphp
                                                @endif

                                                @if(!empty($offerlogix_settings) && $offerlogix_settings['selected'] == 'finance')
                                                    @if($term == $offerlogix_settings['finance_term'])
                                                        <div data-type="finance" data-value="{{$term}}" class = "term_item item_selected">{{$term}}</div>
                                                    @else
                                                        <div data-type="finance" data-value="{{$term}}" class = "term_item">{{$term}}</div>
                                                    @endif

                                                @else

                                                    @if($term == $offerlogix['financing']['term'])
                                                        <div data-type="finance" data-value="{{$term}}" class = "term_item item_selected">{{$term}}</div>
                                                    @else
                                                        <div data-type="finance" data-value="{{$term}}" class = "term_item">{{$term}}</div>
                                                    @endif

                                                @endif


                                            @endforeach


                                        </div>
                                        <div style = "display:none" class = "lease_term_block_items">


                                            @if(!empty($offerlogix['leasing']))
                                                @foreach($offerlogix['leasing']['terms'] as $term)
                                                    @if(!in_array($term, array(24, 36, 42, 48)) && $offerlogix['leasing']['term'] != $term )
                                                        @php
                                                            continue;
                                                        @endphp
                                                    @endif

                                                    @if(!empty($offerlogix_settings) && $offerlogix_settings['selected'] == 'lease')
                                                        @if($term == $offerlogix_settings['lease_term'])
                                                            <div data-type="lease" data-value="{{$term}}" class = "term_item item_selected">{{$term}}</div>
                                                        @else
                                                            <div data-type="lease" data-value="{{$term}}" class = "term_item">{{$term}}</div>
                                                        @endif

                                                    @else


                                                        @if($term == $offerlogix['leasing']['term'])
                                                            <div data-type="lease" data-value="{{$term}}" class = "term_item item_selected">{{$term}}</div>
                                                        @else
                                                            <div data-type="lease" data-value="{{$term}}" class = "term_item">{{$term}}</div>
                                                        @endif
                                                    @endif

                                                @endforeach
                                            @endif


                                        </div>

                                    </div>

                                    @if(!empty($offerlogix['leasing']))
                                        <div style = "display:none" class = "mileage_block">
                                            <div class="item_label">Select your lease mileage/years</div>
                                            <div class = "mileage_block_items">
                                                @if(!empty($offerlogix_settings) && $offerlogix_settings['selected'] == 'lease')
                                                    <div data-value="10000" class = "mileage_item {{($offerlogix_settings['lease_miles'] == 10000) ? 'item_selected':''}}">10,000</div>
                                                    <div data-value="12000" class = "mileage_item {{($offerlogix_settings['lease_miles'] == 12000) ? 'item_selected':''}}">12,000</div>
                                                    <div data-value="15000" class = "mileage_item {{($offerlogix_settings['lease_miles'] == 15000) ? 'item_selected':''}}">15,000</div>

                                                @else

                                                    <div data-value="10000" class = "mileage_item">10,000</div>
                                                    <div data-value="12000" class = "mileage_item {{($offerlogix['leasing']['mileageAllowed'] == 12000) ? 'item_selected':''}}">12,000</div>
                                                    <div data-value="15000" class = "mileage_item">15,000</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif



                                </div>
                                <div class ="downpayment-block">
                                    <div class="item_label">How much do you want to put down?</div>
                                    <input onblur="downpaymentChange()" id = "downpayment_input" style = "font-weight: 700;font-size: 24px;line-height: 30px;color: #212121;border:1px solid #C1C1D0; margin-top:10px;height: 50px;max-width: 300px;" class = "form-control" type = "number" value = "{{(int) $downpayment}}" >
                                    <div class = "downpayment-verbiage">
                                        We recommend you to try to cover at least 20% of the purchase price for a new car. For a used one, a 10% down payment might do. Part of your decision will depend on where your credit score stands.
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    @if(!empty($offerlogix['financing']))
                        <div class = "cal_payment_block">

                            <div class="payment_header">Payment Summary</div>

                            <div class ="payment_line_item">
                                <div class = "payment_line_label">Vehicle Price</div>
                                <div class = "payment_line_value">{{$vehicle->finalPrice}}</div>
                            </div>

                            <div class ="payment_line_item">
                                <div class = "payment_line_label">Incentive/Rebates<div> <i style="color: #039BE5;cursor:pointer;margin-left:5px" onclick="showAmounts('rebate')" class="fas fa-asterisk fa-question-circle"></i></div></div>
                                <div class = "payment_line_value rebates_value">${{$rebates_financing}}</div>
                            </div>

                            <div class ="payment_line_item">
                                <div class = "payment_line_label">Selling Price</div>
                                <div class = "payment_line_value">{{$vehicle->finalPrice}}</div>
                            </div>

                            <div class ="payment_line_item">
                                <div class = "payment_line_label">Fees<div> <i style="color: #039BE5;cursor:pointer;margin-left:5px" onclick="showAmounts('fees')" class="fas fa-asterisk fa-question-circle"></i></div></div>
                                <div class = "payment_line_value fees_value">${{$offerlogix['financing']['fees']}}</div>
                            </div>

                            <div class ="payment_line_item">
                                <div class = "payment_line_label">Taxes<div> <i style="color: #039BE5;cursor:pointer;margin-left:5px" onclick="showAmounts('taxes')" class="fas fa-asterisk fa-question-circle"></i></div></div>
                                <div class = "payment_line_value taxes_value">${{$offerlogix['financing']['taxes']}}</div>
                            </div>

                            <div class ="payment_line_item">
                                <div class = "payment_line_label">Interest Rate</div>
                                <div class = "payment_line_value ir_value">{{$offerlogix['financing']['interestRate']}}%</div>
                            </div>


                            <div style = "border: 1px solid #D7D7D7;height:0px;margin-top:10px;margin-bottom:20px"></div>

                            <div class ="payment_line_item">
                                <span style="float:left;font-size: 12px;color: #039be5;font-weight: 800;cursor: pointer;" id="launchDisplayModal">Add/Edit Trade-In</span>&nbsp;&nbsp; <span style="display:none;float:right;font-size: 12px;color: #039be5;font-weight: 800;cursor: pointer;" id="removeTrade">Remove Trade-In</span>
                            </div>
                            <div class ="payment_line_item">
                                <div class = "payment_line_label">Trade-In Value</div>
                                <div class = "payment_line_value tradein_value">${{ !empty($tradein) ?  number_format($tradein,0,'',',') : '0' }}</div>
                            </div>

                            <div class ="payment_line_item">
                                <div class = "payment_line_label">Payoff</div>
                                <div class = "payment_line_value payoff_value">${{ !empty($payoff) ?  number_format($payoff,0,'',',') : '0' }}</div>
                            </div>


                            <div style = "border: 1px solid #D7D7D7;height:0px;margin-top:10px;margin-bottom:20px"></div>

                            <div class ="payment_line_item">
                                <div style = "margin-top: 5px;font-weight: 700;font-size: 20px;line-height: 25px;color: #000000;" class = "payment_line_label">Zip Code</div>
                                <div class = "payment_line_value"><input style = "height: 32px;max-width: 100px;" class = "form-control" type = "text" value = "{{$offerlogix['zip']}}" ></div>
                            </div>

                            <div style = "margin-bottom: 30px;font-weight: 400;font-size: 12px;line-height: 15px;align-items: center;color: rgba(33, 33, 33, 0.5);">
                                Taxes & Incentives based on provided zip code
                            </div>

                            <div style = "width:100%;margin-bottom:10px;margin-top: 5px;font-weight: 700;font-size: 20px;line-height: 25px;color: #000000;" class = "payment_line_label">Your Deal Price</div>

                            <div class="deal_value" style = "margin-bottom: 5px;font-weight: 700;font-size: 24px;line-height: 30px;color: #FF8F00;">${{number_format($offerlogix['financing']['monthlyPayment'],0,"","")}}</div>
                            <div style="font-weight: 700;font-size: 14px;width:100%;text-align:center;" class="btn btn-primary next-step-btn" onclick="saveVehicle()">SELECT VEHICLE</div>

                        </div>

                    @endif
                </div>
            @endif

            <div class = "about_block">
                <div style = "margin-bottom:10px;font-weight: 700;font-size: 20px;line-height: 25px;color: #000000;">About {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->modelName}} </div>

                <div style = "margin-bottom: 20px!important;" class = "cal_block_mobile" >
                    <div data-type="key_features" class = "about_label about_selected ">Key Features</div>
                    <div data-type="extra_features" class = "about_label">Extra Features</div>
                    <div data-type="info" class = "about_label  ">Description</div>
                </div>
                <div style = "" class = "key_features_block">
                    <div class ="row">
                        @if(!empty($vehicle->engine))
                            <div style = "margin-bottom:20px" class = "col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div style="display:flex">
                                    <div style ="margin-right:10px">
                                        <i style ="margin-top: 5px;font-size: 20px;color: #039BE5;background-color: #F0F3F5;padding: 5px;border-radius: 5px;" class="fas fa-car"></i>
                                    </div>
                                    <div>
                                        <span style ="font-size: 12px;font-weight: 600;color: #757575;">Engine</span><br>
                                        <span style = "font-size: 16px; font-weight: 500">{{$vehicle->engine}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($vehicle->odometer))
                            <div class = "col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div style="display:flex">
                                    <div style ="margin-right:10px">
                                        <i style ="margin-top: 5px;font-size: 20px;color: #039BE5;background-color: #F0F3F5;padding: 5px;border-radius: 5px;" class="fas fa-car"></i>
                                    </div>
                                    <div>
                                        <span style ="font-size: 12px;font-weight: 600;color: #757575;">Odometer</span><br>
                                        <span style = "font-size: 16px; font-weight: 500">{{$vehicle->odometer}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($vehicle->bodyStyle))
                            <div class = "col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div style="display:flex">
                                    <div style ="margin-right:10px">
                                        <i style ="margin-top: 5px;font-size: 20px;color: #039BE5;background-color: #F0F3F5;padding: 5px;border-radius: 5px;" class="fas fa-car"></i>
                                    </div>
                                    <div>
                                        <span style ="font-size: 12px;font-weight: 600;color: #757575;">Body Style</span><br>
                                        <span style = "font-size: 16px; font-weight: 500">{{$vehicle->bodyStyle}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($vehicle->exterior))
                            <div class = "col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div style="display:flex">
                                    <div style ="margin-right:10px">
                                        <i style ="margin-top: 5px;font-size: 20px;color: #039BE5;background-color: #F0F3F5;padding: 5px;border-radius: 5px;" class="fas fa-car"></i>
                                    </div>
                                    <div>
                                        <span style ="font-size: 12px;font-weight: 600;color: #757575;">Exterior Color</span><br>
                                        <span style = "font-size: 16px; font-weight: 500">{{$vehicle->exterior}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($vehicle->transmission))
                            <div class = "col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div style="display:flex">
                                    <div style ="margin-right:10px">
                                        <i style ="margin-top: 5px;font-size: 20px;color: #039BE5;background-color: #F0F3F5;padding: 5px;border-radius: 5px;" class="fas fa-car"></i>
                                    </div>
                                    <div>
                                        <span style ="font-size: 12px;font-weight: 600;color: #757575;">Transmission</span><br>
                                        <span style = "font-size: 16px; font-weight: 500">{{$vehicle->transmission}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($vehicle->driveTrain))
                            <div class = "col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div style="display:flex">
                                    <div style ="margin-right:10px">
                                        <i style ="margin-top: 5px;font-size: 20px;color: #039BE5;background-color: #F0F3F5;padding: 5px;border-radius: 5px;" class="fas fa-car"></i>
                                    </div>
                                    <div>
                                        <span style ="font-size: 12px;font-weight: 600;color: #757575;">Drive Train</span><br>
                                        <span style = "font-size: 16px; font-weight: 500">{{$vehicle->driveTrain}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(!empty($vehicle->fuelEconomy))
                            <div class = "col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div style="display:flex">
                                    <div style ="margin-right:10px">
                                        <i style ="margin-top: 5px;font-size: 20px;color: #039BE5;background-color: #F0F3F5;padding: 5px;border-radius: 5px;" class="fas fa-car"></i>
                                    </div>
                                    <div>
                                        <span style ="font-size: 12px;font-weight: 600;color: #757575;">City/Highway</span><br>
                                        <span style = "font-size: 16px; font-weight: 500">{{$vehicle->fuelEconomy->City}} / {{$vehicle->fuelEconomy->Highway}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($vehicle->interior))
                            <div class = "col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div style="display:flex">
                                    <div style ="margin-right:10px">
                                        <i style ="margin-top: 5px;font-size: 20px;color: #039BE5;background-color: #F0F3F5;padding: 5px;border-radius: 5px;" class="fas fa-car"></i>
                                    </div>
                                    <div>
                                        <span style ="font-size: 12px;font-weight: 600;color: #757575;">Interior Color</span><br>
                                        <span style = "font-size: 16px; font-weight: 500">{{$vehicle->interior}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div></div>
                <div  style = "display:none" class = "extra_features_block">


                        @foreach($categories as $cat_name => $cat_items)
                        <div style="margin: 30px 0px 10px 0px;font-weight: 700;font-size: 18px;line-height: 23px;letter-spacing: 0.035em;text-transform: uppercase;color: #039BE5;" class = "row"><div class = "col-md-12">{{$cat_name}}</div></div>
                            <div class = "row">
                            @foreach($cat_items as $cat_name)

                                <div class ="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">

                                    <div style="display:flex">
                                        <div style ="margin-right:10px">
                                            <i style ="margin-top: 3px;font-size: 15px;color: #039BE5;padding: 5px;border-radius: 5px;" class="fas fa-check-circle"></i>
                                        </div>
                                        <div>
                                            <span style = "font-weight: 500;font-size: 14px;line-height: 18px;text-transform: capitalize;color: #212121;">{{$cat_name}}</span>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                            </div>
                        @endforeach



                </div>
                <div style ="display:none" class = "info_block">
                    <div class = "row">
                        <div style = "color: #2D364C;font-size: 14px; font-weight: 500" class = "col-md-12">
                            {{$vehicle->description}}
                        </div>
                    </div>
                </div>

            </div>







        </div>
    </div>

    <div id="myModal" class="display-modal">

        <!-- Modal content -->
        <div class="display-modal-content">
            <span class="display-close">&times;</span>
            <div class = "display-modal-content-header">Add Your Trade-In</div>

            <div class = "display-modal-item-main">
                <div class = "display-modal-label">Year, Make and Model</div>
                <select id = "select2_search" style="width: 100%"></select>
            </div>
            <div class = "display-modal-item display-modal-item-body">
                <div class = "display-modal-label">Select Body</div>
                <select id = "select2_body" style="width: 100%"></select>
            </div>
            <div class = "display-modal-item display-modal-item-drivetrain">
                <div class = "display-modal-label">Select DriveTrain</div>
                <select id = "select2_drivetrain" style="width: 100%"></select>
            </div>
            <div class = "display-modal-item display-modal-item-engine">
                <div class = "display-modal-label">Select Engine</div>
                <select id = "select2_engine" style="width: 100%"></select>
            </div>
            <div class = "display-modal-item display-modal-item-fuel">
                <div class = "display-modal-label">Select Fuel Type</div>
                <select id = "select2_fuel" style="width: 100%"></select>
            </div>
            <div style="text-align: center" class = "display-modal-item-main ">
                <a style = "margin: 10px;text-align: center;font-weight: 800" onclick="resetSearch()" class="btn btn-primary skip-btn" href="javascript:void(0)">Reset Search</a>
            </div>

            <div class = "display-modal-item display-modal-item-mileage">
                <div class = "display-modal-label">Mileage</div>
                <input id = "display_modal_mileage" style="font-weight: 500;font-size: 20px;line-height: 30px;color: #212121;border:1px solid #C1C1D0; margin-top:0px;height: 40px;max-width: 300px;" class="form-control" type="number" value="">
            </div>
            <div style="text-align: center" class = "display-modal-item display-modal-item-report-button">
                <a style = "margin: 10px;text-align: center;font-weight: 800" onclick="valueTradeReport()" class="btn btn-primary skip-btn" href="javascript:void(0)">Value Trade</a>
            </div>

            <div class = "display-modal-item display-modal-results-block">
                <div class = "display-modal-label">Estimated Trade-In Value</div>
                <div style = "justify-content: space-between;margin-bottom: 10px;display:flex">
                    <div style = "float:left;font-size: 25px;font-weight: 800;" id = "display-modal-trade-value"></div>
                    <div onclick="showTradeReport()" style = "float: right; cursor:pointer;font-size: 15px;color: #039be5;font-weight: 800;">See report</div>
                </div>
                <div class = "display-modal-item-main display-modal-item-mileage">
                    <div class = "display-modal-label">Payoff</div>
                    <input id = "display_modal_payoff" style="font-weight: 500;font-size: 20px;line-height: 30px;color: #212121;border:1px solid #C1C1D0; margin-top:0px;height: 40px;max-width: 300px;" class="form-control" type="number" value="">
                </div>
                <div style="text-align: center" class = "display-modal-item-main display-modal-item-submit-button">
                    <a style = "margin: 10px;text-align: center;font-weight: 800" onclick="valueTradeSave()" class="btn btn-primary skip-btn" href="javascript:void(0)">Submit</a>
                </div>
            </div>



        </div>

    </div>




@endsection

@section('page-js')
    <script src="{{ asset('js/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
        var final_price = '{{$vehicle->price}}';
        var v_id = '{{$vehicle->id}}';
        var v_stock = '{{$vehicle->stockNumber}}';
        var tradein_search = {};
        const tradein_search_default = {id: 0, text: '', year: '', make: '', model: '', trim: '', body: '', drivetrain: '', engine: '', fuel: '', mileage: 0, payoff: 0, tradein: 0};
        var show_leasing = false;
        if("{{$show_leasing}}" == "true") {
            show_leasing = true;
        }


        if("{{$show_financing}}" == "true") {
            var offerlogix_format = String('{{$offerlogix_object_all}}');
            var offerlogix = JSON.parse(offerlogix_format.replace(/&quot;/g, '"'));
            var offerlogix_settings_format = String('{{$offerlogix_settings_json}}');
            var offerlogix_settings = JSON.parse(offerlogix_settings_format.replace(/&quot;/g, '"'));
            var selected = offerlogix_settings.selected;
            var credit_selected = offerlogix_settings.credit_selected;

            if(selected == 'finance'){
                var finance_term_selected = offerlogix_settings.finance_term;
                if("{{$show_leasing}}" == "true") {
                    var lease_term_selected = offerlogix[credit_selected]['leasing']['term'];
                    var mileage_selected = offerlogix[credit_selected]['leasing']['mileageAllowed'];
                }
            }else{
                var lease_term_selected = offerlogix_settings.lease_term;
                var mileage_selected = offerlogix_settings.lease_miles;
                var finance_term_selected =  offerlogix[credit_selected]['financing']['term'];
            }


            var rebates_financing = '{{$rebates_financing}}';
            var rebates_leasing = '{{$rebates_leasing}}';
            var rebates_cash = '{{$rebates_cash}}';
            var zip = '{{@$offerlogix['zip']}}';
            var downpayment = '{{@$downpayment}}';
            var payoff = '{{$payoff}}';
            var tradein = '{{$tradein}}';
            if(offerlogix_settings.tradein_options == 'false'){
                tradein_search = tradein_search_default;
            }else{
                tradein_search = offerlogix_settings.tradein_options;

            }


            var select2_search = null;
            var select2_body = null;
            var select2_drivetrain = null;
            var select2_engine = null;
            var select2_fuel = null;

        }

        var modal = null;
        var ModalViewer = null;
        var user_token = '{{$user_token}}';
        var slideIndex = 1;
        let viewerImageEle = null;

        function showViewer(){
            ModalViewer = Swal.fire({
                template: '#picture-viewer',
                showConfirmButton: false,
                allowOutsideClick: true,
                allowEscapeKey: true,
                width: '80%',
                didOpen: () => {
                    Swal.showLoading();
                    viewerImageEle = Swal.getHtmlContainer().querySelectorAll('.vehicleImage');
                    showSlides(viewerImageEle, 1);
                }
            })
        }

        // Next/previous controls
        function plusSlides(n) {
            if(n == 'back'){
                slideIndex -= 1;
            }else{
                slideIndex += 1;
            }

            //console.log(slideIndex);
            showSlides(viewerImageEle, slideIndex);
        }

        function showSlides(slides, n) {
            var i;


            if (n > slides.length) {slideIndex = 1}
            if (n < 1) {slideIndex = slides.length}

            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";

            }

            slides[slideIndex-1].style.display = "block";
            Swal.hideLoading();
        }

        function downpaymentChange(){
            var amount_entered = $('#downpayment_input').val();
            if(downpayment == amount_entered){
                return;
            }

            downpayment = amount_entered;
            updatePayment();
        }

        function showAmounts(type){
            var title = '';
            var doc = 'N/A';
            var obj = [];
            var template = '';

            if(selected == 'finance') {
                var option = offerlogix[credit_selected]['financing']['options'][finance_term_selected];
                var _downpayment = parseInt(option['downPayment']);
                doc = parseInt(offerlogix[credit_selected]['doc_fee']);


                if(type == 'rebate') {
                    title += 'Rebates ';
                    var rebate_found = false;
                    $.each(offerlogix[credit_selected], function(i, item){
                        if(item.type == 'financing'){
                            var cash = obj.cash;
                            var program_id = obj.program_id;
                            var category = obj.category;
                            var disclaimer = obj.disclaimer;
                            rebate_found = true;
                            template = '<div style = "padding:15px"><p style = "font-weight:700">Cash Off - $'+cash+'</p><br>' +
                                '<p><span style = "font-weight:700">Disclaimer:</span><br>'+disclaimer+'</p><br>' +
                                '<p><span style = "font-weight:700">Program ID:</span>&nbsp;&nbsp;'+program_id+'</p><br>' +
                                '<p><span style = "font-weight:700">Category:</span>&nbsp;&nbsp;'+category+'</p><br></div>';
                        }

                    });




                }else if(type == 'taxes'){
                    title += 'Taxes ';
                    $.each(option.taxes_detail, function(i, item){
                        obj.push({name: item.feeName, amount: parseFloat(item.amount)});
                    });

                }else if(type == 'fees'){
                    title += 'Fees ';
                    obj.push({name: 'Doc Fee', amount: doc});
                    obj.push({name: 'Down Payment', amount: _downpayment});
                    $.each(option.fees_detail, function(i, item){
                        obj.push({name: item.feeName, amount: parseInt(item.amount)});
                    });

                }



            }else if(selected == 'lease'){
                var option = offerlogix[credit_selected]['leasing']['options'][lease_term_selected];
                doc = parseInt(offerlogix[credit_selected]['doc_fee']);


                if(type == 'rebate') {
                    title += 'Rebates ';
                    var rebate_found = false;
                    $.each(offerlogix[credit_selected], function(i, item){
                        if(item.type == 'financing'){
                            var cash = obj.cash;
                            var program_id = obj.program_id;
                            var category = obj.category;
                            var disclaimer = obj.disclaimer;
                            rebate_found = true;
                            template = '<div style = "padding:15px"><p style = "font-weight:700">Cash Off - $'+cash+'</p><br>' +
                                '<p><span style = "font-weight:700">Disclaimer:</span><br>'+disclaimer+'</p><br>' +
                                '<p><span style = "font-weight:700">Program ID:</span>&nbsp;&nbsp;'+program_id+'</p><br>' +
                                '<p><span style = "font-weight:700">Category:</span>&nbsp;&nbsp;'+category+'</p><br></div>';
                        }

                    });




                }else if(type == 'taxes'){
                    title += 'Taxes ';
                    $.each(option.taxes_detail, function(i, item){
                        obj.push({name: item.feeName, amount: parseFloat(item.amount)});
                    });

                }else if(type == 'fees'){
                    title += 'Fees ';
                    var acquisitionFee = parseInt(option['acquisitionFee']);
                    var upfront = parseInt(option['upfront']);
                    obj.push({name: 'Doc Fee', amount: doc});
                    obj.push({name: 'Acquisition Fee', amount: acquisitionFee});
                    obj.push({name: 'Upfront Fee', amount: upfront});
                    $.each(option.fees_detail, function(i, item){
                        obj.push({name: item.feeName, amount: parseInt(item.amount)});
                    });

                }




            }else{
                var option = {};
            }


            if(type != 'rebate') {
                $.each(obj, function (i, item) {
                    template += '<div style = "padding:10px;justify-items:left;display:grid;grid-template-columns: 200px 1fr;font-weight: 800"><div>' + item.name + '</div> <div>$' + item.amount.toFixed(2) + '</div></div>';
                });
            }


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

        function updateFinanceInfo(){
            var range = [];
            var payment = offerlogix[credit_selected]['financing']['options'][finance_term_selected]['monthlyPayment'];
            var ir = offerlogix[credit_selected]['financing']['options'][finance_term_selected]['interestRate'];
            var term = offerlogix[credit_selected]['financing']['options'][finance_term_selected]['term'];
            var downpayment = offerlogix[credit_selected]['financing']['options'][finance_term_selected]['downPayment'];

            //console.log(offerlogix);
            if(Object.keys(offerlogix['poor']['financing']).length !== 0 && Object.keys(offerlogix['poor']['financing']['options']).length !== 0 && offerlogix['poor']['financing']['options'].hasOwnProperty(finance_term_selected) == true  ){
                range.push(offerlogix['poor']['financing']['options'][finance_term_selected]['monthlyPayment']);
            }
            if(Object.keys(offerlogix['fair']['financing']).length !== 0 && Object.keys(offerlogix['fair']['financing']['options']).length !== 0 && offerlogix['fair']['financing']['options'].hasOwnProperty(finance_term_selected) == true ){
                range.push(offerlogix['fair']['financing']['options'][finance_term_selected]['monthlyPayment']);
            }
            if(Object.keys(offerlogix['good']['financing']).length !== 0 && Object.keys(offerlogix['good']['financing']['options']).length !== 0 && offerlogix['good']['financing']['options'].hasOwnProperty(finance_term_selected) == true ){
                range.push(offerlogix['good']['financing']['options'][finance_term_selected]['monthlyPayment']);
            }
            if(Object.keys(offerlogix['excellent']['financing']).length !== 0 && Object.keys(offerlogix['excellent']['financing']['options']).length !== 0 && offerlogix['excellent']['financing']['options'].hasOwnProperty(finance_term_selected) == true ){
                range.push(offerlogix['excellent']['financing']['options'][finance_term_selected]['monthlyPayment']);
            }
            //console.log(range);
            if(range.length > 1){
                const highest = Math.max(...range);
                const lowest = Math.min(...range);
                if(highest == lowest){
                    $('.payment_box_inner_price[data-type=finance]').html('$'+parseInt(payment).toLocaleString("en-US"));

                }else{
                    $('.payment_box_inner_price[data-type=finance]').html('$'+parseInt(lowest).toLocaleString("en-US")+' - '+'$'+parseInt(highest).toLocaleString("en-US"));

                }
            }else{
                $('.payment_box_inner_price[data-type=finance]').html('$'+parseInt(payment).toLocaleString("en-US"));
            }

            $('.cal_term[data-type=finance]').find('.cal_term_value').html('$'+payment.toLocaleString("en-US"));

            $('.cal_term[data-type=finance]').find('.verbiage1').html('for '+term+' months @'+ir+'% interest with');
            $('.cal_term[data-type=finance]').find('.verbiage2').html('$'+downpayment.toLocaleString("en-US")+' down. Taxes and fees included.');

            $('.cal_block_mobile_msg').html('for '+term+' months @'+ir+'% interest with<br>'+'$'+downpayment.toLocaleString("en-US")+' down. Taxes and fees included.');


        }

        function updateLeaseInfo(){
            var range = [];
            var payment = offerlogix[credit_selected]['leasing']['options'][lease_term_selected]['monthlyPayment'];
            var ir = offerlogix[credit_selected]['leasing']['options'][lease_term_selected]['interestRate'];
            var term = offerlogix[credit_selected]['leasing']['options'][lease_term_selected]['term'];
            var downpayment = offerlogix[credit_selected]['leasing']['options'][lease_term_selected]['downPayment'];
            //console.log(offerlogix[credit_selected]['leasing']['options'][lease_term_selected]);
            if(Object.keys(offerlogix['poor']['leasing']).length !== 0 && Object.keys(offerlogix['poor']['leasing']['options']).length !== 0 && offerlogix['poor']['leasing']['options'].hasOwnProperty(lease_term_selected) == true ){
                range.push(offerlogix['poor']['leasing']['options'][lease_term_selected]['monthlyPayment']);
            }
            if(Object.keys(offerlogix['fair']['leasing']).length !== 0 && Object.keys(offerlogix['fair']['leasing']['options']).length !== 0 && offerlogix['fair']['leasing']['options'].hasOwnProperty(lease_term_selected) == true ){
                range.push(offerlogix['fair']['leasing']['options'][lease_term_selected]['monthlyPayment']);
            }
            if(Object.keys(offerlogix['good']['leasing']).length !== 0 && Object.keys(offerlogix['good']['leasing']['options']).length !== 0 && offerlogix['good']['leasing']['options'].hasOwnProperty(lease_term_selected) == true ){
                range.push(offerlogix['good']['leasing']['options'][lease_term_selected]['monthlyPayment']);
            }
            if(Object.keys(offerlogix['excellent']['leasing']).length !== 0 && Object.keys(offerlogix['excellent']['leasing']['options']).length !== 0 && offerlogix['excellent']['leasing']['options'].hasOwnProperty(lease_term_selected) == true ){
                range.push(offerlogix['excellent']['leasing']['options'][lease_term_selected]['monthlyPayment']);
            }
            //console.log(range);
            if(range.length > 1){
                const highest = Math.max(...range);
                const lowest = Math.min(...range);

                if(highest == lowest){
                    $('.payment_box_inner_price[data-type=lease]').html('$'+parseInt(payment).toLocaleString("en-US"));

                }else{
                    $('.payment_box_inner_price[data-type=lease]').html('$'+parseInt(lowest).toLocaleString("en-US")+' - '+'$'+parseInt(highest).toLocaleString("en-US"));

                }
            }else{
                $('.payment_box_inner_price[data-type=lease]').html('$'+parseInt(payment).toLocaleString("en-US"));
            }

            $('.cal_term[data-type=lease]').find('.cal_term_value').html('$'+payment.toLocaleString("en-US"));

            $('.cal_term[data-type=lease]').find('.verbiage1').html('for '+term+' months @'+ir+'% interest with');
            $('.cal_term[data-type=lease]').find('.verbiage2').html('$'+downpayment.toLocaleString("en-US")+' down. Taxes and fees included.');

            $('.cal_block_mobile_msg').html('for '+term+' months @'+ir+'% interest with<br>'+'$'+downpayment.toLocaleString("en-US")+' down. Taxes and fees included.');

        }


        function updatePayment(){
            let taxes = 0;
            let fees = 0;
            let ir = 0.0;
            let payment = 0;
            console.log(offerlogix);
            if(selected == 'finance'){
                $('.rebates_value').html('$'+parseInt(rebates_financing));

                var option = offerlogix[credit_selected]['financing']['options'][finance_term_selected];
                if(option == undefined || option == 'undefined'){
                    $.each($('.term_item[data-type=finance]'), function(i,obj){
                        $(this).removeClass('item_selected');
                    });

                    showError('Please change the financing term. '+finance_term_selected+' months is not available.');
                    finance_term_selected=null;

                    return;
                }
                var term = option['term'];
                // if(option['downPayment'] != downpayment){
               // console.log(tradein_search);
                var _price = parseInt(final_price);
                var _doc_fee = parseInt(offerlogix[credit_selected]['doc_fee']);
                var _tradein = (isFinite(tradein_search.tradein) && parseInt(tradein_search.tradein) > 0)? parseInt(tradein_search.tradein) : 0;
                var _payoff = (isFinite(tradein_search.payoff) && parseInt(tradein_search.payoff) > 0)? parseInt(tradein_search.payoff) : 0;
                var _downpayment = (isFinite(downpayment) && parseInt(downpayment) > 0)? parseInt(downpayment) : 0;
                var _taxrate = parseFloat(option['salesTaxPct']);
                var _other_fees = 0;

                $.each(option.fees_detail, function (i, item) {
                    if (item.feeType == "Registration") {
                        _other_fees += parseInt(item['amount']);

                    }
                });

                var _rebate = parseInt(rebates_financing);
                var _ir = parseFloat(option['interestRate']) / 100 /12;
                ir = option['interestRate'];
                var _term = parseInt(option['term']);

                _price += _doc_fee;
                _price -= _tradein;
                _price += _payoff;
                _price += _other_fees;
                //console.log(_price);
                var _taxes = _price * _taxrate;
                //console.log(_taxes);
                _price += _taxes;
                _price -= _rebate;
                _price -= _downpayment;
                ////console.log(_price);
                var _x = Math.pow(1 + _ir, _term);
                //console.log(_x);
                var _payment = parseInt((_price * _x * _ir)/(_x-1));
                //console.log(_payment);
                payment = parseInt(_payment);
                fees = option['fees'];
                taxes = _taxes;

                // }else{
                //     taxes = option['taxes'];
                //     fees = option['fees'];
                //     ir = option['interestRate'];
                //     payment = option['monthlyPayment'];
                // }


                $('.cal_term[data-type=finance]').find('.cal_term_value').html('$'+payment.toLocaleString("en-US"));
                $('.cal_block_mobile_price').html('$'+parseInt(payment).toLocaleString("en-US"));

                //updateFinanceInfo();

                $('.payment_box_inner_price[data-type=finance]').html('$'+parseInt(payment).toLocaleString("en-US"));


                $('.cal_term[data-type=finance]').find('.cal_term_value').html('$'+payment.toLocaleString("en-US"));

                $('.cal_term[data-type=finance]').find('.verbiage1').html('for '+term+' months @'+ir+'% interest with');
                $('.cal_term[data-type=finance]').find('.verbiage2').html('$'+downpayment.toLocaleString("en-US")+' down. Taxes and fees included.');

                $('.cal_block_mobile_msg').html('for '+term+' months @'+ir+'% interest with<br>'+'$'+downpayment.toLocaleString("en-US")+' down. Taxes and fees included.');



            }else if(selected == 'lease'){
                $('.rebates_value').html('$'+rebates_leasing);
                var options = offerlogix[credit_selected]['leasing']['options'];

                if(Object.keys(offerlogix[credit_selected]['leasing']).length === 0){
                    showError('Please select a different credit rating. '+credit_selected.charAt(0).toUpperCase() + credit_selected.slice(1)+' credit rating is unavailable.');

                    credit_selected = null;
                    $('.credit_item').removeClass('item_selected');
                    return;
                }


                if(options == undefined || options == 'undefined'){
                    $.each($('.term_item[data-type=lease]'), function(i,obj){
                        $(this).removeClass('item_selected');
                    });
                    showError('Please change the leasing term. '+lease_term_selected+' months is not available.');

                    lease_term_selected=null;
                    return;
                }


                let payment_found = false;
                let term_found = false;
                let mileage_found = false;
                var _downpayment = 0;
                var _doc_fee = 0;
                var option = null;
                $.each(options, function(i, item){
                    if(item.term == lease_term_selected ){
                        term_found = true;
                        if(item.mileageAllowed == mileage_selected){

                            mileage_found = true;
                            option = item;
                            payment_found = true;
                        }

                    }
                });

                if(!payment_found){
                    if(!term_found){
                        showError('Please change the leasing term. '+lease_term_selected+' months is not available.');

                        lease_term_selected=null;
                        return;
                    }

                    if(!mileage_found){
                        showError('Please change the leasing mileage. '+mileage_selected+' miles per year not available.');

                        // mileage_selected=null;
                        updateLoan();
                        return;
                    }

                }else {
                    taxes = option.taxes;
                    fees = 0;
                    payment = option.monthlyPayment;
                    ir = option.interestRate;
                    var _tradein = (isFinite(tradein_search.tradein) && parseInt(tradein_search.tradein) > 0)? parseInt(tradein_search.tradein) : 0;
                    var _payoff = (isFinite(tradein_search.payoff) && parseInt(tradein_search.payoff) > 0)? parseInt(tradein_search.payoff) : 0;

                    var term = option.term;
                    var money_factor = parseFloat(ir / 2400);
                    //console.log(money_factor);
                    var sales_tax = parseFloat(option.salesTaxPct);
                    var residual_pct = parseFloat(option.residualPct);
                    var residual_amount = parseFloat(option.residualAmount);
                    var _downpayment = downpayment;
                    //console.log('downpayment - ' + _downpayment);
                    var _doc_fee = offerlogix[credit_selected]['doc_fee'];
                    //console.log('doc fee - ' + _doc_fee);
                    var _acquisition_fee = parseInt(option.acquisitionFee);
                    var _other_fees = 0;

                    $.each(option.fees_detail, function (i, item) {
                        if (item.feeType == 'Registration') {
                            _other_fees += parseInt(item['amount']);
                        }
                    });
                    //console.log('other fee - ' + _other_fees);

                    var gross_capitalized = parseInt(final_price) + parseInt(_doc_fee) + parseInt(_acquisition_fee) + parseInt(_other_fees) + parseInt(_payoff);
                    fees += parseInt(_doc_fee) + parseInt(_acquisition_fee) + parseInt(_other_fees);
                    //console.log('gross capitalized - ' + gross_capitalized);
                    //console.log(downpayment);
                    //console.log(rebates_leasing);
                    //console.log(tradein_search);
                    var capitalized_cost = parseInt(downpayment) + parseInt(rebates_leasing) + parseInt(_tradein);
                    //console.log('capitalized cost - ' + capitalized_cost);
                    var adjusted_capitalized = gross_capitalized - capitalized_cost;
                    //console.log('adjusted capitalized - ' + adjusted_capitalized);
                    var depreciation_amount = adjusted_capitalized - residual_amount;
                    //console.log('depreciation amount - ' + depreciation_amount);
                    var base_payment = depreciation_amount / parseInt(term);
                    //console.log('base payment - ' + base_payment);
                    var rent_charge = (adjusted_capitalized + residual_amount) * money_factor;
                    //console.log('rent charge - ' + rent_charge);
                    var pretax_amount = rent_charge + base_payment;
                    //console.log('pretax amount - ' + pretax_amount);
                    var new_tax = pretax_amount * sales_tax;
                    taxes += new_tax;
                    //console.log('new tax - ' + new_tax);
                    var total_amount = pretax_amount + new_tax;
                    payment = parseInt(total_amount);

                    $('.payment_box_inner_price[data-type=lease]').html('$' + parseInt(payment).toLocaleString("en-US"));

                    $('.cal_term[data-type=lease]').find('.cal_term_value').html('$' + payment.toLocaleString("en-US"));

                    $('.cal_term[data-type=lease]').find('.verbiage1').html('for ' + term + ' months @' + ir + '% interest with');
                    $('.cal_term[data-type=lease]').find('.verbiage2').html('$' + downpayment.toLocaleString("en-US") + ' down. Taxes and fees included.');
                    $('.cal_block_mobile_price').html('$'+parseInt(payment).toLocaleString("en-US"));
                    $('.cal_block_mobile_msg').html('for ' + term + ' months @' + ir + '% interest with<br>' + '$' + downpayment.toLocaleString("en-US") + ' down. Taxes and fees included.');

                }


            }else{
                $('.rebates_value').html('$'+parseInt(rebates_cash));
                taxes = 0;
                var _other_fees = 0;
                fees = 0;
                payment = final_price;

                $('.cal_block_mobile_msg').html('');


                $('.cal_block_mobile_price').html('$'+parseInt(payment).toLocaleString("en-US"));
            }

            $('.taxes_value').html('$'+parseInt(taxes).toLocaleString("en-US"));
            $('.fees_value').html('$'+parseInt(_other_fees).toLocaleString("en-US"));
            $('.deal_value').html('$'+parseInt(payment).toLocaleString("en-US"));
            $('.ir_value').html(ir+'%');

        }

        function updateLoan(){
            var data = {};
            //processingShow();
            data['user_token'] = '{{ $user_token }}';
            data['selected'] = selected;
            data['zip'] = zip;
            data['credit_selected'] = credit_selected;
            data['downpayment'] = downpayment;
            data['tradein'] = tradein_search.tradein;
            data['payoff'] = tradein_search.payoff;
            data['vehicle_id'] = v_id;

            if(selected == 'finance'){
                data['finance_term'] = finance_term_selected;
            }
            if(selected == 'lease'){
                data['lease_term'] = lease_term_selected;
                data['lease_miles'] = mileage_selected;
            }



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

            processingShow();

            $.ajax({
                data: data,
                type: "POST",
                url: 'api/offerlogix-calculate',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).fail(function(jqXHR, status){

                processingHide();
                showError('Unable To Process Request. Try Again Or Skip.');
            }).done(function(rsp) {


                var obj = rsp;
                offerlogix = obj;

                updatePayment();

                processingHide();
            });


        }

        function valueTradeVehicle(){
            $.get('https://snap-api.tradepending.com/api/v4/select?country=US&partner_id=LSTXvyra7CZgqCdYX&year=' +
                tradein_search.year+ '&engine=' + tradein_search.engine +
                '&make=' + tradein_search.make + '&fuel_type=' +
                tradein_search.fuel + '&model=' + tradein_search.model +
                '&trim=' + tradein_search.trim+ '&drivetrain=' +
                tradein_search.drivetrain+ '&body=' +
                tradein_search.body ,
                function(data) {
                    if('id' in data == false){ //see if vehicle id present
                        if('choices' in data == false){ //see if choices available
                            showError('Unable to find vehicle. Please try again.');
                            return;
                        }else{
                            var choices = data.choices;
                            var selected = data.select;
                            var items = [];
                            items.push({id:0, text:''});
                            for(let i = 0; i < choices.length; i++){
                                items.push({id: (i+1), text:choices[i]});
                            }

                            if(selected == 'body'){
                                $("#select2_body").empty();
                                $("#select2_body").select2({
                                    data: items
                                });
                                $(".display-modal-item-body").show();
                            }else if(selected == 'drivetrain'){
                                $("#select2_drivetrain").empty();
                                $("#select2_drivetrain").select2({
                                    data: items
                                });
                                $(".display-modal-item-drivetrain").show();
                            }else if(selected == 'engine'){
                                $("#select2_engine").empty();
                                $("#select2_engine").select2({
                                    data: items
                                });
                                $(".display-modal-item-engine").show();
                            }else if(selected == 'fuel_type'){
                                $("#select2_fuel").empty();
                                $("#select2_fuel").select2({
                                    data: items
                                });
                                $(".display-modal-item-fuel").show();
                            }

                        }

                    }else{
                        tradein_search.id = data.id;
                        $(".display-modal-item-mileage").show();
                        $(".display-modal-item-report-button").show();

                    }

                }).fail(function(){
                showError('There was an error finding vehicle.  Please Try again.');
            });
        }


        function valueTradeReport(){
            var mileage = $('#display_modal_mileage').val();
            if(mileage == "" || mileage == undefined || isNaN(mileage)){
                showError("Please enter the mileage of your vehicle.");
                return;
            }
            tradein_search.mileage = mileage;
            var url = 'https://snap-api.tradepending.com/api/v4/report-html?vehicle_id=' + tradein_search.id + '&url=vip.buildabrand.com&zip_code='+zip+'&partner_id=LSTXvyra7CZgqCdYX&mileage=' + mileage;
            //console.log(url);

            //Test url for frame to see if it is okay
            $.get(url)
                .fail(function(){
                    showError('Unable to generate Vehicle Report');

                }).done(function(){

                $.get('https://snap-api.tradepending.com/api/v4/report?country=US&partner_id=LSTXvyra7CZgqCdYX&url=vip.biuldabrand.com&zip_code='+zip+'&vehicle_id=' + tradein_search.id + '&mileage=' + mileage)
                    .fail(function(){
                        showError('Unable to generate Vehicle Report');

                    }).done(function(data){
                    if('target' in data.report.tradein){
                        tradein_search.tradein = data.report.tradein.target;
                        tradein_search.url = encodeURI(url);
                        $('#display-modal-trade-value').html('$'+tradein_search.tradein);
                        $(".display-modal-item-report-button").hide();
                        $(".display-modal-results-block").show();
                        //$('.trade-value-iframe').append('<iframe src="' + url + '"></iframe>');
                    }else{
                        if('error_message' in data.report){
                            showError(data.report.error_message, 10000);
                        }else{
                            showError('Unable to generate report, try again or skip.');
                        }
                    }

                });
            }).fail(function(){
                showError('Unable to Get Vehicle Report');

            });
        }

        function valueTradeSave(){
            var payoff = $('#display_modal_payoff').val();
            if(payoff == "" || payoff == undefined || isNaN(payoff)){
                showError("Please enter the payoff of your vehicle.");
                return;
            }
            tradein_search.payoff = payoff;
            $('.tradein_value').html('$'+tradein_search.tradein);
            $('.payoff_value').html('$'+tradein_search.payoff);
            modal.style.display = "none";
            resetSearch();
            updatePayment();
            $('#removeTrade').show();
            showMessage("Your Trade Valuation has been saved. Loan results are updated!");


        }

        function saveVehicle(){
            var data = {};
            //processingShow();
            data['user_token'] = '{{ $user_token }}';
            data['selected'] = selected;
            data['v_id'] = v_id;
            data['v_stock'] = v_stock;
            data['offerlogix'] = JSON.stringify(offerlogix);
            if(selected == 'finance' || selected == 'lease') {
                data['zip'] = zip;
                data['credit_selected'] = credit_selected;
                data['downpayment'] = downpayment;
                data['tradein'] = tradein_search.tradein;
                data['payoff'] = tradein_search.payoff;
                data['tradein_options'] = JSON.stringify(tradein_search);

                if (selected == 'finance') {
                    data['finance_term'] = finance_term_selected;
                }
                if (selected == 'lease') {
                    data['lease_term'] = lease_term_selected;
                    data['lease_miles'] = mileage_selected;
                }

                if (selected == 'leasing') {
                    if (leaseCredit == "" || leaseCredit == undefined || leaseCredit == 'undefined') {
                        showError('Please select your leasing credit score.');
                        return;
                    }
                    if (leaseTerm == "" || leaseTerm == undefined || leaseTerm == 'undefined') {
                        showError('Please select your leasing term.');
                        return;
                    }
                    if (leaseMiles == "" || leaseMiles == undefined || leaseMiles == 'undefined') {
                        showError('Please select your leasing miles/year.');
                        return;
                    }

                } else if (selected == 'financing') {
                    if (financeCredit == "" || financeCredit == undefined || financeCredit == 'undefined') {
                        showError('Please select your financing credit score.');
                        return;
                    }
                    if (financeTerm == "" || financeTerm == undefined || financeTerm == 'undefined') {
                        showError('Please select your financing term.');
                        return;
                    }

                }

            }
            pageProcessingShow();

            $.ajax({
                data: data,
                type: "POST",
                url: '{{ url('vehicle-detail') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).fail(function(jqXHR, status){

                pageProcessingHide();
                showError('Unable To Process Request. Try Again Or Skip.');
            }).done(function(rsp) {

                window.location.href = '{{ url('schedule-appointment') }}?user_token={{ $user_token }}';
            });


        }


        function showTradeReport(){
            var height = $(window).height()+'px';
            Swal.fire({
                width: '100%',
                html:
                    '<iframe height = "'+height+'" width="100%" src="' + tradein_search.url + '"></iframe>',
                showCloseButton: true,
                focusConfirm: false,


            })
        }

        function resetSearch(){
            $("#select2_body,#select2_drivetrain,#select2_engine,#select2_fuel").select2({
                data: [],
                disabled: false
            });


            $("#select2_search").val(null);
            $("#select2_search").empty().trigger('change');

            $(".display-modal-item-body").hide();
            $(".display-modal-item-drivetrain").hide();
            $(".display-modal-item-engine").hide();
            $(".display-modal-item-fuel").hide();
            $(".display-modal-item-mileage").hide();
            $(".display-modal-results-block").hide();
            $(".display-modal-item-report-button").hide();
        }


        /////////////////onready/////////////////////////////////////////////////////////////
        $(document).ready(function() {
            if ("{{$show_financing}}" == "true") {

                select2_search = $("#select2_search");
                select2_body = $("#select2_body");
                select2_drivetrain = $("#select2_drivetrain");
                select2_engine = $("#select2_engine");
                select2_fuel = $("#select2_fuel");

                select2_search.on('select2:select', function (e) {
                    tradein_search.text = e.params.data.text;
                    tradein_search.year = e.params.data.year;
                    tradein_search.make = e.params.data.make;
                    tradein_search.model = e.params.data.model;
                    tradein_search.trim = e.params.data.trim;
                    tradein_search.body = '';
                    tradein_search.drivetrain = '';
                    tradein_search.engine = '';
                    tradein_search.fuel = '';
                    $(".display-modal-item-body").hide();
                    $(".display-modal-item-drivetrain").hide();
                    $(".display-modal-item-engine").hide();
                    $(".display-modal-item-fuel").hide();
                    $(".display-modal-item-mileage").hide();
                    $(".display-modal-results-block").hide();
                    $(".display-modal-item-report-button").hide();
                    $("#select2_body,#select2_drivetrain,#select2_engine,#select2_fuel").select2({
                        data: [],
                        disabled: false
                    });

                    valueTradeVehicle();
                });

                select2_body.on('select2:select', function (e) {
                    tradein_search.body = e.params.data.text;

                    select2_body.select2({
                        //disabled: true
                    });
                    valueTradeVehicle();
                });

                select2_drivetrain.on('select2:select', function (e) {
                    tradein_search.drivetrain = e.params.data.text;

                    select2_drivetrain.select2({
                        //disabled: true
                    });
                    valueTradeVehicle();
                });

                select2_engine.on('select2:select', function (e) {
                    tradein_search.engine = e.params.data.text;

                    select2_engine.select2({
                        //disabled: true
                    });
                    valueTradeVehicle();
                });

                select2_fuel.on('select2:select', function (e) {
                    tradein_search.fuel = e.params.data.text;

                    select2_fuel.select2({
                        //disabled: true
                    });
                    valueTradeVehicle();
                });

                select2_search.select2({
                    width: 'resolve', // need to override the changed default
                    placeholder: 'Year Make Model',
                    closeOnSelect: true,
                    allowClear: true,
                    minimumInputLength: 4,
                    ajax: {
                        url: 'https://snap-api.tradepending.com/api/v4/ymmtsearch',
                        dataType: 'json',
                        data: function (params) {

                            var str = {
                                query: params.term,
                                result_count: 5,
                                country: 'US',
                                partner_id: 'LSTXvyra7CZgqCdYX'

                            }

                            // Query parameters will be ?search=[term]&type=public
                            return str;
                        },
                        processResults: function (data) {
                            //console.log(data);
                            let f = [];
                            $.each(data, function (i, o) {
                                //console.log(o);
                                f.push({
                                    id: (i + 1),
                                    text: o.year + ' ' + o.make + ' ' + o.model + ' ' + o.trim,
                                    year: o.year,
                                    make: o.make,
                                    model: o.model,
                                    trim: o.trim
                                });
                            });
                            //console.log(f);
                            // Transforms the top-level key of the response object from 'items' to 'results'
                            return {
                                results: f

                            };
                        }

                    },

                });


                select2_body.select2({
                    width: 'resolve', // need to override the changed default
                    placeholder: 'Select Body',
                    closeOnSelect: true
                });

                select2_drivetrain.select2({
                    width: 'resolve', // need to override the changed default
                    placeholder: 'Select Drivetrain',
                    closeOnSelect: true
                });

                select2_engine.select2({
                    width: 'resolve', // need to override the changed default
                    placeholder: 'Select Engine',
                    closeOnSelect: true
                });

                select2_fuel.select2({
                    width: 'resolve', // need to override the changed default
                    placeholder: 'Select Fuel Type',
                    closeOnSelect: true
                });







                //Financing type change
                $(document).on('click', '.cal_term,.cal_block_mobile_label', function(){
                    let type = $(this).attr('data-type');
                    let view = $(this).attr('data-view');
                    if(show_leasing == false && type == 'lease'){
                        showMessage('There are no available leasing options for this vehicle.');
                        return;
                    }



                    selected = type;

                    //Remove all selected classes
                    $('.cal_term').each(function(i, obj) {
                        $(this).removeClass('cal_term_selected');
                        $(this).find('.cal_term_label').removeClass('cal_term_label_selected');
                    });

                    //Remove all mobile selected classes
                    $('.cal_block_mobile_label').each(function(i, obj) {
                        $(this).removeClass('cal_mobile_selected');
                    });

                    $('.cal_term[data-type='+type+']').addClass('cal_term_selected');
                    $('.cal_term[data-type='+type+']').find('.cal_term_label').addClass('cal_term_label_selected');
                    $('.cal_block_mobile_label[data-type='+type+']').addClass('cal_mobile_selected');

                    if(type == 'finance'){
                        $(".credit_block").show();
                        $(".leasing_term_label").hide();
                        $(".lease_term_block_items").hide();
                        $(".mileage_block").hide();
                        $(".financing_term_label").show();
                        $(".term_block_items").show();
                        $(".downpayment-block").show();
                    }else if(type == 'lease'){
                        $(".credit_block").show();
                        $(".financing_term_label").hide();
                        $(".term_block_items").hide();
                        $(".leasing_term_label").show();
                        $(".lease_term_block_items").show();
                        $(".mileage_block").show();
                        $(".downpayment-block").show();
                    }else{
                        $(".leasing_term_label").hide();
                        $(".lease_term_block_items").hide();
                        $(".financing_term_label").hide();
                        $(".term_block_items").hide();
                        $(".mileage_block").hide();
                        $(".credit_block").hide();
                        $(".downpayment-block").hide();
                    }

                    updatePayment();

                });

                $(document).on('click', '.term_item', function(){
                    let type = $(this).attr('data-type');
                    let value = $(this).attr('data-value');
                    if(type == 'finance'){
                        finance_term_selected = value;
                    }else{
                        lease_term_selected = value;
                    }

                    $.each($('.term_item[data-type='+type+']'), function(i,obj){
                        $(this).removeClass('item_selected');
                    });
                    $(this).addClass('item_selected');

                    updatePayment();

                });

                $(document).on('click', '.credit_item', function(){
                    let type = $(this).attr('data-type');
                    credit_selected = type;
                    $('.credit_item').removeClass('item_selected');
                    $(this).addClass('item_selected');
                    updatePayment();
                })

                $(document).on('click', '.mileage_item', function(){
                    let value = $(this).attr('data-value');
                    if(value == mileage_selected)return;
                    mileage_selected = value;
                    $('.mileage_item').removeClass('item_selected');
                    $(this).addClass('item_selected');
                    updatePayment();
                });



                if(selected == 'lease'){
                    $('.cal_term[data-type=lease]').trigger('click');
                }else{
                    updatePayment();
                }


                // Get the modal
                modal = document.getElementById("myModal");

// Get the button that opens the modal
                var btn = document.getElementById("launchDisplayModal");

// Get the <span> element that closes the modal
                var span = document.getElementsByClassName("display-close")[0];

// When the user clicks on the button, open the modal
                btn.onclick = function () {
                    modal.style.display = "block";
                }

// When the user clicks on <span> (x), close the modal
                span.onclick = function () {
                    modal.style.display = "none";
                    resetSearch();
                }

                if(parseInt(tradein) > 0 || parseInt(payoff) > 0){
                    $('#removeTrade').show();
                }

                $(document).on('click', '#removeTrade', function() {
                    processingShow();
                    var _t = '{{ $user_token }}';
                    var _url = "{{ url('remove-trade') }}?user_token="+_t;
                    $.get(_url)
                        .fail(function(){
                            processingHide();
                            showError('Unable to remove Trade-in.  Please Try again.');

                        }).done(function(){
                        processingHide();
                        resetSearch();
                        tradein_search =  {id: 0, text: '', year: '', make: '', model: '', trim: '', body: '', drivetrain: '', engine: '', fuel: '', mileage: 0, payoff: 0, tradein: 0};
                        payoff=0;
                        tradein=0;
                        $('.tradein_value').html("$0");
                        $('.payoff_value').html("$0");
                        updatePayment();
                        showMessage('Your Trade-In has been removed and loan re-calculated.');
                        $('#removeTrade').hide();
                    });
                });


            } else {

            }

            $(document).on('click', '.fa-question-circle', function(){
                let type = $(this).attr('data-type');
            });

            $(document).on('click', '.about_label', function() {
                let type = $(this).attr('data-type');
                $('.key_features_block').hide();
                $('.extra_features_block').hide();
                $('.info_block').hide();

                //Remove all selected classes
                $('.about_label').each(function (i, obj) {
                    $(this).removeClass('about_selected');
                });
                $(this).addClass('about_selected');
                if(type == 'key_features')$('.key_features_block').show();
                if(type == 'extra_features')$('.extra_features_block').show();
                if(type == 'info')$('.info_block').show();



            })



// When the user clicks anywhere outside of the modal, close it
//             window.onclick = function(event) {
//                 if (event.target == modal) {
//                     modal.style.display = "none";
//                 }
//             }
/////////////////////////////////////////////////////////////////////////////////////
        });



    </script>
@endsection
