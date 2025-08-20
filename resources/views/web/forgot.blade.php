@extends('web.include.main')
@section('content')
<!-- first section start  -->
<section>
<div class="container log-in-container form-section">
    <div class="log-in-box">
      <h4 class="mb-4 fw-bold">Forgot Password ?</h4>
      <p>If you can't log in to your account because you forget your password, you can reset now.</p>
      <div class="log-in-divider">or</div>
      <form>
        <div class="mb-3">
          <input type="email" class="form-control log-in-form-control" placeholder="Email Id" required></div>
        <button type="submit" class="btn log-in-btn w-100 py-2 mb-3">Reset Password</button>
      </form>
    </div>
  </div>
</section>
<!-- first section end  -->
@endsection