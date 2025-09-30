
@extends('web.include.main')
@section('content')
<style>


</style>
<!-- first section start  -->
<section>
<div class="container log-in-container form-section">
    <div class="log-in-box">
      <h4 class="mb-4 fw-bold">Login</h4>

      <div class="d-flex justify-content-center gap-3 mb-3">
        <button class="btn log-in-social-btn bg-transparent">
            <a href="{{ route('auth.google.redirect') }}">
                <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google">
            </a>
        </button>
        {{-- <button class="btn log-in-social-btn bg-transparent">
          <img src="https://img.icons8.com/fluency/48/000000/facebook-new.png" alt="Facebook">
        </button> --}}
      </div>

      <!--<div class="log-in-divider">or</div>-->

      <form action="{{ route('login.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <input type="email" name="email" class="form-control log-in-form-control" placeholder="Email id" required>
            @error('email')
                <div class="text-danger mb-2">{{ $message }}</div>
            @enderror
    </div>

    <div class="mb-2">
        <input type="password" name="password" class="form-control log-in-form-control" placeholder="Password" required>
         @error('password')
            <div class="text-danger mb-2">{{ $message }}</div>
        @enderror
    </div>


    <a href="{{ route('forgot.password.form') }}" class="log-in-forgot-pass">Forgot Password?</a>

    <button type="submit" class="btn log-in-btn w-100 py-2 mb-3">LOGIN</button>

    <a href="{{ route('loginbyphone') }}" class="log-in-phone-login d-block mb-3">Login using phone number</a>

    <p class="mb-0">Don't have an account? <a href="{{ route('signup') }}" class="log-in-signup-link">Sign Up</a></p>
</form>

    </div>
  </div>
</section>
<!-- first section end  -->
@endsection
