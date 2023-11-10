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
<h1>Choose Exercise Type</h1>

<!-- if there are creation errors, they will show here -->
@if (count($errors) > 0)
 <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
@endif


   <a class="btn btn-small btn-info" href="{{ URL::to('exercises/create/Body/'.$workout->id. '/') }}">Body</a><br /><br />
   <a class="btn btn-small btn-info" href="{{ URL::to('exercises/create/Weight/'.$workout->id. '/') }}">Weight</a><br /><br />
   <a class="btn btn-small btn-info" href="{{ URL::to('exercises/create/Cardio/'.$workout->id. '/') }}">Cardio</a><br /><br />
   <a class="btn btn-small btn-info" href="{{ URL::to('exercises/create/Static/'.$workout->id. '/') }}">Static</a>





@endsection

