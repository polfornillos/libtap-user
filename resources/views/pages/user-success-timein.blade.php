@extends('layouts.user-base')

@section('content')
<div class="body-container d-flex flex-column align-items-center justify-content-center w-100">
    <img class="mb-5" src="https://placehold.co/300x300" />
    <h5><strong id="userInfo"></strong></h5>
    <h5><strong id="timeInfo"></strong></h5>
    <a href="{{ route('home') }}" class="btn btn-light px-5 mt-5" id="goBackBtn">
        <strong style="color: #014fb3">Go Back</strong>
    </a>
</div>
<img class="bottom-right-img" src="{{ asset('images/School Logo.png') }}" alt="Bottom Right Image" />

@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const name = sessionStorage.getItem("name");
        const schoolId = sessionStorage.getItem("school_id");
        const idNumber = sessionStorage.getItem("id_number");
        const timeIn = sessionStorage.getItem("time_in");

        if (name && timeIn) {
            let identifier = '';

            if (schoolId && schoolId !== 'undefined') {
                identifier = `School ID: ${schoolId}`;
            } else if (idNumber && idNumber !== 'undefined') {
                identifier = `ID Number: ${idNumber}`;
            } else {
                identifier = 'No ID available';
            }

            document.getElementById("userInfo").innerText = `${name} - ${identifier}`;
            document.getElementById("timeInfo").innerText = `Time In: ${timeIn}`;
            sessionStorage.clear();
        } else {
            document.getElementById("userInfo").innerText = "Session data missing!";
        }
    });

    setTimeout(function() {
        window.location.href = "{{ route('home') }}";
    }, 2000); 
</script>
@endsection
