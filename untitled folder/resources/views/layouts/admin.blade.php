<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href='{{ asset("favicon.png") }}'/>
        <link rel="stylesheet" type="text/css" href='{{ url("Semantic-UI-CSS-master/semantic.min.css") }}' />
        <link rel="stylesheet" type="text/css" href='{{ url("summernote/summernote-lite.css") }}' />
        <link rel="stylesheet" type="text/css" href='{{ url("css/user.css") }}'/>
        @yield('styles')

        <title>@yield('title', 'Sendmunk')</title>

    </head>
    <body class="index pushable">
        @include('includes.admin.sidebar')
        <div class="pusher">
            <div style="min-height:100%;position:relative;" class="ui basic segment">
                <div class="main-content">
                    @include('includes.admin.navbar')

                    @yield('content')
                    
                    @include('includes.admin.footer')
                </div>
            </div>
        </div>
        @include('includes.admin.footerscripts')           
    </body>
</html>