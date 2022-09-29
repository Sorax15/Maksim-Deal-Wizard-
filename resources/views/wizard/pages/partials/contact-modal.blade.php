<template id="contact_form_modal">


    <swal-html>


        <div class="" style="margin:20px;text-align: left">
            <div class="contact-info-header-section">
                @if(isset($salesperson) && !empty($salesperson))
                    <div style = "display: flex">
                        <img class = "personal_image" src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
                        <h3 style = "margin-top: 20px;">Contact Details</h3>
                    </div>


                @else
                    <h3>Contact Details</h3>
                @endif

                <h6 style="margin-bottom: 35px;">
                    Thanks for entering your contact information below in order for me to assist you.
                </h6>

            </div>
            <div class="contact-info-form-section">
                <h3>Contact Information</h3>
                <hr />
                <div class="contact-form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>First Name</label>
                                <input style="max-width: 500px;" type="text" name="fname" required class="form-control" value="{{ old('fname', $deal->fname) }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input style="max-width: 500px;" type="text" name="lname" required class="form-control" value="{{ old('lname', $deal->lname) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Email</label>
                            <input style="max-width: 500px;" type="email" name="email" required class="form-control" value="{{ old('email', $deal->email) }}">
                        </div>
                        <div class="col-md-12" style="padding-top: 30px;">
                            <label>Phone Number</label>
                            <input style="max-width: 500px;" id="phone" maxlength="10" type="tel" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"  name="phone" value="{{ old('phone', $deal->phone) }}" class="form-control" />

                        </div>
                    </div>

                    <div class="text-opt-in">
                        <div class="checkbox">

                            <label for="text_opt_in">
                                <input type="checkbox" name="text_opt_in" id="text_opt_in" checked>
                                <span class="contact-box"></span>
                                <span class="cb-text" style="margin-left: 35px!important;">I agree to receiving Text Messages from {{ $dealer->dealer_name }}.</span>
                            </label>


                        </div>
                    </div>

                    <div style="text-align: center;margin-top:5px" class="">
                        <a id="contact-submit" class="btn btn-primary next-step-btn" onclick="submitContactInfo()">SUBMIT</a>
                    </div>
                    <br>
                    <div style="text-align: center" class="">
                        <span onclick="hideContactModal()" class = "modal-salesperson-cancel">Cancel</span>
                    </div>

                </div>
            </div>
        </div>




    </swal-html>



</template>