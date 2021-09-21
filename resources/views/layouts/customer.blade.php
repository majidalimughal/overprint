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
    <!-- chartist CSS -->
    <link href="{{asset('material/assets/plugins/chartist-js/dist/chartist.min.css')}}" rel="stylesheet">
    <link href="{{asset('material/assets/plugins/chartist-js/dist/chartist-init.css')}}" rel="stylesheet">
    <link href="{{asset('material/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css')}}" rel="stylesheet">
    <!-- toggle---->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!--This page css - Morris CSS -->
    <link href="{{asset('material/assets/plugins/c3-master/c3.min.css')}}" rel="stylesheet">

    <!-----Slick Slider ----->
    <link rel="stylesheet" type="text/css" href="{{asset('slick/slick.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('slick/slick-theme.css')}}">
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
@php $authUser=Auth::user(); @endphp
<body class="fix-header fix-sidebar card-no-border cs-body {{$authUser->role==='store' && $authUser->dark===true?'dark':'light'}}">
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
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <header class="topbar">
        <nav class="navbar top-navbar navbar-toggleable-sm navbar-light">
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <div class="navbar-header">
                <a class="navbar-brand" href="">
                    <!-- Logo icon --><b>
                        <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->

                        <!-- Light Logo icon -->
                        <a href="{{route('customer.check')}}">
                            <img src="{{asset('c.png')}}" alt="homepage" height="50px" width="auto" class="light-logo" />
                        </a>
                    </b>
                    <!--End Logo icon -->
                    <!-- Logo text -->
                    <span>
                    </span>
                </a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <div class="navbar-collapse pr-5">
                <!-- ============================================================== -->
                <!-- toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav mr-auto mt-md-0">
                    <!-- This is  -->
                {{--<li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>--}}
                <!-- ============================================================== -->
                    <!-- Search -->
                    <!-- ============================================================== -->
                </ul>
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <ul class="navbar-nav my-lg-0">
                    <!-- ============================================================== -->
                    <!-- Profile -->
                    <!-- ============================================================== -->
                    <li class="nav-item dropdown">
                        <a class="nav-link  text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars font-20"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right user-dd flipInY">
                            <a class="dropdown-item" href="">
                                <i class="ti-user m-r-5 m-l-5"></i> My Info</a>
                            <a class="dropdown-item" href="https://www.boompup.com/pages/photo-guide " target="_blank">
                                <i class="ti-camera m-r-5 m-l-5"></i>Photo Guides</a>
                            <a class="dropdown-item" href="https://boompup.aftership.com/" target="_blank">
                                <i class="ti-settings m-r-5 m-l-5"></i>Track Order
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="https://www.boompup.com/" target="_blank">
                                <i class="fa fa-home m-r-5 m-l-5"></i>Store
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('customer.logout')}}">
                                <i class="fa fa-power-off m-r-5 m-l-5"></i>Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- ============================================================== -->
    <!-- End Topbar header -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class=" cs-wrapper mg-t-10">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container">
            @include('flash_message.message')
            @yield('content')
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->

    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <!-- ============================================================== -->
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
<!--Slick slider -->
<script src="{{asset('slick/slick.js')}}" type="text/javascript" charset="utf-8"></script>
<!--Custom JavaScript -->
<script src="{{asset('material/js/custom.min.js')}}"></script>
<script src="{{asset('material/js/cs-script.js')}}"></script>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->
<!-- chartist chart -->
<script src="{{asset('material/assets/plugins/chartist-js/dist/chartist.min.js')}}"></script>
<script src="{{asset('material/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js')}}"></script>
<!--c3 JavaScript -->
<script src="{{asset('material/assets/plugins/d3/d3.min.js')}}"></script>
<script src="{{asset('material/assets/plugins/c3-master/c3.min.js')}}"></script>
<!-- Chart JS -->
<script src="{{asset('material/js/dashboard1.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
</body>

</html>
