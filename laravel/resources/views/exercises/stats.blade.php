@extends('layouts.app')

@section('title', 'Exercise Stats')

@section('nav')


@endsection
@section('content')


<h1>Stats of all exercises of current user</h1>


<form method="post" action="{{ URL::to('stats')}}">
 {{ csrf_field() }}
<div class="row">

<div class="form-group col-xs-4">
{{Form::selectMonth('month', $selectmonth, ['class'=>'form-control','name' => 'selectmonth'])}}
</div>
<div class="form-group col-xs-4">
{{Form::selectYear('year', '2016', $currentyear ,$selectyear, ['class'=>'form-control','name' => 'selectyear'])}}
</div>
<div class="form-group  col-xs-4">
<input class="btn btn-info" value="Select Date" type="submit"></input>
</div>
</div>
</form>

<div class="well">
    <div class="row">
      <ul class="list-group">
        <li class="list-group-item">
          <span class="badge badge-default badge-pill pull-xs-right">{{$completecalories}}</span>
          Complete calorie expenditure
        </li>
        <li class="list-group-item">
            <span class="badge badge-default badge-pill pull-xs-right">
            {{$selectedmonthcal}}
            </span>
            Calorie expenditure of selected month
        </li>
        <li class="list-group-item">
            <span class="badge badge-default badge-pill pull-xs-right">
            {{$currentmonthcal}}
            </span>
            Calorie expenditure of current month
        </li>
        <li class="list-group-item">
            <span class="badge badge-default badge-pill pull-xs-right">
            {{$currentmonthcal - $selectedmonthcal}}
            </span>
            Diff. Current / Selected
        </li>
    </div>
   </div>
</div>
<!-- will be used to show any messages -->
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

  <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li class="active"><a href="#body" data-toggle="tab">Body</a></li>
        <li><a href="#weight" data-toggle="tab">Weight</a></li>
        <li><a href="#static" data-toggle="tab">Static</a></li>
        <li><a href="#cardio" data-toggle="tab">Cardio</a></li>
    </ul>
    <div id="my-tab-content" class="tab-content">
        <div class="tab-pane active" id="body">
            <h2>Body Exercises Record Amount</h2>

            <table class="table table-striped table-bordered">
                <thead>

                        <th>Name</th>
                        <th>Reps</th>
                        <th>Sets</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                @foreach($body as $b)
                    <tr>
                        <td>{{ $b->name}}</td>
                        <td>{{ $b->reps }}</td>
                        <td>{{ $b->sets }}</td>
                        <td>{{ $b->total }}</td>
                        <td>
                            <button data-href="{{URL::to('stat/'.$b->type_id)}}" type="button" title="Stats of exercise" class="exercisestatsModal btn btn-info"  data-toggle="modal" data-target="#statsModal">
                              <span class="glyphicon glyphicon-stats"></span>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="weight">
            <h2>Weight Exercises  Record Amount</h2>
            <table class="table table-striped table-bordered">
                <thead>

                        <th>Name</th>
                        <th>Reps</th>
                        <th>Sets</th>
                        <th>Weight (kg)</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                @foreach($weight as $w)
                    <tr>
                        <td>{{ $w->name}}</td>
                        <td>{{ $w->reps }}</td>
                        <td>{{ $w->sets }}</td>
                        <td>{{ $w->weight }}</td>
                        <td>{{ $w->total }}</td>
                        <td>
                            <button data-href="{{URL::to('stat/'.$w->type_id)}}" type="button" title="Stats of exercise" class="exercisestatsModal btn btn-info"  data-toggle="modal" data-target="#statsModal">
                              <span class="glyphicon glyphicon-stats"></span>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="static">
            <h2>Static Exercises  Records</h2>
            <table class="table table-striped table-bordered">
                <thead>
                        <th>Name</th>
                        <th>Duration</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                @foreach($static as $s)
                    <tr>
                        <td>{{$s->name}}</td>
                        <td>{{$s->duration }}</td>
                        <td>
                            <button data-href="{{URL::to('stat/'.$s->type_id)}}" type="button" title="Stats of exercise" class="exercisestatsModal btn btn-info"  data-toggle="modal" data-target="#statsModal">
                              <span class="glyphicon glyphicon-stats"></span>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="cardio">
            <h2>Cardio Exercises  Record Distance</h2>
            <table class="table table-striped table-bordered">
                <thead>
                        <th>Name</th>
                        <th>Duration</th>
                        <th>Distance (km)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                @foreach($cardio as $c)
                    <tr>
                        <td>
                            <button data-href="{{URL::to('stat/'.$c->type_id)}}" type="button" title="Stats of exercise" class="exercisestatsModal btn btn-info"  data-toggle="modal" data-target="#statsModal">
                              <span class="glyphicon glyphicon-stats"></span>
                            </button>
                        </td>
                        <td>{{$c->name}}</td>
                        <td>{{$c->duration }}</td>
                        <td>{{$c->distance }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
</div>

  <!-- Modal -->
  <div class="modal fade" id="statsModal" role="dialog">
    <div class="modal-dialog full-screen">
      <div class="modal-content">
        <div class="modal-header">


          <h4 class="modal-title">Exercise Stats</h4>
        </div>
        <div class="modal-body">
          <iframe frameborder="0" scrolling="no" class="modaliframe" height="100%" width="100%"></iframe>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>



@endsection

