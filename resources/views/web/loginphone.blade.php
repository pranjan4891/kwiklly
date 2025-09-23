@extends('web.include.main')
@section('content')
<?php //include("include/header2.php")?>
<!-- first section start  -->
<section>
<div class="container log-in-container form-section">
    <div class="log-in-box">
      <h4 class="mb-4 fw-bold">Log In</h4>

      {{-- <div class="d-flex justify-content-center gap-3 mb-3">
        <button class="btn log-in-social-btn bg-transparent">
          <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google">
        </button>
        <button class="btn log-in-social-btn bg-transparent">
          <img src="https://img.icons8.com/fluency/48/000000/facebook-new.png" alt="Facebook">
        </button>
      </div>

      <div class="log-in-divider">or</div> --}}

      <form action="{{ route('otpsent') }}" method="POST">
        @csrf
        <div class="mb-3">
          <input type="number" class="form-control log-in-form-control" name="phone_number" placeholder="99XXXXX99" required maxlength="10" minlength="10">
        </div>
        @error('phone_number')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn log-in-btn w-100 py-2 mb-3">Request for OTP</button>

        <a href="{{route('login')}}" class="log-in-phone-login d-block mb-3">Log in using Email</a>

        <p class="mb-0">Don't have an account? <a href="{{route('signup')}}" class="log-in-signup-link">Sign Up</a></p>
      </form>
    </div>
  </div>
</section>
<!-- first section end  -->
@endsection
