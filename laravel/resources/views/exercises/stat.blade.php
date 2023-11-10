@extends('layouts.app')

@section('title', 'Stats of Exercise {{$name}}')

@section('script')
    <!-- Scripts -->
    <script src="{{ asset('/js/app.js')}}"></script>
@endsection

@section('nav')

@stop
@section('content')

@section('mainnav')
@stop

<h3>{{$info['type']}}</h3>
<div class="container">

@if(count($compareuser) > 0)
  <form class="form-inline" method="get" action="">
   {{ Form::label('name', 'User') }}
      <div class="input-group">
         <div class="col-xs-12">
            <div class="form-group">
              <select class="form-control" id="compare_user_id" name="compare_user_id" class="form-control exercisetype_select">
                  <option value="">Choose User</option>
                @foreach ($compareuser as $u)
                  <option value="{{$u->id}}">{{$u->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <span class="input-group-btn">
               <input type="submit" class="btn btn-default" value="Compare"></input>
              </span>
            </div>
          </div>
       </div>
  </form>
@endif
  <canvas id="stat"></canvas>

</div>

<script>

var ctx = document.getElementById("stat");

var dat = {
    labels: [<?php print_r($dates); ?>],
    datasets: [
      {
        label: '{{$info['labelname']}} (Current User)',
        backgroundColor:'rgba(215, 45, 45, 0.62)',
        borderColor:'rgba(215, 45, 45, 0.62)',
        fill :true,
        strokeColor: "rgba(75, 59, 196, 0.58)",
        pointColor: "rgba(0, 72, 255, 0.6)",
        pointStrokeColor: "rgba(0, 72, 255, 0.6)",
        pointHighlightFill: "rgba(255, 255, 255, 0.54)",
        pointHighlightStroke: "rgba(215, 45, 45, 0.62)",
        data: <?php echo json_encode($exercises); ?>,
        spanGaps: true
      }
      @if(!empty($compstats))
      ,{
        label: '{{$info['labelname']}} (Compared User)',
        backgroundColor:'rgba(45, 65, 164, 0.7)',
        borderColor:'rgba(45, 65, 164, 0.7)',
        fill :true,
        strokeColor: "rgba(45, 65, 164, 0.7)",
        pointColor: "rgba(241, 19, 19, 0.62)",
        pointStrokeColor: "rgba(241, 19, 19, 0.62)",
        pointHighlightFill: "rgba(255, 255, 255, 0.54)",
        pointHighlightStroke: "rgba(45, 65, 164, 0.7)",
        data: <?php echo json_encode($compstats); ?>,
        spanGaps: true
      }
      @endif
    ]
  };

var myChart = new Chart(ctx , {
    type: "line",
    options: {
      tooltips: {
        enabled : true
      },
      scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        beginAtZero: true
                    }
                }]
      }
    },
    data: dat
});

</script>
@endsection
