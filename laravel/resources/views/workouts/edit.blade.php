@extends('layouts.app')

@section('title', 'Edit Workout')

@section('nav')
<li>
<a title="Back to Exercisesetlist" href="{{ URL::to('workouts') }}">
  <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
</a>
</li>
    @parent


@endsection
@section('content')
<h1>Edit {{ $workout->name }}</h1>

<!-- if there are creation errors, they will show here -->
@if (count($errors) > 0)
 <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
@endif

{{ Form::model($workout, array('route' => array('workouts.update', $workout->id), 'method' => 'PUT')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group input-group date">
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
        {{ Form::time('time', gmdate("H:i:s",strtotime($workout->time)), array('class' => 'form-control durationfield', 'step' => 1)) }}
    </div>

    <div class="form-group">
        {{ Form::label('name', 'Fit Duration') }}
        {{ Form::time('fittime', gmdate("H:i:s",strtotime($workout->fittime)), array('class' => 'form-control durationfield', 'step' => 1)) }}
    </div>

    <div class="form-group">
        {{ Form::label('name', 'Fat Duration') }}
        {{ Form::time('fattime', gmdate("H:i:s",strtotime($workout->fattime)), array('class' => 'form-control durationfield', 'step' => 1)) }}
    </div>

    <div class="form-group">
        {{ Form::label('name', 'Heart Avg') }}
        {{ Form::number('heart_avg', Input::old('heart_avg'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('name', 'Heart Max') }}
        {{ Form::number('heart_max', Input::old('heart_max'), array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Edit Workout', array('class' => 'btn btn-primary')) }}
    <!-- edit this Workout (uses the edit method found at GET /workouts/{id}/edit -->
    <a class="btn btn-small btn-info" href="{{ URL::to('exercises/' . $workout->id . '/') }}">List Exercises</a>


{{ Form::close() }}
@endsection

