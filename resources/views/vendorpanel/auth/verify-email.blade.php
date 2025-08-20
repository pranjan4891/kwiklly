@extends('vendorpanel.include.main')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <!-- Include Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg rounded">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Verify Your Email</h4>
                    </div>
                    <div class="card-body">

                        @if (session('message'))
                            <div class="alert alert-success text-center">
                                {{ session('message') }}
                            </div>
                        @endif

                        <p class="text-center mb-4">
                            A verification link has been sent to your email address:
                            <strong class="text-primary">{{ Auth::guard('vendor')->user()->email }}</strong>
                        </p>

                        <form method="POST" action="{{ route('vendor.verification.send') }}" class="text-center mb-3">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                Resend Verification Email
                            </button>
                        </form>

                        <form method="POST" action="{{ route('vendor.logout') }}" class="text-center">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                Logout
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

@endsection
