@extends('layout')

@section('page-css')
<link rel="stylesheet" href="{{ asset('js/jqueryui/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/vehicle.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}" />

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('js/range-slider/style.css') }}">

@endsection

@section('top-nav-item')
<h3><i class="fa fa-user"></i>SELECT A VEHICLE</h3>
@endsection

@section('footer-btns')
<div class="back">
 <a class="btn btn-primary back-btn" href="{{ url('/start-sales-person?user_token=' . $user_token) }}">Back</a>
</div>
<div class="bot-buttons">
    <a class="btn btn-primary skip-btn vehicle_skip" href="{{ url('/value-trade?user_token=' . $user_token) }}">SKIP</a>
</div>
@endsection

@section('footer')
@include('includes.footer')
@endsection

@section('content')


<!--
<div class="col-md-12">
    <div class="vehicle-info">
        <div class="vehicle-info-header">
            <h2><span class="filter-mobile" style = "cursor:pointer;font-size: 20px;color: #039BE5;float:right"><i style = "font-size: 20px;color: #039BE5;" class="fas fa-bars"></i>&nbsp;Filters</span></h2>

                    @if(isset($salesperson) && !empty($salesperson))
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






            <p>Select a Vehicle from Below to Continue</p>
        </div>
    </div>
</div>
-->
<!--
<div class="col-md-12 vehicle_mobile">

    <div class = "row">
        <div class = "col-md-12 search_filters">




        </div>
    </div>


    <div class = "row">
        <div  class = "col-md-12">
            <div style="display: grid;grid-template-columns: 60% 20% 20%;justify-content: space-between;">
                <div style = "display: grid;grid-template-columns: 45px 1fr 1fr 1fr 1fr;" id="breadcrumbs">

                </div>
                <div>
                    <div style = "font-weight: 700;font-size: 13px;" class = "col-md-3">{{$filters->total_vehicles}} VEHICLES</div>
                </div>
                <div>
                    <div style = "margin-right: 5px;font-size: 12px;font-weight: 700;margin-top: 6px;">Sort:</div>
                    <div class="">
                        <select style = "padding:0px;max-height: 30px;font-size: 12px;" onchange="submitFormEvent()" id="sortBy" name="sortBy" class="form-control">
                            <option value="" selected>--Select--</option>
                            @if(Request::get('sortBy') == 'make_asc')
                                <option value="make_asc" selected>Make: A to Z</option>
                            @else
                                <option value="make_asc">Make: A to Z</option>
                            @endif
                            @if(Request::get('sortBy') == 'make_desc')
                                <option value="make_desc" selected>Make: Z to A</option>
                            @else
                                <option value="make_desc">Make: Z to A</option>
                            @endif
                            @if(Request::get('sortBy') == 'price_desc')
                                <option value="price_desc" selected>Price: Highest First</option>
                            @else
                                <option value="price_desc">Price: Highest First</option>
                            @endif
                            @if(Request::get('sortBy') == 'price_asc')
                                <option value="price_asc" selected>Price: Lowest First</option>
                            @else
                                <option value="price_asc">Price: Lowest First</option>
                            @endif
                            @if(Request::get('sortBy') == 'year_desc')
                                <option value="year_desc" selected>Year: Highest First</option>
                            @else
                                <option value="year_desc">Year: Highest First</option>
                            @endif
                            @if(Request::get('sortBy') == 'year_asc')
                                <option value="year_asc" selected>Year: Lowest First</option>
                            @else
                                <option value="year_asc">Year: Lowest First</option>
                            @endif

                        </select>
                    </div>
                </div>
            </div>


        </div>


    </div>
    <div class = "row">

        <div class = "col-md-12">
            <div style = "margin-bottom:5px;padding:5px;width:100%;border-bottom: 1px solid #dee2e6;"></div>
        </div>
    </div>
    <div id="vehicles_block" class="row">


        @include('wizard.pages.partials.vehicles')

    </div>
</div>
-->





<div class="filter_block">
    @include('wizard.pages.partials.vehicle-filters')
</div>




