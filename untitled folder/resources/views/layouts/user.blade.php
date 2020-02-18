<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href='{{ asset("favicon.png") }}'/>
        <link rel="stylesheet" type="text/css" href='{{ url("Semantic-UI-CSS-master/semantic.min.css") }}' />
        <link rel="stylesheet" type="text/css" href='{{ url("summernote/summernote-lite.css") }}' />
        <link rel="stylesheet" type="text/css" href='{{ url("css/user.css") }}'/>
        @yield('styles')

        <title>@yield('title', 'SendMunk')</title>
    </head>
    <body class="index pushable">
            @include('includes.user.sidebar')
            <div class="pusher">
                <div style="min-height:100%;position:relative;" class="ui basic segment">
                    <div class="main-content">
                        @include('includes.user.navbar')

                        @yield('content')

                        @include('includes.user.footer')
                    </div>
                </div>
            </div>
        @include('includes.user.footerscripts')

        {{-- <script src="/js/app.js"></script> --}}
    </body>
</html>
