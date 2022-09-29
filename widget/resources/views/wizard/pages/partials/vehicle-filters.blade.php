<div  class="closebtn-filter" >&times;</div>
<div class = "desktop_filter" style="max-width:220px">
<form method="GET" id="filterForm" style="margin-top:12px" class="filterForm" onsubmit="submitForm(event)">
    <input type="hidden" id="user_token" name="user_token" value="{{ $user_token }}">
<div class="row">
    <div class = "col-md-12">
        <div class="">
            <p  style="float: left;font-size: 20px;font-weight: 700;" class="" >Filters</p>
            <a onclick="clearFilters()" href="javascript:void(0)" style="float: right;color: #039BE5;font-size: 13px;font-weight: 700; margin-top: 7px;">clear all</a>
        </div>

    </div>

</div>


<div class = "row">
    <div class="col-md-12">
        <div style = "margin-bottom:10px" class="condition-filter">
            @if($is_offerlogix)
            <div style = "display: flex;margin-top: -6px;margin-bottom: 2px;">
                <div class="back" style=" line-height: 20px;padding-right:5px">
                    <div style = "font-size: 12px;font-weight: 700" id="cash_finance" onclick="loanCondition('cash')" class="btn btn-primary back-btn loan_button">Cash</div>
                </div>
                <div class="back" style=" line-height: 20px; padding-right:5px">
                    <div style = "font-size: 12px;font-weight: 700" id="loan_finance"  onclick="loanCondition('finance')" class="btn btn-primary back-btn loan_button" >Finance</div>
                </div>
                <div class="back" style=" line-height: 20px;">
                    <div style = "font-size: 12px;font-weight: 700" id="lease_finance" onclick="loanCondition('lease')" class="btn btn-primary back-btn loan_button">Lease</div>
                </div>

            </div>
            @endif

            <div style = "display: none" id = "monthly_payment_block">
                <div   style ="display: inline-flex; margin-top:10px; margin-bottom: 15px">
                    <div style = "padding-right: 5px;font-size: 12px; font-weight: 700; padding-top: 3px">
                        Monthly Payment &nbsp; <
                    </div>
                    <div>
                        <input onchange="updatePaymentRange()" id="monthly_payment_input" maxlength="4" class = "form-control" style = "    font-size: 12px;max-height: 25px;max-width: 65px;" type = "number" value = "500">
                    </div>

                    <div style = "display:none;font-size:10px;font-weight: 700" id = "monthly_payment_alert"  class="alert alert-danger" role="alert">
                        Monthly payment must be greater than $100
                    </div>



                </div>

                <div style = "" id="range-slider-payment" >

                </div>

            </div>




            <div style = "margin-top:10px;display: block" id = "cash_block">
                <div style="display:inline-flex">
                    <div style = "padding-right: 5px;font-size: 12px; font-weight: 700; padding-top: 10px">
                        Price
                    </div>
                    <div style ="display: inline-flex; margin-top:5px; margin-bottom: 15px">

                        <div>
                            <input onchange="updatePriceRange()"  id="price_start_input" maxlength="4" class = "form-control" style = "padding:6px;font-size: 12px;max-height: 25px;max-width: 65px;" type = "number" value = "{{$filters->low_price}}">
                        </div>
                        <div style = "margin: 3px">
                            -
                        </div>
                        <div>
                            <input onchange="updatePriceRange()" id="price_end_input" maxlength="4" class = "form-control" style = "  padding:6px;  font-size: 12px;max-height: 25px;max-width: 65px;" type = "number" value = "{{$filters->high_price}}">
                        </div>

                    </div>
                </div>

                <div style = "display:none;font-size:10px;font-weight: 700" id = "price_range_alert"  class="alert alert-danger" role="alert">
                    Enter in a valid price
                </div>

                <div id="range-slider-price">

                </div>
            </div>



           <!-- <select onchange="submitFormEvent()" id="loan_condition" name="loan_condition" class="form-control">

            </select>-->
        </div>
    </div>
</div>


<div class = "row">

    <div class = "col-md-12">
        <div style = "margin-bottom:10px;padding:5px;width:100%;border-bottom: 1px solid #dee2e6;"></div>
    </div>
