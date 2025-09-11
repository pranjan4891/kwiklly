@extends('web.include.main')
@section('content')
<!-- first section start  -->
<section>
    <div class="container log-in-container form-section">
        <div class="log-in-box">
            <h4 class="mb-4 fw-bold">Reset Password</h4>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <form method="POST" action="{{ route('password.reset') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-3">
                    <input type="password" name="password" class="form-control log-in-form-control" placeholder="New Password" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password_confirmation" class="form-control log-in-form-control" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="btn log-in-btn w-100 py-2 mb-3">Update Password</button>
            </form>
        </div>
    </div>
</section>


<!-- first section end  -->
@endsection
