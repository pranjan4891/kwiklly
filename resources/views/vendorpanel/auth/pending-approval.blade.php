@extends('vendorpanel.include.main')
@section('content')

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Pending Approval</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light" style="min-height: 100vh; display: flex; flex-direction: column;">

    <div class="container flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="text-center p-5 bg-white shadow rounded" style="max-width: 600px; width: 100%;">
            <h2 class="text-warning mb-3">Please wait for approval by Admin.</h2>
            <p class="text-secondary">You will be notified once your account is approved.</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            setInterval(function(){
                $.get('{{ route("vendor.status") }}', function(data){
                    if(data.status == 1){
                        window.location.href = '{{ route("vendor.dashboard") }}';
                    }
                });
            }, 5000); // Check every 5 seconds
        });
    </script>

</body>
</html>

@endsection
