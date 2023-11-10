@extends('layouts.app')

@section('title', 'Create Workout')


@section('nav')
<li>
       <a title="Create a new Workout" href="{{ URL::to('workouts/create') }}">
       Create a Workout
       </a>
</li>
@stop
@section('content')

<div id="cal_expenditure" class="form-group">
  <div class="well">
      <div class="row">
      <div class="col-xs-4">
          <label class="label  label-success" for="completecalories">Complete calorie expenditure</label>
          <span class="badge" id="totalyear"></span><br/>
          <label class="label  label-success" for="lastmonth">Calorie expenditure of last month</label>
           <span class="badge" id="lastmonth"></span><br/>
          <label class="label  label-success" for="currentmonth">Calorie expenditure of current month</label>
          <span class="badge" id="currentmonth"></span>
      </div>
      <br/>

      </div>
  </div>
</div>

    <div id="calendar"></div>
<script>
function getExpenditureMonth(year, month) {

  var ex = $.ajax({
    type: "GET",
    url: 'expendituremonth/' + year + '/' + month,
    async: false
  }).responseText;

  return (ex != 'None') ? ex : 0;
}

function getExpenditureYear(year) {

  var ex = $.ajax({
    type: "GET",
    url:  'expenditureyear/' + year,
    async: false
  }).responseText;

  return (ex != 'None') ? ex : 0;
}

function setExpenditureInfo(set) {
  var curryear = (new Date()).getFullYear();
  var currmonth = (new Date()).getMonth() + 1;
  if (set) {
    var moment = $('#calendar').fullCalendar('getDate');
    curryear = moment.getFullYear();
    currmonth = moment.getMonth() + 1;
  }


  $('#totalyear','#cal_expenditure').html(getExpenditureYear(curryear));
  $('#lastmonth','#cal_expenditure').html(getExpenditureMonth(curryear, currmonth-1));
  $('#currentmonth','#cal_expenditure').html(getExpenditureMonth(curryear, currmonth));
}


    $(function () {
            $('#calendar').fullCalendar({
                header: {
                  left: 'prev,next today',
                  center: 'title',
                  right: 'month,basicWeek,basicDay'
                },
                defaultDate: new Date(),
                editable: false,
                eventLimit: true, // allow "more" link when too many events
                viewDisplay: function (element) {
                    setExpenditureInfo(true);
                },
                events: <?php echo $workoutevents;?>
              });
          });
         $(function(){
           setExpenditureInfo(true);
         });
</script>

@endsection
