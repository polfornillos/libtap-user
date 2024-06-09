@extends('layouts.user-base')

@section('content')
<div class="body-container d-flex flex-column align-items-center justify-content-center w-100">
    <img class="mb-4" src="{{ asset('images/School Logo.png') }}" width="150px" />
    <h5 class="mb-4"><strong>IACADEMY LIBRARY ATTENDANCE</strong></h5>
    <div class="main-container">
        <div class="attendance-form bg-white p-5 rounded">
            <form action="{{ route('register.attendance') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">User type</label>
                    <select class="form-select" id="userType" name="userType">
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                        <option value="non-teaching">Non-teaching (Guest)</option>
                    </select>
                </div>
                <div class="mb-3" id="studentEmployeeNumberContainer">
                    <label class="form-label">Student/Employee Number</label>
                    <input type="text" class="form-control" id="studentEmployeeNumber" name="studentEmployeeNumber" />
                </div>
                <div class="w-100 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4 mt-3">Submit</button>
                </div>
            </form>
        </div>
        <a href="{{ route('home') }}" class="btn btn-light px-5 mt-3" id="cancelBtn">
            <strong style="color: #014fb3">Cancel</strong>
        </a>
    </div>
</div>
<img class="bottom-right-img" src="{{ asset('images/School Logo.png') }}" alt="Bottom Right Image" />

<script>
    @if(session('error'))
        var errorMessage = '{{ session('error') }}';
    @else
        var errorMessage = '';
    @endif
</script>
@endsection
