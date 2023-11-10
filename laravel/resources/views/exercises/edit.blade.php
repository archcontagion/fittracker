@extends('layouts.app')

@section('title', 'Edit Workout')

@section('nav')
<li>
<a title="Back to Workoutlist" href="{{ URL::to('exercises/'.$workout->id. '/') }}">
  <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
</a>
</li>
<li>
    <a title="Create a new Exercise" href="{{ URL::to('exercises/create/'.$workout->id. '/') }}">Create a Exercise</a>
</li>

@endsection
@section('content')
<h1>Edit Exercise</h1>

<!-- if there are creation errors, they will show here -->
@if (count($errors) > 0)
 <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
@endif

{{ Form::model($exercise, array('route' => array('exercises.update', $exercise->id), 'method' => 'PUT')) }}



    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        <select name="type_id" class="form-control exercisetype_select">
           @foreach($exercisetypes as $key => $value)

                @if ($value->id == $exercise->type_id)
                <option selected data-type="{{$value->type}}" value="{{$value->id}}">{{$value->name}}</option>
                @else
                <option data-type="{{$value->type}}" value="{{$value->id}}">{{$value->name}}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="form-group weight body">
        {{ Form::label('name', 'Sets') }}
        {{ Form::number('sets', Input::old('sets'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group weight body">
        {{ Form::label('name', 'Reps') }}
        {{ Form::number('reps', Input::old('reps'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group weight cardio">
        {{ Form::label('name', 'Weight') }}
        {{ Form::number('weight', Input::old('weight'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group cardio static">
        {{ Form::label('name', 'Duration') }}
        {{ Form::text('duration', Input::old('duration'), array('class' => 'form-control durationfield')) }}
    </div>

    <div class="form-group cardio">
        {{ Form::label('name', 'Distance') }}
        {{ Form::text('distance', Input::old('distance'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::hidden('workout_id', $workout->id, array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Edit Exercise', array('class' => 'btn btn-primary')) }}
    <button type="button" class="btn btn-info cardio" data-toggle="modal" data-target="#routeModal">Choose Route</button>
  <!-- Modal -->
  <div class="modal fade" id="routeModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Import Route</h4>
        </div>
        <div class="modal-body">
            <?php  $routes->prepend('Select Route', 0);?>
            {{ Form::select('route_id', $routes, Input::old('route_id'),['class' => 'form-control']) }}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
{{ Form::close() }}
@if($exercise->route_id != 0)
<h4>Choosen Route</h4>
    <iframe frameborder="0" height="300" width="500" scrolling="no" src="{{URL::to('routes/'.$exercise->route_id)}}"/>
@endif

@endsection
