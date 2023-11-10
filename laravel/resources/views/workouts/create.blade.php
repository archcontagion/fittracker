@extends('layouts.app')

@section('title', 'Create Workout')

@section('nav')
<li>
<a title="Back to Exercisesetlist" href="{{ URL::to('workouts') }}">
  <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
</a>
</li>
    @parent


@endsection
@section('content')
<h1>Create a Workout</h1>
<!-- if there are creation errors, they will show here -->
@if (count($errors) > 0)
 <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
@endif
{{ Form::open(array('url' => 'workouts')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', 'Session '.($workoutscount+1), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('name', 'Date') }}
        <div class="input-group date">
            {{ Form::text('created_at', Input::old('created_at'), array('class' => 'form-control datefield')) }}
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('name', 'Expenditure') }}
        {{ Form::number('expenditure', Input::old('expenditure'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('name', 'Duration') }}
        {{ Form::time('time', '00:00:00', array('class' => 'form-control durationfield', 'step'=> 1)) }}
    </div>

    <div class="form-group">
        {{ Form::label('name', 'Fit Duration') }}
        {{ Form::time('fittime', '00:00:00', array('class' => 'form-control durationfield', 'step'=> 1)) }}
    </div>

    <div class="form-group">
        {{ Form::label('name', 'Fat Duration') }}
        {{ Form::time('fattime', '00:00:00', array('class' => 'form-control durationfield', 'step'=> 1)) }}
    </div>

    <div class="form-group">
        {{ Form::label('name', 'Heart Avg') }}
        {{ Form::number('heart_avg', Input::old('heart_avg'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('name', 'Heart Max') }}
        {{ Form::number('heart_max', Input::old('heart_max'), array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Create the Workout!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

@endsection