</div>

    <div class = "row">
        <div class = "col-md-12">
            <ul style = "padding: 0;">
                <li style = "" class="list-group-item search_nav_list" data-type = "condition">
                    <div class = "list_header" style="margin-bottom:5px;width:100%;font-weight: 700;" >Condition </div>
                    <ul class = "search_nav_list_child" style = "display: block">

                        <li style = "list-style: none;">
                            <label style = "cursor:pointer;margin-bottom: 0px">
                                <input onclick="submitFormEvent()"  data-group="condition_chkbox_grp" class="contact-box"  data-alias="Condition" data-display="New" value="new" name="condition_chkbox_grp" type="checkbox">
                                <span style = "font-size: 13px;font-weight: 600;">{{$filters->conditions['new']}}</span>
                            </label>
                        </li>
                        <li style = "list-style: none;">
                            <label style = "cursor:pointer;margin-bottom: 0px">
                                <input onclick="submitFormEvent()"  data-group="condition_chkbox_grp" class="contact-box"  data-alias="Condition" data-display="Used" value="used" name="condition_chkbox_grp" type="checkbox">
                                <span style = "font-size: 13px;font-weight: 600;">{{$filters->conditions['used']}}</span>
                            </label>
                        </li>
                        <li style = "list-style: none;">
                            <label style = "cursor:pointer;margin-bottom: 0px">
                                <input  onclick="submitFormEvent()" data-group="condition_chkbox_grp" class="contact-box"  data-alias="Condition" data-display="Certified" value="certified" name="condition_chkbox_grp" type="checkbox">
                                <span style = "font-size: 13px;font-weight: 600;">{{$filters->conditions['certified']}}</span>
                            </label>
                        </li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>

