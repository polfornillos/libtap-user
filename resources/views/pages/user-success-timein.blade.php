@extends('layouts.user-base')

@section('content')
<div class="body-container d-flex flex-column align-items-center justify-content-center w-100">
    <img class="mb-5" src="https://placehold.co/300x300" />
    <h5><strong>{{ session('name') }} - {{ session('id_number') }}</strong></h5>
    <h5><strong>Time In: {{ session('time_in') }}</strong></h5>
    <a href="{{ route('home') }}" class="btn btn-light px-5 mt-5" id="goBackBtn">
        <strong style="color: #014fb3">Go Back</strong>
    </a>
</div>
<img class="bottom-right-img" src="{{ asset('images/School Logo.png') }}" alt="Bottom Right Image" />

<script>
  setTimeout(function() {
      window.location.href = "{{ route('home') }}";
  }, 2000); 
</script>
@endsection
