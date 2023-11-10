@extends('layouts.app')

@section('title', 'Stats Charts')


@section('nav')

@stop
@section('content')
<div class="container">

<form class="form-inline" method="get" action="">
<div class="form-group">
  {{Form::label('name','Daterange')}}
  {{ Form::text('daterange', Input::old('daterange'), array('class' => 'form-control daterange')) }}
</div>
@if(count($users) > 0)

      <div class="form-group">

        <div class="form-group">
          {{ Form::label('name', 'User') }}
          <select class="form-control compareuser" id="compare_user_id" name="compare_user_id" class="form-control exercisetype_select">
              <option value="">Choose User</option>
            @foreach ($users as $u)
              <option {{ ($compareuser == $u->id) ? 'selected' : '' }} value="{{$u->id}}">{{$u->name}}</option>
            @endforeach
          </select>
        </div>

       </div>
@endif
<div class="form-group">
  <div class="input-group">
    <span class="input-group-btn">
     <input type="submit" class="btn btn-default" value="Send"></input>
    </span>
      <span class="input-group-btn">
     <input type="button" class="btn btn-default resetFilter" value="Reset Filter"></input>
    </span>
  </div>
</div>
</form>
<br/>

 <dl>

  <dt>Total of year</dt>
  <dd>
  {{$usersdata[0]['yearcal']}}
  @if(count($usersdata) > 1)
  <span class="compareuser">({{$usersdata[1]['yearcal']}})</span>
  @endif
  </dd>
  <dt>Last month (cal)</dt>
  <dd>
  {{$usersdata[0]['lastmonthcal']}}
  @if(count($usersdata) > 1)
  <span class="compareuser">({{$usersdata[1]['lastmonthcal']}})</span>
  @endif
  </dd>
  <dt>Current month (cal)</dt>
  <dd>
  {{$usersdata[0]['currmonthcal']}}
  @if(count($usersdata) > 1)
  <span class="compareuser">({{$usersdata[1]['currmonthcal']}})</span>
  @endif
  </dd>
  @if(isset($usersdata[0]['calinrange']))
  <dt>In Daterange (cal)</dt>
  <dd>
  {{$usersdata[0]['calinrange']}}
  @if(count($usersdata) > 1)
  <span class="compareuser">({{$usersdata[1]['calinrange']}})</span>
  @endif
  </dd>
  @endif
</dl>


    <div class="row">
        <div class="col-xs-12">
            <canvas id="expenditure"></canvas>
        </div>
        <div class="col-xs-12">
            <canvas id="times"></canvas>
        </div>
        @if (count($usersdata) > 1)
        <div class="col-xs-6">
            <canvas id="duration"></canvas>
        </div>
        <div class="col-xs-6">
            <canvas id="duration2"></canvas>
        </div>
        @else
        <div class="col-xs-12">
            <canvas id="duration"></canvas>
        </div>
        @endif
        <div class="col-xs-12">
            <canvas id="heart"></canvas>
        </div>
    </div>
</div>

<script  defer>


var usercolor1 = 'rgba(242, 48, 48, 0.5)',
usercolor2 = 'rgba(48, 48, 242, 0.5)',
compareusercolor1 = 'rgba(224, 205, 62, 0.5)' ,
compareusercolor2 = 'rgba(62, 224, 78, 0.5)',
highlightcolor = 'rgba(255, 255, 255, 0.6)';


var ctx = document.getElementById("expenditure"),
 ctx2 = document.getElementById("duration"),
 @if (count($usersdata) > 1)
 ctx2_1 = document.getElementById("duration2"),
 @endif
 ctx3 = document.getElementById("times"),
 ctx4 = document.getElementById("heart");

var dat = {
    labels: <?php echo json_encode($usersdata[0]['dateData']); ?>,
    datasets: [
      {
        label: "Expenditure Calories",
        backgroundColor: usercolor1,
        borderColor: usercolor1,
        fill :true,
        strokeColor: usercolor1,
        pointColor: usercolor1,
        pointStrokeColor: usercolor1,
        pointHighlightFill: highlightcolor,
        pointHighlightStroke: usercolor1,
        data: <?php echo json_encode($usersdata[0]['expenditures']); ?>,
        spanGaps: true
      }
      @if(count($usersdata) > 1)
      ,{
        label: 'Expenditure Calories (Compared User)',
        backgroundColor:compareusercolor1,
        borderColor:compareusercolor1,
        fill :true,
        strokeColor: compareusercolor1,
        pointColor: compareusercolor1,
        pointStrokeColor: compareusercolor1,
        pointHighlightFill: highlightcolor,
        pointHighlightStroke: compareusercolor1,
        data: <?php echo json_encode($usersdata[1]['expenditures']); ?>,
        spanGaps: true
      }
      @endif
    ]
  };
var dat2 = {
    labels: <?php echo json_encode($usersdata[0]['dateData']); ?>,
    datasets: [
     {
        label: "Fat Duration Ratio (%)",
        backgroundColor: usercolor1,
        borderColor:usercolor1,
        fill :true,
        fillColor: usercolor1,
        strokeColor: usercolor1,
        pointColor: usercolor1,
        pointStrokeColor: usercolor1,
        pointHighlightFill: highlightcolor,
        pointHighlightStroke: usercolor1,
        data: <?php echo json_encode($usersdata[0]['fattimes']); ?>,
        spanGaps: true
      },
      {
        label: "Fit Duration Ratio (%)",
        backgroundColor:usercolor2,
        borderColor:usercolor2,
        fill :true,
        fillColor: usercolor2,
        strokeColor:usercolor2,
        pointColor: usercolor2,
        pointStrokeColor: highlightcolor,
        pointHighlightFill: usercolor2,
        pointHighlightStroke:usercolor2,
        data: <?php echo json_encode($usersdata[0]['fittimes']); ?>,
        spanGaps: true
      }
    ]
  };
