<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href='{{ url("favicon.ico") }}'/>
        <link rel="stylesheet" type="text/css" href='{{ url("Semantic-UI-CSS-master/semantic.min.css") }}' />
        <link rel="stylesheet" type="text/css" href='{{ url("summernote/summernote-lite.css") }}' />
        <link rel="stylesheet" type="text/css" href='{{ url("css/user.css") }}'/>
        @yield('styles')

        <title>@yield('title', 'Opt-In')</title>

        <script>
            window.Laravel = {!! json_encode([
                'user' => Auth::user(),
                'csrfToken' => csrf_token(),
                'vapidPublicKey' => config('webpush.vapid.public_key'),
                'pusher' => [
                    'key' => config('broadcasting.connections.pusher.key'),
                    'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                ],
            ]) !!};
        </script>

        
        
    </head>
    <body class="index pushable">
        <div class="pusher">
            <div style="min-height:100%;position:relative;" class="ui basic segment">
                <div class="main-content">
                    <nav id="navbar" class="ui secondary menu">
                        <div class="menu">
                            <a style="color:black;" class="item" id="toggle_menu" href="#"><i class="list icon"></i></a>
                        </div>
                    </nav>

                    <div class="container"  id="noti">
                        <notifications-push :new_form = "{{ $form }}"></notifications-push>
                    </div>
                    <div>
                        
                    </div>
                </div>
            </div>
        </div>
        @include('includes.user.footerscripts')        
        
        <script src="/js/app.js"></script>

    </body>
</html>