<div style = "margin-top:10px;padding-left:45px" class="col-md-12 vehicle_display">

    <div class="row">
        <div class="col-md-12">
            <div style="font-size: 24px;margin-bottom:5px; margin-top: 10px;font-weight: 600;" id="vehicle_total">
                {{$filters->total_vehicles}} Vehicles Available
            </div>
        </div>
    </div>

    <div class = "row">
        <div class="col-md-3" style="margin-bottom:10px">
            <div style="display: -webkit-inline-box;">
                <input  id="v_search"  class = "form-control" style = "box-sizing: border-box;width: 100%;height: 30px;background: #FFFFFF;border: 1px solid #CED8DF;border-radius: 20px;" type = "text" placeholder="vehicle search">

                <i onclick="keywordSearch()" style="color: white;position: relative;right: 27px;border-left: 1px solid white;padding: 5px;cursor: pointer;height: 30px;margin-top: 0px;background-color:#039BE5;border-bottom-right-radius: 10px;border-top-right-radius: 10px;" class="fas fa-search"></i>

            </div>
        </div>

        <div class="col-md-7" style="margin-bottom:10px">
            <div style = "display: table;justify-items: center;" class="breadcrumbs">

            </div>
        </div>

        <div class="col-md-2" style="margin-bottom:10px">
            <select style = "max-width:150px;padding:0px;max-height: 35px;font-size: 12px;" onchange="submitFormEvent()" id="sortBy" name="sortBy" class="form-control">
                <option value="" selected>--Select--</option>
                @if(Request::get('sortBy') == 'make_asc')
                    <option value="make_asc" selected>Make: A to Z</option>
                @else
                    <option value="make_asc">Make: A to Z</option>
                @endif
                @if(Request::get('sortBy') == 'make_desc')
                    <option value="make_desc" selected>Make: Z to A</option>
                @else
                    <option value="make_desc">Make: Z to A</option>
                @endif
                @if(Request::get('sortBy') == 'price_desc')
                    <option value="price_desc" selected>Price: Highest First</option>
                @else
                    <option value="price_desc">Price: Highest First</option>
                @endif
                @if(Request::get('sortBy') == 'price_asc')
                    <option value="price_asc" selected>Price: Lowest First</option>
                @else
                    <option value="price_asc">Price: Lowest First</option>
                @endif
                @if(Request::get('sortBy') == 'year_desc')
                    <option value="year_desc" selected>Year: Highest First</option>
                @else
                    <option value="year_desc">Year: Highest First</option>
                @endif
                @if(Request::get('sortBy') == 'year_asc')
                    <option value="year_asc" selected>Year: Lowest First</option>
                @else
                    <option value="year_asc">Year: Lowest First</option>
                @endif

            </select>

        </div>


    </div>


    <div class = "row">

        <div class="col-md-12">

            <div class = "row">
                <div class = "col-md-12 search_filters">




                </div>
            </div>


            <div class = "row">

                <div class = "col-md-12">
                    <div style = "margin-bottom:5px;padding:5px;width:100%;border-bottom: 1px solid #dee2e6;"></div>
                </div>
            </div>

            <div id="vehicles_block">
                @include('wizard.pages.partials.vehicles')
            </div>

        </div>
    </div>
</div>







@endsection

@section('page-js')
<script src="{{ asset('js/jqueryui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/range-slider/rangeslider.umd.min.js') }}"></script>
<script src="{{ asset('js/searchFilter.js') }}"></script>


<script>
let html_received = null;
let filters_received = null;
let processing_interval = null;
let page = 1;
let loan_condition = 'cash';
let financing_json_format = String('{{$financing_json}}');
var financing_obj = JSON.parse(financing_json_format.replace(/&quot;/g, '"'));
let leasing_json_format = String('{{$leasing_json}}');
var leasing_obj = JSON.parse(leasing_json_format.replace(/&quot;/g, '"'));
var is_offerlogix = '{{$is_offerlogix}}';
var high_price = '{{$filters->high_price}}';
var low_price = '{{$filters->low_price}}';
var high_year = '{{$filters->high_year}}';
var low_year = '{{$filters->low_year}}';
var high_mileage = '{{$filters->high_mileage}}';
var low_mileage = '{{$filters->low_mileage}}';
var clear_filters_clicked = false;

const breadcrumb_filter_template = `<div style = "margin-right: 5px;font-size: 12px;font-weight: 700;margin-top: 6px;">Filters:</div>`;
const breadcrumb_item_template = `<div style = "max-height:25px;margin-right: 5px;box-shadow: 1px 1px 1px 1px #0000002e;border: 1px solid #039BE5;width: fit-content;padding: 5px;font-size: 10px;font-weight: 700;border-radius: 20px;">
                        Ford <i  onclick ="deleteBreadcrumb('make')" style="cursor: pointer;color: #039BE5;" class="fas fa-times-circle"></i>
                    </div><div style = "max-height:25px;margin-right: 5px;box-shadow: 1px 1px 1px 1px #0000002e;border: 1px solid #039BE5;width: fit-content;padding: 5px;font-size: 10px;font-weight: 700;border-radius: 20px;">
                        $0 - $60,000 <i  onclick ="deleteBreadcrumb('make')" style="cursor: pointer;color: #039BE5;" class="fas fa-times-circle"></i>
                    </div>`;

