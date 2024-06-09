@extends('layouts.user-base')

@section('content')
<div class="body-container d-flex flex-column align-items-center justify-content-center w-100">
    <img class="mb-5" src="{{ asset('images/School Logo.png') }}" width="400px" />
    <h5><strong>Please scan your ID to login</strong></h5>
    <p>If you don't have an ID, please click the button below.</p>
    <a href="{{ route('user-manual-timein') }}" class="btn btn-light px-5">
        <strong style="color: #014fb3">Time In</strong>
    </a>
</div>
@endsection