<div class = "row">
    <div class="col-md-12">

        @if(!empty($filters->brands))
            <ul style = "padding: 0;">
                <li style = "" class="list-group-item search_nav_list" data-type = "makes">
                    <div class = "list_header" style="font-size:12px;margin-bottom:5px;width:100%;font-weight: 700;" >Make </div>
                    <ul class = "search_nav_list_child search_nav_make_list" style = "display: none">
                        @foreach($filters->brands as $make => $make_info)

                            <li class = "" style = "list-style: none;">

                                @if(Request::has('v_make') &&  Request::get('v_make') != 'all' && Request::get('v_make') == $make_info['id'] && strlen(Request::get('v_make')) > 0)

                                    <label style = "cursor:pointer;margin-bottom: 0px">
                                        <input onclick="submitFormEvent()" data-group="make_chkbox_grp" class="contact-box" checked data-alias="Make" data-display="{{$make}}" value="{{$make_info['id']}}" name="make_chkbox_grp" type="checkbox">
                                        <span style = "font-size: 13px;font-weight: 600;">{{ $make }} ({{$make_info['count']}})</span>
                                    </label>


                                @else

                                    <label style = "cursor:pointer;margin-bottom: 0px">
                                        <input onclick="submitFormEvent()" data-group="make_chkbox_grp" class="contact-box"  data-alias="Make" data-display="{{$make}}" value="{{$make_info['id']}}" name="make_chkbox_grp" type="checkbox">
                                        <span style = "font-size: 13px;font-weight: 600;">{{ $make }} ({{$make_info['count']}})</span>
                                    </label>

                                @endif


                            </li>
                        @endforeach

                    </ul>
                </li>

                <li style = "" class="list-group-item search_nav_list" data-type = "models">
                    <div class = "list_header" style="font-size:12px;margin-bottom:5px;width:100%;font-weight: 700;" >Model </div>
                    <ul class = "search_nav_list_child search_nav_model_list" style = "display: none">
                        @php
                            $makes_array = [];
                        @endphp
                        @foreach($filters->models as $model => $model_info)

                            @if(!in_array($model_info['make'], $makes_array))
                                <li style = "list-style: none;color: #0000007d;font-weight: 800;font-size: 12px;padding-top:5px">{{$model_info['make']}}</li>
                                @php
                                    $makes_array[] = $model_info['make'];
                                @endphp
                            @endif

                            <li style = "list-style: none;padding-left: 15px;">

                                @if(Request::has('v_model') && Request::get('v_model') != 'all' && Request::get('v_model') == $model_info['id'] && strlen(Request::get('v_model')) > 0)

                                    <label style = "cursor:pointer;margin-bottom: 0px">
                                        <input onclick="submitFormEvent()" data-group="model_chkbox_grp" class="contact-box" checked data-alias="Model" data-display="{{$model}}" value="{{$model_info['id']}}" name="model_chkbox_grp" type="checkbox">
                                        <span style = "font-size: 13px;font-weight: 600;">{{ $model }} ({{$model_info['count']}})</span>
                                    </label>


                                @else

                                    <label style = "cursor:pointer;margin-bottom: 0px">
                                        <input onclick="submitFormEvent()" data-group="model_chkbox_grp" class="contact-box"  data-alias="Model" data-display="{{$model}}" value="{{$model_info['id']}}" name="model_chkbox_grp" type="checkbox">
                                        <span style = "font-size: 13px;font-weight: 600;">{{ $model }} ({{$model_info['count']}})</span>
                                    </label>

                                @endif


                            </li>
                        @endforeach

                    </ul>
                </li>


                <li style = "" class="list-group-item search_nav_list" data-type = "engines">
                    <div class = "list_header" style="font-size:12px;margin-bottom:5px;width:100%;font-weight: 700;" >Engine </div>
                    <ul class = "search_nav_list_child search_nav_engine_list" style = "display: none">
                        @php
                            $engines_array = [];
                        @endphp
                        @foreach($filters->engines as $engine => $engine_info)


                            <li style = "list-style: none;padding-left: 15px;">



                                    <label style = "cursor:pointer;margin-bottom: 0px">
                                        <input onclick="submitFormEvent()" data-group="engine_chkbox_grp" class="contact-box"  data-alias="Engine" data-display="{{$engine}}" value="{{$engine}}" name="engine_chkbox_grp" type="checkbox">
                                        <span style = "font-size: 13px;font-weight: 600;">{{ $engine }} ({{$engine_info['count']}})</span>
                                    </label>



                            </li>
                        @endforeach

                    </ul>
                </li>


                <li style = "" class="list-group-item search_nav_list" data-type = "transmissions">
                    <div class = "list_header" style="font-size:12px;margin-bottom:5px;width:100%;font-weight: 700;" >Transmission </div>
                    <ul class = "search_nav_list_child search_nav_transmission_list" style = "display: none">
                        @php
                            $transmissions_array = [];
                        @endphp
                        @foreach($filters->transmissions as $transmission => $transmission_info)


                            <li style = "list-style: none;padding-left: 15px;">



                                <label style = "cursor:pointer;margin-bottom: 0px">
                                    <input onclick="submitFormEvent()" data-group="transmission_chkbox_grp" class="contact-box"  data-alias="Transmission" data-display="{{$transmission}}" value="{{$transmission}}" name="transmission_chkbox_grp" type="checkbox">
                                    <span style = "font-size: 13px;font-weight: 600;">{{ $transmission }} ({{$transmission_info['count']}})</span>
                                </label>



                            </li>
                        @endforeach

                    </ul>
                </li>

                <li style = "" class="list-group-item search_nav_list" data-type = "drivetrain">
                    <div class = "list_header" style="font-size:12px;margin-bottom:5px;width:100%;font-weight: 700;" >Drivetrain </div>
                    <ul class = "search_nav_list_child search_nav_drivetrain_list" style = "display: none">
                        @php
                            $drivetrains_array = [];
                        @endphp
                        @foreach($filters->drivetrains as $drivetrain => $drivetrain_info)


                            <li style = "list-style: none;padding-left: 15px;">



                                <label style = "cursor:pointer;margin-bottom: 0px">
                                    <input onclick="submitFormEvent()" data-group="drivetrain_chkbox_grp" class="contact-box"  data-alias="Drivetrain" data-display="{{$drivetrain}}" value="{{$drivetrain}}" name="drivetrain_chkbox_grp" type="checkbox">
                                    <span style = "font-size: 13px;font-weight: 600;">{{ $drivetrain }} ({{$drivetrain_info['count']}})</span>
                                </label>



                            </li>
                        @endforeach

                    </ul>
                </li>


            </ul>
        @endif




    </div>
</div>


    <div class = "row">
        <div class = "col-md-12">
            <div style = "margin-bottom:10px;width:100%;border-bottom: 1px solid #dee2e6;"></div>
        </div>


    </div>



    <div class = "row" style = "margin-bottom:20px">
    <div class="col-md-12">

        <div style ="display: inline-flex; margin-top:5px; margin-bottom: 15px">
            <div style = "padding-right: 5px;font-size: 12px; font-weight: 700; padding-top: 3px">
                Year
            </div>
            <div>
                <input onchange="updateYearRange()"  id="year_start_input" maxlength="4" class = "form-control" style = "font-size: 12px;max-height: 25px;max-width: 65px;" type = "number" value = "{{$filters->low_year}}">
            </div>
            <div style = "margin: 3px">
                -
            </div>
            <div>
                <input onchange="updateYearRange()" id="year_end_input" maxlength="4" class = "form-control" style = "font-size: 12px;max-height: 25px;max-width: 65px;" type = "number" value = "{{$filters->high_year}}">
            </div>

        </div>

        <div style = "display:none;font-size:10px;font-weight: 700" id = "year_range_alert"  class="alert alert-danger" role="alert">
            Enter in a valid year
        </div>

        <div id="range-slider-year">

        </div>

    </div>
