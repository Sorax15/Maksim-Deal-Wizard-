
@if(count($vehicles->items) == 0)

    <div class="col-md-12 vehicles">
        <div class="row">
            <div class="resetFilterBtn">
                <a class="btn btn-danger" onclick="clearFilters();">No Vehicles Found - Reset Filters</a>
            </div>
        </div>
    </div>

@else


<div style="display: grid;gap: 10px;justify-content: center"  class="vehicle-block">

@foreach($vehicles->items as $vehicle)

    @php

    $offerlogix_payment = false;
    if(!empty($vehicle->offerlogix) && !empty($vehicle->offerlogix->good)){
        $offerlogix_payment = $vehicle->offerlogix->good->financing->monthlyPayment;
    }else if(!empty($vehicle->offerlogix) && !empty($vehicle->offerlogix->excellent)){
        $offerlogix_payment = $vehicle->offerlogix->excellent->financing->monthlyPayment;
    }else if(!empty($vehicle->offerlogix) && !empty($vehicle->offerlogix->fair)){
        $offerlogix_payment = $vehicle->offerlogix->fair->financing->monthlyPayment;
    }else if(!empty($vehicle->offerlogix) && !empty($vehicle->offerlogix->poor)){
        $offerlogix_payment = $vehicle->offerlogix->poor->financing->monthlyPayment;
    }

    if($vehicle->alt_monthly_payment){
       $offerlogix_payment =  $vehicle->alt_monthly_payment;
    }

    @endphp

    <div style = "padding-bottom:20px" class="">
        <a class="vehicle-link" href="{{ url('vehicle-detail?user_token=' . $user_token . '&vehicle_id=' . $vehicle->id) }}">
            <div class="vehicle">
                <div class="" style="">
                    <img style = "" src="{{ $vehicle->photo }}" class="vehicle-img">
                    <div class="vehicleClass">
                        <span class="vehicle-{{ strtolower($vehicle->condition) }}">{{ ucfirst($vehicle->condition) }}</span>
                    </div>
                </div>
                <div class="vehicle-info">
                    <div style="display: flex;justify-content: space-between;">
                        <div class="stockNumber">Stock# {{ $vehicle->stock_number }}</div>
                        <div class="vehicleMileage"> {{ number_format($vehicle->mileage,0,"",",") }} Miles</div>
                    </div>
                    <div class="vehicleName">{{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}</div>
                    <div class="vehicleBotInfo">
                        <div class="vehiclePrice" >${{ number_format($vehicle->price, 2) }} </div>
                        @if($offerlogix_payment)


                            <div class="finance_price">${{number_format($offerlogix_payment,0,"",",")}}/mo</div>

                        @endif
                    </div>
                </div>
            </div>
        </a>
    </div>
@endforeach
</div>

<div class="row">
    <div class = "col-md-12">
        @include('wizard.pages.partials.vehicle-pagination')

    </div>
</div>

@endif


