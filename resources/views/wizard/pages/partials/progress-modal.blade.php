@php

$progress_time_message = $minSaved.'  minutes saved in total';
$progress_wait_message = 'We are processing request. Please wait.';




@endphp



@if($progress_show)

<template id="progress-template">
    <swal-html>
        <div class = "modal-text-header">
            {{$progress_header}}
        </div>
        <div class = "modal-message-container">
            <div class = "modal-message-text">
                <div class = "modal-message-text1">{{$progress_message1}}</div>
                <div class = "modal-message-text2">{{$progress_message2}}</div>
            </div>
            <div class = "modal-image-container">
                <img src="{{$api_endpoints['image']}}/uploads/media/profile_images/cropped/{{ $salesperson->photo }}">
            </div>
        </div>
        <div class = "modal-message-text3">You've just save <b>{{$saved_time}} minutes</b>.</div>
        <div class = "modal-message-text3">{{$progress_text2}}</div>
        <div class = "modal-message-text4">{{$progress_time_message}}</div>
        <div class = "modal-message-text5">
            <i style="color: #0095ff;" class="fas fa-info-circle"></i>
            {{$progress_wait_message}}
        </div>


    </swal-html>
</template>

@endif
