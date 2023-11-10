<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link href="{{ asset('/css/app.css')}}" rel="stylesheet">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
       <script src="{{ asset('/js/jquery.min.js')}}"></script>
       <script>
            $(function(){
              var fitrackerintervall,fittrackeraudio;


              $('#start').on('click',function(){
                intervallSound(true);
              });
              $('#end').on('click',function(){
                intervallSound(false);
              });


              function intervallSound(status) {


                if (status)
                {
                  fittrackeraudio = new Audio($(':selected','#sound').val());
                  fittrackeraudio.play();

                  fitrackerintervall = setInterval(function(){
                    fittrackeraudio.play();

                  }, (parseInt($(':selected','#sound').attr('data-duration'),10) + parseInt($('#time').val(),10))*1000);
                }
                else
                {
                  clearInterval(fitrackerintervall);
                }
              };

            });
        </script>


    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    <!-- <a href="{{ url('/login') }}">Login</a> -->
                    <!-- <a href="{{ url('/register') }}">Register</a> -->
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Fit Tracker
                </div>
    <div class="intervallTimer">
     <div class="row">
        <label for="time">Pause Time between Intervalls</label>
        <input class="form-control" type="text" id="time" name="time"/>
    </div>
    <div class="row">
        <label for="sound">Select Sound</label>
        <select class="form-control" id="sound" name="sound">
          <option data-duration="6" value="{{ URL::asset('/sounds/bell1.wav') }}">Bell1 (6 sec.)</option>
          <option data-duration="29" value="{{ URL::asset('/sounds/bell2.wav') }}">Bell2 (29 sec.)</option>
          <option data-duration="5" value="{{ URL::asset('/sounds/bell3.wav') }}">Bell3 (5 sec.)</option>
          <option data-duration="15" value="{{ URL::asset('/sounds/bell4.wav') }}">Bell4 (15 sec.)</option>
          <option data-duration="13" value="{{ URL::asset('/sounds/bell5.wav') }}">Bell5 (13 sec.)</option>
          <option data-duration="100" value="{{ URL::asset('/sounds/bell6.wav') }}">Bell6 (1 min.)</option>
        </select>
    </div>
    <br/>
    <div class="row">
            <div class="button-group" role="group">
                <button class="btn btn-secondary" type="button" value="Start" id="start" name="start">Start</button>
                <button class="btn btn-secondary" type="button" value="End" id="end" name="end">End</button>
            </div>
    </div>
    </div>
                <div class="links">
                    <a href="{{ URL::to('calendar') }}">Calendar</a>
                    <a href="{{ URL::to('workouts') }}">Workout lists</a>
                    <a href="{{ URL::to('charts') }}">Charts</a>
                    <a href="{{ URL::to('stats') }}">Stats</a>
                    <a href="{{ URL::to('routes') }}">Routes</a>
                </div>
            </div>
        </div>
    </body>
</html>
