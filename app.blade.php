<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('/css/app.css')}}" rel="stylesheet">
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
@section('script')
    <!-- Scripts -->
    <script src="{{ asset('/js/app.js')}}"></script>
    <script src="{{asset('/js/angular.min.js')}}"></script>
    <script src="{{asset('/js/ui-bootstrap-2.1.4.min.js')}}"></script>
    <script src="{{asset('/js/angularapp.js')}}"></script>
    <script src="{{asset('/js/bootstrap-dialog.js')}}"></script>
    <script src="{{asset('/js/ol-debug.js')}}"></script>


@show
@section('head')

@show

</head>
<body>
    @section('mainnav')
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/calendar') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li>&nbsp;</li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <!-- <li><a href="{{ url('/register') }}">Register</a></li> -->
                    @else
                       @section('nav')
                       @show

                        <li class="dropdown">

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ URL::to('workouts') }}" alt="Workoutlist">Workoutlist</a>
                                </li>
                                <li>
                                    <a title="Back to Workout Calendar" href="{{ URL::to('calendar') }}">
                                    Calendar
                                    </a>
                                </li>
                                <li>
                                    <a title="To expenditure chart" href="{{ URL::to('charts') }}">
                                    Charts
                                    </a>
                                </li>
                                <li>
                                    <a title="To expenditure chart" href="{{ URL::to('stats') }}">
                                    Stats
                                    </a>
                                </li>
                                 <li>
                                    <a title="To routes list" href="{{ URL::to('routes') }}">
                                    Routes
                                    </a>
                                </li>
                                <li><a href="{{ URL::to('exercisetypes') }}">Exercisetypes</a></li>
                                 <li><a href="{{ URL::to('exercisesets') }}">Exercise Sets</a></li>
                                <li>
                                    <a href="{{ url('/logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    @show
    <div class="container">
    @yield('content')
    </div>

</body>
</html>
