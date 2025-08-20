@extends('web.include.main2')
@section('content')
<!-- first section start  -->
<section>
<div class="container log-in-container form-section">
    <div class="log-in-box">
      <h4 class="mb-4 fw-bold">Vendor LogIn  Here</h4>
      <form method="POST" action="{{ route('vendor.login.submit') }}">
          @csrf
          <div class="mb-3">
            <input type="email" name="email" class="form-control log-in-form-control" placeholder="Email id" required>
          </div>
          <div class="mb-2">
            <input type="password" name="password" class="form-control log-in-form-control" placeholder="Password" required>
          </div>
          <button type="submit" class="btn log-in-btn w-100 py-2 mb-3">Log In</button>
          <p class="mb-0">Don't have an account? 
            <a href="{{route('vendor.signup')}}" class="log-in-signup-link">Become a Vendor</a>
          </p>
        </form>

    </div>
  </div>
</section>
<!-- first section end  -->
@endsection
