<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Kwiklly | {{ $title }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('public/assets/website/img/web-main/logo.png') }}"/>
    <!-- Google-Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:100,300,400,600,700,900,400italic' rel='stylesheet'>
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('public/assets/admin/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/admin/assets/css/bootstrap-reset.css') }}" rel="stylesheet">
    <!--Animation css-->
    <link href="{{ asset('public/assets/admin/assets/css/animate.css') }}" rel="stylesheet">
    <!--Icon-fonts css-->
    <link href="{{ asset('public/assets/admin/assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/admin/assets/ionicon/css/ionicons.min.css') }}" rel="stylesheet" />
    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="{{ asset('public/assets/admin/assets/morris/morris.css') }}">
    <!-- Custom styles for this template -->
    <link href="{{ asset('public/assets/admin/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/admin/assets/css/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/admin/assets/css/style-responsive.css') }}" rel="stylesheet" />
</head>
<body>
    <div class="wrapper-page animated fadeInDown">
        <div class="panel panel-color panel-primary">
            <div class="panel-heading">
                <h3 class="text-center m-t-10"> Sign In to <strong>Admin</strong> </h3>
            </div>
            <form class="form-horizontal m-t-40" method="post" action="{{ route('admin.loginCheck') }}">
                @csrf
                <div class="form-group">
                    <div class="col-xs-12">
                        <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <input class="form-control" type="password" name="password" placeholder="Password">
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif
                <div class="form-group text-right">
                    <div class="col-xs-12">
                        <button class="btn btn-purple w-md" type="submit">Log In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('public/assets/admin/assets/js/jquery.js') }}"></script>
    <script src="{{ asset('public/assets/admin/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/assets/admin/assets/js/pace.min.js') }}"></script>
    <script src="{{ asset('public/assets/admin/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('public/assets/admin/assets/js/jquery.nicescroll.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/admin/assets/js/jquery.app.js') }}"></script>
</body>
</html>
