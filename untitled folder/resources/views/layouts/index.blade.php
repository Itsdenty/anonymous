<html lang="en">
      <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="">
            <meta name="author" content="">
            <link rel="icon" href='{{ asset("favicon.png") }}'/>
            <link rel="stylesheet" type="text/css" href='{{ url("Semantic-UI-CSS-master/semantic.min.css") }}' />
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css" />

            <link rel="stylesheet" type="text/css" href='{{ asset("Semantic-UI-CSS-master/semantic.min.css") }}' />
            {{-- <link rel="stylesheet" type="text/css" href='{{ url("css/user.css") }}'/> --}}


            <title>@yield('title', 'SendMunk')</title>

      </head>
      <body>

            @include('includes.index.header')
            <div class="container-fluid">
                <br>
                @yield('content')
            </div>
            <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src='{{ url("Semantic-UI-CSS-master/semantic.min.js") }}'></script>
            @yield('footerscripts')
      </body>
  </html>
