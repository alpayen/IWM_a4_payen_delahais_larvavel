<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- JS Libs -->
    <script
            src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha256-k2WSCIexGzOj3Euiig+TlR8gA0EmPjuc79OEeY5L45g="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>
    <style>

        html, body {
            font-family: Source Serif Pro, PT Sans, Trebuchet MS, Helvetica, Arial;
            font-weight: 100;
            height: 100vh;
            margin: 0;
            background-color: aliceblue;
        }
        h1, h2{
            color: #1f648b;
            margin: 1%;
        }
        .links > a {
            color: #1d658b;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

    </style>
</head>
<body>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url(route('project.index')) }}">
                 < Back To Safety |
            </a>
            <a href="{{route('project.edit', $project)}}">Edit</a> |
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a>{{ Auth::user()->name }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"

                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>

                        <li><a href="{{ asset('/project') }}">Project</a></li>
                        @endguest
            </ul>
        </div>
    </div>
</nav>

<div id="sidebar">
    <ul class="list-group">
        <li class="list-group-item"><a href="{{route('project.live', [$project])}}">Live Version ! </a></li>
        <li class="list-group-item"><a href="{{route('project.show', [$project, "type"=>'html'])}}">Html</a></li>
        <li class="list-group-item"><a href="{{route('project.show',[$project, "type"=>'css'])}}">CSS</a></li>
        <li class="list-group-item"><a href="{{route('project.show',[$project, "type"=>'javascript'])}}">Javascript (jQuery Enabled)</a></li>
    </ul>
</div>
<div id="app">
    @yield('content')
</div>
<!-- Scripts -->
<script>
    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
    ]); ?>
</script>
<script src="{{asset('js/app.js')}}"></script>
</body>
</html>
