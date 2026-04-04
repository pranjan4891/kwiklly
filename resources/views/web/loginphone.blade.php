@extends('web.include.main')
@section('content')
<?php //include("include/header2.php")?>
<!-- first section start  -->
<section>
<div class="container log-in-container form-section">
    <div class="log-in-box">
      <h4 class="mb-4 fw-bold">Log In</h4>

      @if(session('error'))
        <div class="alert alert-danger small mb-3">{{ session('error') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger small mb-3">
          @foreach($errors->all() as $err)
            <div>{{ $err }}</div>
          @endforeach
        </div>
      @endif

      <div class="d-flex justify-content-center gap-3 mb-3">
        <button type="button" class="btn log-in-social-btn bg-transparent" onclick="return socialLoginWithLocation({{ json_encode(route('auth.google.redirect')) }})">
                <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google">
        </button>
        <button type="button" class="btn log-in-social-btn bg-transparent" onclick="return socialLoginWithLocation({{ json_encode(route('auth.facebook.login')) }})">
                <img src="https://img.icons8.com/fluency/48/000000/facebook-new.png" alt="Facebook">
        </button>
      </div>

      <div class="log-in-divider">or</div>

      <form id="phoneLoginForm" action="{{ route('otpsent') }}" method="POST">
        @csrf
        <div class="mb-3">
          <input type="number" class="form-control log-in-form-control" name="phone_number" placeholder="99XXXXX99" value="{{ old('phone_number') }}" required maxlength="10" minlength="10">
        </div>
        @error('phone_number')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
        @error('latitude')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
        @error('longitude')
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

@push('scripts')
<script>
(function () {
  function getLoc() {
    if (typeof window.getPreferredSavedLocation === 'function') {
      return window.getPreferredSavedLocation();
    }
    try {
      var raw = localStorage.getItem('userLocation');
      if (!raw) return null;
      var o = JSON.parse(raw);
      if (o && o.lat != null && o.lng != null) return o;
    } catch (e) {}
    return null;
  }

  window.socialLoginWithLocation = function (baseUrl) {
    var loc = getLoc();
    if (!loc || loc.lat == null || loc.lng == null) {
      alert('Pehle header se apni delivery location select karein (jahan service available ho), phir login karein.');
      return false;
    }
    var sep = baseUrl.indexOf('?') >= 0 ? '&' : '?';
    window.location.href = baseUrl + sep + 'latitude=' + encodeURIComponent(loc.lat) + '&longitude=' + encodeURIComponent(loc.lng);
    return false;
  };

  var form = document.getElementById('phoneLoginForm');
  if (form) {
    form.addEventListener('submit', function (e) {
      var loc = getLoc();
      if (!loc || loc.lat == null || loc.lng == null) {
        e.preventDefault();
        alert('Pehle header se apni delivery location select karein (master + vendor zone ke andar), phir OTP maangein.');
        return false;
      }
      var latIn = form.querySelector('input[name="latitude"]');
      var lngIn = form.querySelector('input[name="longitude"]');
      if (!latIn) {
        latIn = document.createElement('input');
        latIn.type = 'hidden';
        latIn.name = 'latitude';
        form.appendChild(latIn);
      }
      if (!lngIn) {
        lngIn = document.createElement('input');
        lngIn.type = 'hidden';
        lngIn.name = 'longitude';
        form.appendChild(lngIn);
      }
      latIn.value = loc.lat;
      lngIn.value = loc.lng;
    });
  }
})();
</script>
@endpush
