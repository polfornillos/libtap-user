@extends('layouts.user-base')

@section('content')
<div class="body-container d-flex flex-column align-items-center justify-content-center w-100">
    <img class="mb-4" src="{{ asset('images/School Logo.png') }}" width="150px" />
    <h5 class="mb-4"><strong>IACADEMY LIBRARY ATTENDANCE</strong></h5>
    <div class="main-container">
        <div class="attendance-form bg-white p-5 rounded">
            <form id="attendanceForm">
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
                    <label class="form-label">School/Employee Number</label>
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

@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#attendanceForm').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: '{{ route('register.attendance') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Attendance registered successfully',
                            timer: 2000, // 2 seconds
                            showConfirmButton: false
                        }).then(() => {
                            sessionStorage.setItem('name', response.name);
                            sessionStorage.setItem('school_id', response.school_id);
                            sessionStorage.setItem('time_in', response.time_in);
                            window.location.href = "{{ route('user.success.timein') }}";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error,
                        });
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred while registering attendance';
                    if (xhr.status === 400 || xhr.status === 404) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMsg,
                    });
                }
            });
        });

        document.getElementById("userType").addEventListener("change", function() {
            var userType = this.value;
            var studentEmployeeNumberContainer = document.getElementById("studentEmployeeNumberContainer");
            var studentEmployeeNumberInput = document.getElementById("studentEmployeeNumber");

            if (userType === "non-teaching") {
                studentEmployeeNumberContainer.style.display = "none";
                studentEmployeeNumberInput.disabled = true;
            } else {
                studentEmployeeNumberContainer.style.display = "block";
                studentEmployeeNumberInput.disabled = false;
            }
        });
    });
</script>
@endsection