Tracking = {
    user_token: '{{$user_token}}',
    page: '{{$currentPage}}'

};

$(document).ready(function() {

    $('.mobile-menu-icon-filter').click(function() {
        console.log($('.filter_block').css("left"));
        if($('.filter_block').css("left") == "0px"){
          //  $('.filter_block').css("left", "-3000!important;");
            $('.filter_block').attr("style", "left: -3000 !important;");
        }else{
            $('.filter_block').attr("style", "left: 0 !important;");
        }
    });


    $('.closebtn-filter').click(function(){
        $('.filter_block').attr("style", "left: -3000 !important;");
    });

});

function submitFormEvent(){
    page = 1;
    $( "#filterForm" ).submit();
}

function submitFormEventPaginate(){
    $( "#filterForm" ).submit();
}

function submitForm(event){
    event.preventDefault();
    processingShow();

    var data = {};
    data['user_token'] = $('#user_token').val();
    data['sortBy'] = $('#sortBy').val();



    if(!clear_filters_clicked){
        data['v_condition'] = searchFilter.getSelectedFilters(searchUI.conditionsCheckboxGroup);
        data['v_make'] = searchFilter.getSelectedFilters(searchUI.makesCheckboxGroup);
        data['v_model'] = searchFilter.getSelectedFilters(searchUI.modelsCheckboxGroup);
        data['v_engine'] = searchFilter.getSelectedFilters(searchUI.enginesCheckboxGroup);
        data['v_transmission'] = searchFilter.getSelectedFilters(searchUI.transmissionsCheckboxGroup);
        data['v_drivetrain'] = searchFilter.getSelectedFilters(searchUI.drivetrainsCheckboxGroup);

        if($('#year_end_input').val() != low_year){
            data['v_year_to'] = $('#year_end_input').val();
            data['v_year_from'] = $('#year_start_input').val();
        }


        if($('#price_end_input').val() > 0 && $('#price_end_input').val() != low_price){
            data['v_price_range'] = $('#price_start_input').val()+'-'+$('#price_end_input').val();
        }

        if($('#mileage_end_input').val() != low_mileage){
            data['v_miles_range'] = $('#mileage_start_input').val()+'-'+$('#mileage_end_input').val();
        }
        data['loan_condition'] = loan_condition;
        data['loan_payment'] = $('#monthly_payment_input').val();
      
        if($.trim($('#v_search').val())  != '' && $.trim($('#v_search').val()) != undefined ){
            data['v_search'] = $('#v_search').val();
        }
    }


    if(page != 1){
        data['p'] = page;
    }


    var str = '';
    for (const key in data) {
        str = str + key+'='+data[key]+'&';
    }

    html_received = false;
    filters_received = false;

    if(page != 1){
        filters_received = true;
    }

    processing_interval = setInterval(function(){
        if(html_received && filters_received){
            clearInterval(processing_interval);
            $('html, body').animate({
                scrollTop: $("#vehicles_block").offset().top
            }, 1000);
            processingHide();
        }
    }, 200, html_received, filters_received);


    if(page != 1){
        $.ajax({
            data: data,
            type: "POST",
            dataType: "html",
            url: "{{ url('get-vehicles-html') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(response) {

            $('#vehicles_block').html(response);
            html_received = true;
        });



    }else{

        $.ajax({
            data: data,
            type: "POST",
            dataType: "html",
            url: "{{ url('get-vehicles-html') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(response) {

            $('#vehicles_block').html(response);
            html_received = true;
        });



        $.ajax({
            data: data,
            type: "POST",
            dataType: "json",
            url: "{{ url('vehicle-filter') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(response) {

            var filters = response.data.filters;
            if(is_offerlogix == '1'){

                financing_obj = JSON.parse(response.data.financing_json);
                leasing_obj = JSON.parse(response.data.leasing_json);

                if(response.data.filters.loanCondition == 'finance'){
                    updateLoanCondition(financing_obj);
                }else{
                    updateLoanCondition(leasing_obj);
                }

                $.each($('#loan_condition option'),function(i,o){
                    if($(this).val() == response.data.filters.loanConditionValue){
                        $('#loan_condition option:eq('+i+')').prop('selected', true);
                    }
                });

            }

            searchFilter.updateMakesFilter(filters.brands);
            searchFilter.updateModelsFilter(filters.models);
            searchFilter.updateEnginesFilter(filters.engines);
            searchFilter.updateTransmissionsFilter(filters.transmissions);
            searchFilter.updateDrivetrainsFilter(filters.drivetrains);


            $('#vehicle_total').html(filters.total_vehicles+' Vehicles');

            if(rsp.value()[1] == low_price){
                rsp.value([low_price, high_price]);
            }

            if(rsy.value()[1] == low_year){
                rsy.value([low_year, high_year]);
            }

            if(rsm.value()[1] == low_mileage){
                rsm.value([low_mileage, high_mileage]);
            }

            if(clear_filters_clicked){
                rsp.value([low_price, high_price]);
                rsy.value([low_year, high_year]);
                rsm.value([low_mileage, high_mileage]);
            }

            searchFilter.buildBreadCrumbs();
            clear_filters_clicked = false;
            filters_received = true;


        });

    }

    Tracking['type'] = 'vehicle_filter_event';
    Tracking['env'] = 'app';
    Tracking['info'] = 'Srp filtering';
    sendTracking();

}


