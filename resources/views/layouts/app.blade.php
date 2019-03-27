<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tell the browser to be responsive to screens width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('img/favicon.png')}}">
    <title>Demo Cotel - Bayoex S.R.L.</title>
    <!-- Custom CSS -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.min.css')}}" rel="stylesheet">
@stack('styles')
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
    </div>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper" data-sidebartype="mini-sidebar" class="mini-sidebar">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    @yield('navbar')
    <!-- ============================================================== -->
    <!-- End Topbar header -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    @yield('sidebar')
    <!-- ============================================================== -->
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->

    @yield('content')

</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<script src="{{asset('js/app.js')}}"></script>
<!-- ============================================================== -->
<!-- Bootstrap tether Core JavaScript -->
<script src="{{asset('js/libs/popper.js/dist/umd/popper.min.js')}}"></script>
<script src="{{asset('js/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')}}"></script>
<!--Wave Effects -->
<script src="{{asset('js/waves.js')}}"></script>
<!--Menu sidebar -->
<script src="{{asset('js/sidebarmenu.js')}}"></script>
<!--Custom JavaScript -->
<script src="{{asset('js/custom.min.js')}}"></script>
<!--This page JavaScript -->
<!-- <script src="../../dist/js/pages/dashboards/dashboard1.js"></script> -->
@stack('scripts')
</body>

</html>