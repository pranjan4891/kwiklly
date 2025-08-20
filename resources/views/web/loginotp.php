<?php //include("include/header2.php")?>
@extends('web.include.main')
@section('content')
<!-- first section start  -->
<section>

<div class="container log-in-container form-section">
    <div class="log-in-box">
      <h4 class="mb-4 fw-bold">Log In</h4>

      <div class="d-flex justify-content-center gap-3 mb-3">
        <button class="btn log-in-social-btn bg-transparent">
          <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google">
        </button>
        <button class="btn log-in-social-btn  bg-transparent">
          <img src="https://img.icons8.com/fluency/48/000000/facebook-new.png" alt="Facebook">
        </button>
      </div>

      <div class="log-in-divider">or</div>

      <!-- OTP Inputs -->
      <div class="d-flex justify-content-center mb-2">
        <input type="text" maxlength="1" class="log-in-otp-input" oninput="moveNext(this, event)">
        <input type="text" maxlength="1" class="log-in-otp-input" oninput="moveNext(this, event)">
        <input type="text" maxlength="1" class="log-in-otp-input" oninput="moveNext(this, event)">
        <input type="text" maxlength="1" class="log-in-otp-input" oninput="moveNext(this, event)">
        <input type="text" maxlength="1" class="log-in-otp-input" oninput="moveNext(this, event)">
        <input type="text" maxlength="1" class="log-in-otp-input" oninput="moveNext(this, event)">
      </div>

      <div class="text-end">
        <span class="log-in-resend">Resend OTP</span>
      </div>

      <button type="button" class="btn log-in-btn w-100 py-2 my-3">Log in</button>

      <a href="login.php" class="log-in-phone-login d-block mb-3">Log in using email</a>

      <p class="mb-0">Don't have an account? <a href="signup.php" class="log-in-signup-link">Sign Up</a></p>
    </div>
  </div>
</section>
<!-- first section end  -->
@endsection