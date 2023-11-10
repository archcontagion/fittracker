@extends('layouts.app')

@section('title', 'Edit Workout')

@section('nav')
<li>
<a title="Back to Exercisetypeslist" href="{{ URL::to('exercisetypes') }}">
  <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
</a>
</li>
<li>
  <a href="{{ URL::to('exercisetypes/create') }}">Create a Exercisetype</a>
</li>
@endsection
@section('content')
<h1>Edit {{ $exercisetype->name }}</h1>

<!-- if there are creation errors, they will show here -->
@if (count($errors) > 0)
 <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
@endif

{{ Form::model($exercisetype, array('route' => array('exercisetypes.update', $exercisetype->id), 'method' => 'PUT')) }}


    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">

        {{ Form::label('name', 'Type') }}
        <select name="type" class="form-control">
          <option value="Body" {{ $exercisetype->type == 'Body' ? 'selected' : '' }}>Body</option>
          <option value="Weight" {{ $exercisetype->type == 'Weight' ? 'selected' : '' }}>Weight</option>
          <option value="Cardio" {{ $exercisetype->type == 'Cardio' ? 'selected' : '' }}>Cardio</option>
          <option value="Static" {{ $exercisetype->type == 'Static' ? 'selected' : '' }}>Static</option>
        </select>
    </div>

    {{ Form::submit('Edit Exercise Type', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@endsection
