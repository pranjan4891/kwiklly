@extends('vendorpanel.include.main')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Rejected</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light" style="min-height: 100vh; display: flex; flex-direction: column;">

    <div class="container flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="text-center p-5 bg-white shadow rounded" style="max-width: 600px; width: 100%;">
            <h2 class="text-danger mb-3">Your registration was rejected by Admin.</h2>
            <p class="text-muted">Please contact support if you think this was a mistake.</p>

            <a href="#" class="btn btn-outline-primary mt-3">
                Contact Support
            </a>
        </div>
    </div>

</body>
</html>

@endsection
