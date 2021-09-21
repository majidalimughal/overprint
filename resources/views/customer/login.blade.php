<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon.png')}}">
    <title>Dashboard</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{asset('material/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- toggle---->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!--This page css - Morris CSS -->
    <link href="{{asset('material/assets/plugins/c3-master/c3.min.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('material/css/style.css')}}" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="{{asset('material/css/colors/blue.css')}}" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="fix-header fix-sidebar card-no-border">
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">

    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style='background:url("{{asset('material/login-images/auth-bg.jpg')}}") no-repeat center center;'>
        <div class="auth-box">
            <div id="loginform">
                <div class="logo">
                    <span class="db">
                        <img src="{{asset('customer-center-ogo.png')}}" alt="logo" style="max-width: 400px;">
                    </span>

                </div>
                @if(session()->has('msg'))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert" style="margin: 0">{{session('msg')}}</div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <form action="{{route('customer.check')}}" method="get" class="form-horizontal m-t-20">
{{--                           @csrf--}}
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                </div>
                                <input type="text" required class="form-control form-control-lg" placeholder="Enter your Order ID" name="order_name">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                                </div>
                                <input type="email" required class="form-control form-control-lg" placeholder="Enter Your Email Address" name="email">
                            </div>

                            <div class="form-group text-center">
                                <div class="col-xs-12 p-b-20">
                                    <button style="background: #ff438d;border-radius: 41px;" class="btn customer-login-btn btn-block btn-lg btn-danger" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="{{asset('material/assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{asset('material/assets/plugins/bootstrap/js/tether.min.js')}}"></script>
<script src="{{asset('material/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{asset('material/js/jquery.slimscroll.js')}}"></script>
<!--Wave Effects -->
<script src="{{asset('material/js/waves.js')}}"></script>
<!--Menu sidebar -->
<script src="{{asset('material/js/sidebarmenu.js')}}"></script>
<!--stickey kit -->
<script src="{{asset('material/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
<!--Custom JavaScript -->
<script src="{{asset('material/js/custom.min.js')}}"></script>
<script src="{{asset('material/js/script.js')}}"></script>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->
<!-- chartist chart -->
<!-- Chart JS -->
<script src="{{asset('material/js/dashboard1.js')}}"></script>
</body>

</html>
