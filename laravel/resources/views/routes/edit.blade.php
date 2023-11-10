@extends('layouts.app')

@section('title', 'Edit Workout')

@section('nav')
<li>
<a title="Back to Routeslist" href="{{ URL::to('routes') }}">
  <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
</a>
</li>
<li>
  <a href="{{ URL::to('routes/create') }}">Create a Route</a>
</li>
@endsection
@section('content')
<h1>Edit {{ $route->name }}</h1>

<!-- if there are creation errors, they will show here -->
@if (count($errors) > 0)
 <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
@endif

{{ Form::model($route, array('route' => array('routes.update', $route->id), 'method' => 'PUT', 'id' => 'routeForm')) }}


    {{ Form::submit('Edit Route', array('class' => 'btn btn-primary', 'id' => 'routesubmit')) }}
    {{ Form::button('Delete the Route!', array('class' => 'btn btn-danger', 'id' => 'removeRoute')) }}
    <br/>
    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
        {{ Form::hidden('coordinates', Input::old('coordinates'), array('class' => 'form-control', 'id' => 'routescoordinates')) }}
        {{ Form::label('name', 'Distance (km)') }}
        {{ Form::text('distance', Input::old('distance'), array('class' => 'form-control', 'id' => 'routesdistance', 'readonly' => 'true')) }}
    </div>

    <div class="form-group">
        <input type="radio" value="show" checked="checked" name="mapswitch">View Mode</input>
        <input type="radio" value="edit" name="mapswitch">Edit Mode</input>
    </div>
    <div style="display:none;" class="mapeditButtons" id="mapeditButtons">
      <button type="button" class="btn btn-default" id="undolastMapLine">Undo</button>
      <button type="button" class="btn btn-default" id="redolastMapLine">Redo</button>
    </div>
    <div id="map" class="map"></div>
    <input type="hidden" id="geodesic"  checked="checked">
  </div>

    @include('mapscript')

{{ Form::close() }}
@endsection