function updateMilesRanges(arr){
    var selected = $('#v_miles_range').val();
    $('#v_miles_range').empty().append('<option value="all">All</option>');

    for (const key in arr) {
        $('#v_miles_range').append('<option value="'+key+'">'+arr[key].low+' - '+arr[key].high+' ('+arr[key].count+')</option>');
    }
    $('#v_miles_range').val(selected);

}

function updatePricesRanges(arr){
    var selected = $('#v_price_range').val();
    $('#v_price_range').empty().append('<option value="all">All</option>');

    for (const key in arr) {
        $('#v_price_range').append('<option value="'+key+'">'+arr[key].low+' - '+arr[key].high+' ('+arr[key].count+')</option>');
    }
    $('#v_price_range').val(selected);

}

function updateYears(arr){
    var selected = $('#v_year').val();
    $('#v_year').empty().append('<option value="all">All</option>');

    for (const key in arr) {
        $('#v_year').append('<option value="'+key+'">'+key+' ('+arr[key].count+')</option>');
    }
    $('#v_year').val(selected);

}


function updateModels(arr){
    var selected = $('#v_model').val();
    $('#v_model').empty().append('<option value="all">All</option>');

    for (const key in arr) {
        $('#v_model').append('<option value="'+arr[key].id+'">'+key+' ('+arr[key].count+')</option>');
    }

    $('#v_model').val(selected);
}

function paginate(i){

    if(!isNaN(i)){
        page = i;
        submitFormEventPaginate();
    }

}

function loanCondition(type){
    $('#loan_finance').addClass('loan_button');
    $('#lease_finance').addClass('loan_button');
    $('#cash_finance').addClass('loan_button');
    $('#monthly_payment_block').hide();
    $('#cash_block').hide();

    if(type == 'finance'){
        loan_condition = "finance";
        $('#loan_finance').removeClass('loan_button');
        $('#monthly_payment_block').show();
       // updateLoanCondition(financing_obj);
    }else if(type == 'lease'){
        loan_condition = "lease";
        $('#lease_finance').removeClass('loan_button');
        $('#monthly_payment_block').show();
        //updateLoanCondition(leasing_obj);
    }else{
        loan_condition = "cash";
        $('#cash_finance').removeClass('loan_button');
        $('#cash_block').show();
    }
    submitFormEvent();
}

function updateLoanCondition(obj){
    $('#loan_condition').empty();
    $('#loan_condition').append(`<option value = "all">All</option>`);
    $.each(obj, function(i, o){
        $('#loan_condition').append('<option value = "'+i+'">'+i+' ('+o.count+')</option>');
    })
}

let rs;
let rsy;
let rsp;
let rsm;

function updatePriceRange(){
    let start = parseInt($('#price_start_input').val());
    let end = parseInt($('#price_end_input').val());
    if( (!isNaN(start)) &&  (!isNaN(end))  ){
        $('#year_range_alert').hide();
        rsp.value([start,end]);
        submitFormEvent();
    }else{
        $('#price_range_alert').show();
    }

}

function updatePaymentRange(){
    let payment = parseInt($('#monthly_payment_input').val());
    console.log(payment+ '  - first');
    if(!isNaN(payment) && payment.toString().length > 2){
        $('#monthly_payment_alert').hide();
        rs.value([0,payment]);
        submitFormEvent();
    }else{
        $('#monthly_payment_alert').show();
    }

}

