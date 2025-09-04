@extends('web.include.main')
@section('content')
<?php //include("include/header2.php")?>
<!-- first section start  -->
<section class="">
<div class="container log-in-container form-section">
    <div class="log-in-box">
      <h4 class="mb-4 fw-bold">Sign Up </h4>

      {{-- <div class="d-flex justify-content-center gap-3 mb-3">
        <button class="btn log-in-social-btn bg-transparent">
          <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google">
        </button>
        <button class="btn log-in-social-btn bg-transparent">
          <img src="https://img.icons8.com/fluency/48/000000/facebook-new.png" alt="Facebook">
        </button>
      </div> --}}

      <div class="log-in-divider">or</div>

      <form action="{{ route('signup.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <input type="text" name="name" class="form-control log-in-form-control" placeholder="Full Name" required>
    </div>

    <div class="mb-3">
        <input type="email" name="email" class="form-control log-in-form-control" placeholder="Email id" required>
    </div>
    <div class="mb-2">
        <input type="text" name="phone_number" class="form-control log-in-form-control" placeholder="Phone Number" required>
    </div>
    <div class="mb-2">
        <input type="password" name="password" class="form-control log-in-form-control" placeholder="Password" required>
    </div>
    <div class="mb-2">
        <input type="password" name="password_confirmation" class="form-control log-in-form-control" placeholder="Confirm Password" required>
    </div>
    <div class="mb-2">
        <input type="text" name="referral_code" class="form-control log-in-form-control" placeholder="Referral Code">
    </div>

    <a href="{{ route('forgot') }}" class="log-in-forgot-pass">Forgot Password?</a>
    <button type="submit" class="btn log-in-btn w-100 py-2 mb-3">Sign Up</button>

    <p class="mb-0">If already have account! <a href="{{ route('login') }}" class="log-in-signup-link">Login</a></p>
</form>


    </div>
  </div>
</section>
<!-- first section end  -->
@endsection
