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
<h1>Create a Exercise ({{$type_id}})</h1>

<!-- if there are creation errors, they will show here -->
@if (count($errors) > 0)
 <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
@endif

{{ Form::open(array('url' => 'exercises')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        <select name="type_id" class="form-control exercisetype_select">
           @foreach($exercisetypes as $key => $value)
                <option data-type="{{$value->type}}" value="{{$value->id}}">{{$value->name}}</option>
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

    <div class="form-group weight">
        {{ Form::label('name', 'Weight') }}
        {{ Form::number('weight', Input::old('weight'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group cardio static">
        {{ Form::label('name', 'Duration') }}
        {{ Form::time('duration','00:00:00', array('class' => 'form-control durationfield', 'step' => 1)) }}
    </div>

    <div class="form-group cardio">
        {{ Form::label('name', 'Distance') }}
        {{ Form::text('distance', Input::old('distance'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::hidden('workout_id', $workout->id, array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Create the Exercise!', array('class' => 'btn btn-primary')) }}
    <button type="button" class="btn btn-info cardio" data-toggle="modal" data-target="#routeModal">Choose Route</button>
      <!-- Modal -->
      <div class="modal fade" id="routeModal" role="dialog">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">

              <button type="button" class="close" data-dismiss="modal">&texts;</button>
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




@endsection

