<!doctype html>
<html class="no-js " lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="description" content="Manage campaigns">
        {{-- <title>SendMunk: Campaigns</title> --}}
        <!-- Favicon-->
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
        {{-- <link rel="icon" href="../assets/images/logo-svg.svg" type="image/x-icon"> --}}
        <link rel="stylesheet" href="{{ asset('assets2/plugins/bootstrap/css/bootstrap.min.css') }}" >
        <link rel="stylesheet" href="{{ asset('assets2/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('assets2/plugins/morrisjs/morris.min.css') }}" />
        <!-- Custom Css -->
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('css/color_skins.css') }}">

        <title>@yield('title', 'SendMunk')</title>

    </head>
    <body class="theme-red">
        <!-- Page Loader -->
        <div class="page-loader-wrapper">
            <div class="loader">
                <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('assets2/images/logo-svg.svg') }}" width="55" height="55" alt="SendMunk"></div>
                <p>SendMunk Loading...</p>
            </div>
        </div>
        <!-- Overlay For Sidebars -->
        <div class="overlay"></div>

        <!-- Top Bar -->
        <nav class="navbar p-l-5 p-r-5">
            <ul class="nav navbar-nav navbar-left">
                <li class="float-right">
                    <a href="sign-in.html" class="mega-menu" data-close="true"><i class="zmdi zmdi-power"></i></a>
                </li>
            </ul>
        </nav>

        <!-- Left Sidebar -->
        @include('includes.main.sidebar')

        <!-- Main Content -->
        <section class="content home">
            @yield('content')
        </section>
        <!-- Jquery Core Js -->
        @include('includes.main.footerscripts')
        <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) -->
        <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script> <!-- slimscroll, waves Scripts Plugin Js -->

        <script src="{{ asset('assets/bundles/morrisscripts.bundle.js') }}"></script><!-- Morris Plugin Js -->
        <script src="{{ asset('assets/bundles/jvectormap.bundle.js') }}"></script> <!-- JVectorMap Plugin Js -->
        <script src="{{ asset('assets/bundles/knob.bundle.js') }}"></script> <!-- Jquery Knob, Count To, Sparkline Js -->

        <script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
        <script src="{{ asset('js/pages/index.js') }}"></script>

        
    </body>
</html>