@if (count($usersdata) > 1)
var dat2_1 = {
    labels: <?php echo json_encode($usersdata[1]['dateData']); ?>,
    datasets: [
      {
        label: 'Fat Duration Ratio (%)  (C. User)',
        backgroundColor:compareusercolor1,
        borderColor:compareusercolor1,
        fill :true,
        strokeColor:compareusercolor1,
        pointColor: compareusercolor1,
        pointStrokeColor: compareusercolor1,
        pointHighlightFill: highlightcolor,
        pointHighlightStroke: compareusercolor1,
        data: <?php echo json_encode($usersdata[1]['fattimes']); ?>,
        spanGaps: true
      },
      {
        label: "Fit Duration Ratio (%)  (C. User)",
        backgroundColor:compareusercolor2,
        borderColor:compareusercolor2,
        fill :true,
        fillColor:compareusercolor2,
        strokeColor: compareusercolor2,
        pointColor: compareusercolor2,
        pointStrokeColor: compareusercolor2,
        pointHighlightFill: highlightcolor,
        pointHighlightStroke: compareusercolor2,
        data: <?php echo json_encode($usersdata[1]['fittimes']); ?>,
        spanGaps: true
      }
    ]
  };
@endif
  var dat3 = {
    labels: <?php echo json_encode($usersdata[0]['dateData']); ?>,
    datasets: [
      {
        label: "Time (Hours)",
        backgroundColor: usercolor1,
        fill :true,
        strokeColor:usercolor1,
        pointColor: usercolor1,
        pointStrokeColor: highlightcolor,
        pointHighlightFill: highlightcolor,
        pointHighlightStroke: usercolor1,
        data: <?php echo json_encode($usersdata[0]['times']); ?>,
        spanGaps: true
      }
      @if(count($usersdata) > 1)
      ,{
        label: 'Time (Compared User)',
        backgroundColor:compareusercolor1,
        borderColor:compareusercolor1,
        fill :true,
        strokeColor: compareusercolor1,
        pointColor: compareusercolor1,
        pointStrokeColor:highlightcolor,
        pointHighlightFill: highlightcolor,
        pointHighlightStroke: compareusercolor1,
        data: <?php echo json_encode($usersdata[1]['times']); ?>,
        spanGaps: true
      }
      @endif
    ]
  };
  var dat4 = {
    labels: <?php echo json_encode($usersdata[0]['dateData']); ?>,
    datasets: [
      {
        label: "Heartrate (avg.)",
        fill:false,
        backgroundColor:usercolor1,
        strokeColor:usercolor1,
        borderColor:usercolor1,
        pointStrokeColor:usercolor1,
        data: <?php echo json_encode($usersdata[0]['heart_avg']); ?>,
        spanGaps: true
      },
      {
        label: "Heartrate (max.)",
         fill:false,
        backgroundColor:usercolor2,
        strokeColor:usercolor2,
        borderColor:usercolor2,
        pointStrokeColor:usercolor2,
        data: <?php echo json_encode($usersdata[0]['heart_max']); ?>,
        spanGaps: true
      }
      @if(count($usersdata) > 1)
      ,{
        label: "Heartrate (avg.) (C. User)",
        fill:false,
        backgroundColor:compareusercolor1,
        strokeColor:compareusercolor1,
        borderColor:compareusercolor1,
        pointStrokeColor:compareusercolor1,
        data: <?php echo json_encode($usersdata[1]['heart_avg']); ?>,
        spanGaps: true
      },
      {
        label: "Heartrate (max.) (C. User)",
        fill:false,
        backgroundColor:compareusercolor2,
        strokeColor:compareusercolor2,
        borderColor:compareusercolor2,
        pointStrokeColor:compareusercolor2,
        data: <?php echo json_encode($usersdata[1]['heart_max']); ?>,
        spanGaps: true
      }
      @endif
    ]
  };
var myChart = new Chart(ctx , {
    type: "line",
    options: {
    scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        beginAtZero: true
                    }
                }]
      },
      tooltips: {
        enabled : true
      }
    },
    data: dat
});

var myChart2 = new Chart(ctx2 , {
    type: "bar",
    data: dat2,
    barPercentage : 0.4,
    options: {
        scales: {
                xAxes: [{
                        stacked: true
                }],
                yAxes: [{
                        stacked: true
                }]
        }
    }
});
@if(count($usersdata) > 1)
var myChart2_1 = new Chart(ctx2_1 , {
    type: "bar",
    data: dat2_1,
    barPercentage : 0.4,
    options: {
        scales: {
                xAxes: [{
                        stacked: true
                }],
                yAxes: [{
                        stacked: true
                }]
        }
    }
});
@endif
var myChart3 = new Chart(ctx3 , {
    type: "line",
    options: {
      scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        beginAtZero: true
                    }
                }]
      },
      tooltips: {
        enabled : true
      }
    },
    data: dat3
});
var myChart4 = new Chart(ctx4 , {
    type: "line",
    options: {
    scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        beginAtZero: true
                    }
                }]
      },
      tooltips: {
        enabled : true
      }
    },
    data: dat4
});

</script>
@endsection
