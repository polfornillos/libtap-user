@extends('layouts.user-base')

@section('content')
<div class="body-container d-flex flex-column align-items-center justify-content-center w-100">
    <img class="mb-5" src="{{ asset('images/School Logo.png') }}" width="400px" />
    <h5><strong>Please scan your ID to login</strong></h5>
    <p>If you don't have an ID, please click the button below.</p>
    <a href="{{ route('user.manual.timein') }}" class="btn btn-light px-5">
        <strong style="color: #014fb3">Time In</strong>
    </a>
</div>

<!-- Hidden input field to capture RFID scans -->
<input type="text" id="rfidInput" style="position:absolute; top:-9999px;">

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rfidInput = document.getElementById('rfidInput');
        let rfidValue = '';
        let timeout;

        // Function to focus the RFID input
        function focusRFIDInput() {
            rfidInput.focus();
        }

        // Automatically focus the RFID input field
        focusRFIDInput();

        // Handle page visibility change
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'visible') {
                focusRFIDInput();
            }
        });

        // Refocus the RFID input if the user clicks away
        document.addEventListener('click', function () {
            focusRFIDInput();
        });

        // Handle the input event
        rfidInput.addEventListener('input', function (e) {
            clearTimeout(timeout);
            rfidValue += e.data;

            timeout = setTimeout(function () {
                if (rfidValue) {
                    // Send the RFID value to the server
                    fetch('{{ route('user.rfid.timein') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ rfid: rfidValue })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            sessionStorage.setItem('name', data.name);
                            sessionStorage.setItem('id_number', data.id_number);
                            sessionStorage.setItem('school_id', data.school_id);
                            sessionStorage.setItem('time_in', data.time_in);

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Attendance registered successfully',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "{{ route('user.success.timein') }}";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error,
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while processing the RFID scan.',
                        });
                    });

                    // Clear the accumulated RFID value for the next scan
                    rfidValue = '';
                }
            }, 200); // Adjust the timeout as needed for your RFID reader
        });
    });
</script>
@endsection
