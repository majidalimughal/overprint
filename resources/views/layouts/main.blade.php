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
    <title>XPrintee</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{asset('material/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
@yield('styles')
<!-- chartist CSS -->
{{--    <link href="{{asset('material/assets/plugins/chartist-js/dist/chartist.min.css')}}" rel="stylesheet">--}}
{{--    <link href="{{asset('material/assets/plugins/chartist-js/dist/chartist-init.css')}}" rel="stylesheet">--}}
{{--    <link href="{{asset('material/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css')}}" rel="stylesheet">--}}
<!-- toggle---->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!--This page css - Morris CSS -->
    <link href="{{asset('material/assets/plugins/c3-master/c3.min.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('material/css/style.css')}}" rel="stylesheet">

    <link href="{{asset('material/assets/plugins/alertify/css/alertify.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9/dist/sweetalert2.min.css" id="theme-styles">

    <!-- You can change the theme colors from here -->
    <link href="{{asset('material/css/colors/blue.css')}}" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->
</head>
<?php
$authUser=\Illuminate\Support\Facades\Auth::user();
?>
<body class="fix-header fix-sidebar card-no-border {{$authUser->role==='store' && $authUser->theme==='dark'?'dark':'light'}}">
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
    </svg>
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
                        <a href="">
                            <img src="{{asset('c.png')}}" alt="homepage" height="50px" width="auto" class="light-logo"/>
                        </a>
                    </b>
                    <!--End Logo icon -->
                    <!-- Logo text --><span>

                         <!-- Light Logo text -->
                         {{--<img src="{{asset('material/assets/images/logo-light-text.png')}}" class="light-logo" alt="homepage" />--}}</span>
                </a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <div class="navbar-collapse">
                <!-- ============================================================== -->
                <!-- toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav mr-auto mt-md-0">
                    <!-- This is  -->
                    <li class="nav-item"><input type="checkbox"/></li>
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
                        <input type="checkbox"/>
                    </li>
                    @if(\Illuminate\Support\Facades\Auth::user()->role!=='store')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark"
                           onclick="document.getElementById('logout-form').submit();" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false"><i class="mdi mdi-power"></i>Logout</a>
                        <form id="logout-form" action="{{route('logout')}}" method="POST">
                            @csrf
                        </form>
                    </li>
                    @endif
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
    <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
            <!-- Sidebar navigation-->

            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    @if($authUser->role=='admin')
                        <li><a class="waves-effect waves-dark" href="{{route('admin')}}" aria-expanded="false"><i
                                    class="mdi mdi-home-variant"></i><span class="hide-menu">Home</span></a>
                        </li>
                        <li><a class="waves-effect waves-dark" href="{{route('admin.orders')}}" data-toggle="collapsed"
                               data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i
                                    class="mdi mdi-inbox-arrow-down"></i><span class="hide-menu">Stores</span></a>
                            <ul class="collapsed" id="collapseExample">
                                <li><a href="{{route('admin.stores')}}"><span
                                            class="dot dot-success bg-purple"></span> All Stores</a></li>
                                                           {{-- <li><a href="{{route('admin.orders')}}?status="><span class="dot dot-alert"></span> New
                                                                   Orders</a></li> --}}
                                <li><a href="{{route('admin.stores')}}?status=fulfilled"><span
                                            class="dot dot-primary"></span> Available Stores</a></li>
                                <li><a href="{{route('admin.stores')}}?status=cancelled"><span
                                            class="dot dot-warning"></span> Cancelled Orders</a></li>

                            </ul>
                        </li> 
                    @endif
                    <li><a class="waves-effect waves-dark" href="{{route('admin.orders.index')}}"
                           data-toggle="collapsed"
                           data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i
                                class="mdi mdi-inbox-arrow-down"></i><span class="hide-menu">Orders</span></a>
                        <ul class="collapsed" id="collapseExample">
                            <li><a href="{{route('admin.orders.index')}}">All Orders</a></li>
                            @if($authUser->role=='supplier')<li><a href="{{route('admin.orders.index')}}?status="><span class="dot dot-alert"></span>
                                    New
                                    Orders</a></li>@endif
                            <li><a href="{{route('admin.orders.index')}}?status=fulfilled"><span
                                        class="dot dot-primary"></span> Completed Orders</a></li>
                            <li><a href="{{route('admin.orders.index')}}?status=cancelled"><span
                                        class="dot dot-warning"></span> Cancelled Orders</a></li>

                        </ul>
                    </li>
                    @if($authUser->role=='admin')
                        <li><a class="waves-effect waves-dark" href="{{route('admin.products')}}" aria-expanded="false"><i
                                    class="mdi mdi-inbox-arrow-down"></i><span class="hide-menu">Products</span></a>
                        </li>

                        <li><a class="waves-effect waves-dark" href="{{route('admin.contact')}}" aria-expanded="false"><i
                            class="mdi mdi-help-circle"></i><span class="hide-menu">Support</span></a>
                        </li>
                        {{-- <li><a class="waves-effect waves-dark" href="{{route('billing.methods')}}"
                               aria-expanded="false"><i
                                    class="mdi mdi-credit-card"></i><span class="hide-menu">Billing Methods</span></a>
                        </li>

                        <li><a class="waves-effect waves-dark" href="{{route('home')}}" aria-expanded="false"><i
                                    class="mdi mdi-help-circle"></i><span class="hide-menu">Support</span></a>
                        </li> --}}
                    @endif


                    {{--<li> <a class="waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu">Managment</span></a>--}}
                    {{--</li>--}}

                </ul>

            </nav>
            <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
        <!-- Bottom points-->

        <!-- End Bottom points-->
    </aside>
    <!-- ============================================================== -->
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
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
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="{{asset('material/assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{asset('material/assets/plugins/bootstrap/js/tether.min.js')}}"></script>
<script src="{{asset('material/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('material/assets/plugins/bootstrap/js/popper.min.js')}}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{asset('material/js/jquery.slimscroll.js')}}"></script>
<!--Wave Effects -->
{{--<script src="{{asset('material/js/waves.js')}}"></script>--}}
<!--Menu sidebar -->
<script src="{{asset('material/js/sidebarmenu.js')}}"></script>
<!--stickey kit -->
<script src="{{asset('material/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
<!--Custom JavaScript -->
<script src="{{asset('material/js/custom.min.js')}}"></script>
<script src="{{asset('material/js/script.js')}}"></script>

<script src="{{asset('material/assets/plugins/alertify/js/alertify.js')}}"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>

<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->
<!-- chartist chart -->
{{--<script src="{{asset('material/assets/plugins/chartist-js/dist/chartist.min.js')}}"></script>--}}
{{--<script src="{{asset('material/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js')}}"></script>--}}
<!--c3 JavaScript -->
{{--<script src="{{asset('material/assets/plugins/d3/d3.min.js')}}"></script>--}}
{{--<script src="{{asset('material/assets/plugins/c3-master/c3.min.js')}}"></script>--}}
{{--Sweet Alert--}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9/dist/sweetalert2.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
<!-- Chart JS -->
{{--<script src="{{asset('material/js/dashboard1.js')}}"></script>--}}
{{--<script src="{{asset('js/script.js')}}"></script>--}}

{{--<script>--}}
{{--    $(function () {--}}
{{--        $('[data-toggle="tooltip"]').tooltip()--}}
{{--    })--}}
{{--</script>--}}

@yield('scripts')


</body>

</html>