function updateYearRange(){
    let start = parseInt($('#year_start_input').val());
    let end = parseInt($('#year_end_input').val());
    if( (!isNaN(start) && start.toString().length == 4) &&  (!isNaN(end) && end.toString().length == 4)  ){
        $('#year_range_alert').hide();
        rsy.value([start,end]);
        submitFormEvent();
    }else{
        $('#year_range_alert').show();
    }

}

function updateMileageRange(){
    let start = parseInt($('#mileage_start_input').val());
    let end = parseInt($('#mileage_end_input').val());
    if( !isNaN(start) &&  !isNaN(end)   ){
        $('#mileage_range_alert').hide();
        rsm.value([start,end]);
        submitFormEvent();
    }else{
        $('#year_range_alert').show();
    }

}

    $(document).ready(function() {



       rs = rangeSlider(document.querySelector('#range-slider-payment'), {
            value: [2000],
            max: 2000,
            min: 0,
            step: 100,
           thumbsDisabled: [true, false],
           rangeSlideDisabled: true,
            onThumbDragEnd: function(){
                submitFormEvent();
            },
           onInput: function(){
               $('#monthly_payment_input').val(rs.value()[1]);
           }
       });

       rs.value([0,2000]);

        rsy = rangeSlider(document.querySelector('#range-slider-year'), {
            value: [low_year, high_year],
            step: 1,
            min: low_year,
            max: high_year,
            thumbsDisabled: [false, false],
            rangeSlideDisabled: false,
            onThumbDragEnd: function(){
                submitFormEvent();
            },
            onInput: function(){
                $('#year_start_input').val(rsy.value()[0]);
                $('#year_end_input').val(rsy.value()[1]);
            }

        });


        rsp = rangeSlider(document.querySelector('#range-slider-price'), {
            value: [low_price,high_price],
            step: 1000,
            min: low_price,
            max: high_price,
            thumbsDisabled: [false, false],
            rangeSlideDisabled: false,
            onThumbDragEnd: function(){
                //$('#price_start_input').val(rsp.value()[0]);
                //$('#price_end_input').val(rsp.value()[1]);
                submitFormEvent();
            },
            onInput: function(){
                $('#price_start_input').val(rsp.value()[0]);
                $('#price_end_input').val(rsp.value()[1]);
            }

        });

        rsm = rangeSlider(document.querySelector('#range-slider-mileage'), {
            value: [low_mileage, high_mileage],
            step: 1,
            min: low_mileage,
            max: high_mileage,
            thumbsDisabled: [false, false],
            rangeSlideDisabled: false,
            onThumbDragEnd: function(){
                submitFormEvent();
            },
            onInput: function(){
                $('#mileage_start_input').val(rsm.value()[0]);
                $('#mileage_end_input').val(rsm.value()[1]);
            }

        });




        if('1' == is_offerlogix){
            $('#cash_finance').removeClass('loan_button');
            //updateLoanCondition(financing_obj);
        }

        $(document).on('change', '#ex6', function(e) {
            var id = e.target.value;
            document.getElementById("ex6Val").innerHTML = id;
            $(this).prop('max', 100);
        });

        //Tracking Navigation exit finish later
        $('.skip-btn').click(function(event){




        //var url = $(this).prop('href');
        Tracking['type'] = 'navigation';
        Tracking['env'] = 'app';
        Tracking['info'] = 'link click skip button';
        sendTracking();
        // window.location.href = url;
        });

        $('.vehicle-link').click(function(){

        Tracking['type'] = 'vehicle_changed';
        Tracking['env'] = 'app';
        Tracking['info'] = 'vehicle changed';
        sendTracking();

        });

        $('.vehicle_skip').click(function(){

        Tracking['type'] = 'vehicle_skipped';
        Tracking['env'] = 'app';
        Tracking['info'] = 'vehicle skipped';
        sendTracking();

        });




        $('.filter-mobile').click(function(){
                $(".vehicle-section").toggle();
        });

        searchFilter.buildBreadCrumbs();


    });
    function clearFilters()
    {
        page=1;
        $('#v_search').val('');
        searchFilter.clearFilters();
        clear_filters_clicked = true;
        loanCondition('cash');

        //submitFormEvent();
    }

    function keywordSearch(){
        if($.trim($('#v_search').val())  == '' || $.trim($('#v_search').val()) == undefined ){
            return;
        }
        submitFormEvent();
    }

</script>
@endsection