</div>

<div class = "row">
    <div class = "col-md-12">
        <div style = "margin-bottom:10px;width:100%;border-bottom: 1px solid #dee2e6;"></div>
    </div>

</div>

<div class = "row" style = "margin-bottom:20px">
    <div class="col-md-12">
        <div style="display:inline-flex">
            <div style = "padding-right: 5px;font-size: 12px; font-weight: 700; padding-top: 8px">
                Mileage
            </div>
            <div style ="display: inline-flex; margin-top:5px; margin-bottom: 15px">

                 <div>
                    <input onchange="updateMileageRange()"  id="mileage_start_input"  class = "form-control" style = "font-size: 12px;max-height: 25px;max-width: 85px;" type = "number" value = "{{$filters->low_mileage}}">
                </div>
                <div style = "margin: 3px">
                    -
                </div>
                <div>
                    <input onchange="updateMileageRange()" id="mileage_end_input"  class = "form-control" style = "font-size: 12px;max-height: 25px;max-width: 85px;" type = "number" value = "{{$filters->high_mileage}}">
                </div>

            </div>
        </div>
        <div style = "display:none;font-size:10px;font-weight: 700" id = "mileage_range_alert"  class="alert alert-danger" role="alert">
            Enter in a valid mileage
        </div>

        <div id="range-slider-mileage">

        </div>

    </div>
</div>
    <div class = "row">
        <div class="col-md-12">
            <br><br>
        </div>
    </div>


</form>
<!--
<div class = "row">
    <div class="col-md-12">

        <div class="model-filter">
            <div style = "font-size: 12px;font-weight: 700;">Exterior Color</div>
            <select onchange="submitFormEvent()" id="v_model" name="v_model" class="form-control">
                <option value="all">All</option>


                @foreach($filters->models as $model => $model_info)
                    @if(Request::has('v_model') && Request::get('v_model') != 'all' && Request::get('v_model') == $model_info['id'] && strlen(Request::get('v_model')) > 0)

                        <option value="{{ $model_info['id'] }}" selected>{{ $model }} ({{$model_info['count']}})</option>
                    @else
                        <option value="{{ $model_info['id'] }}" >{{ $model }} ({{$model_info['count']}})</option>
                    @endif

                @endforeach


            </select>
        </div>

    </div>
</div>
-->
<!--
<div class = "row">
    <div class="col-md-12">

        <div class="model-filter">
            <div style = "font-size: 12px;font-weight: 700;">Interior Color</div>
            <select onchange="submitFormEvent()" id="v_model" name="v_model" class="form-control">
                <option value="all">All</option>


                @foreach($filters->models as $model => $model_info)
                    @if(Request::has('v_model') && Request::get('v_model') != 'all' && Request::get('v_model') == $model_info['id'] && strlen(Request::get('v_model')) > 0)

                        <option value="{{ $model_info['id'] }}" selected>{{ $model }} ({{$model_info['count']}})</option>
                    @else
                        <option value="{{ $model_info['id'] }}" >{{ $model }} ({{$model_info['count']}})</option>
                    @endif

                @endforeach


            </select>
        </div>

    </div>
</div>
-->
<!--
<div class = "row">
    <div class="col-md-12">

        <div class="model-filter">
            <div style = "font-size: 12px;font-weight: 700;">Fuel Type</div>
            <select onchange="submitFormEvent()" id="v_model" name="v_model" class="form-control">
                <option value="all">All</option>


                @foreach($filters->models as $model => $model_info)
                    @if(Request::has('v_model') && Request::get('v_model') != 'all' && Request::get('v_model') == $model_info['id'] && strlen(Request::get('v_model')) > 0)

                        <option value="{{ $model_info['id'] }}" selected>{{ $model }} ({{$model_info['count']}})</option>
                    @else
                        <option value="{{ $model_info['id'] }}" >{{ $model }} ({{$model_info['count']}})</option>
                    @endif

                @endforeach


            </select>
        </div>

    </div>
</div>-->


</div>


                       