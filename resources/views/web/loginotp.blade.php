@extends('web.include.main')
@section('content')
<section>
  <div class="container log-in-container form-section">
    <div class="log-in-box">
      <h4 class="mb-4 fw-bold">Enter OTP</h4>

      <!-- OTP Form -->
      <form id="otpForm" method="POST" action="{{ route('otpcheck') }}">
        @csrf
        <input type="hidden" name="phone_number" value="{{ $user->phone_number ?? '' }}">
        <input type="hidden" name="otp" id="otpHidden">

        <div class="d-flex justify-content-center mb-2" id="otpInputs">
          @for($i = 0; $i < 6; $i++)
            <input type="text" maxlength="1" class="log-in-otp-input"
                   value="{{ isset($otp) ? $otp[$i] : '' }}"
                   oninput="moveNext(this)" required>
          @endfor
        </div>

        <div class="text-end">
          <button type="button" id="resendOtpBtn" class="btn btn-link p-0 log-in-resend">
            Resend OTP
          </button>
        </div>

        <button type="submit" class="btn log-in-btn w-100 py-2 my-3">Verify OTP</button>
      </form>
    </div>
  </div>
</section>

<script>
    function moveNext(input) {
        if (input.value.length === 1) {
            let next = input.nextElementSibling;
            if (next && next.classList.contains('log-in-otp-input')) next.focus();
        }
    }

    // Collect all 6 digits into hidden input before submit
    document.getElementById('otpForm').addEventListener('submit', function(e) {
        let otp = '';
        document.querySelectorAll('.log-in-otp-input').forEach(inp => otp += inp.value);
        document.getElementById('otpHidden').value = otp;
    });

    // Resend OTP with AJAX
    document.getElementById('resendOtpBtn').addEventListener('click', function() {
        let phone = document.querySelector('input[name="phone_number"]').value;
        fetch("{{ route('resendotp') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ phone_number: phone })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                let otp = data.otp.toString();
                let inputs = document.querySelectorAll('.log-in-otp-input');

                // Clear and auto-fill inputs
                inputs.forEach((inp, i) => {
                    inp.value = otp[i] ?? '';
                });

                // Update hidden field too
                document.getElementById('otpHidden').value = otp;

                // Focus last input
                inputs[inputs.length - 1].focus();
            } else {
                alert(data.error || "Failed to resend OTP");
            }
        });
    });

    // On page load, update hidden input with pre-filled values (if any)
    window.addEventListener('DOMContentLoaded', function() {
        let otp = '';
        document.querySelectorAll('.log-in-otp-input').forEach(inp => otp += inp.value);
        document.getElementById('otpHidden').value = otp;
    });
</script>
@endsection
