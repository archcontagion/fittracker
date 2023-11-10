@extends('layouts.app')

@section('title', 'Edit Workout')
@section('mainnav')

@endsection
@section('nav')

@endsection
@section('content')

<!-- if there are creation errors, they will show here -->
@if (count($errors) > 0)
 <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
@endif

        <div class="form-group">
            <input id="routescoordinates" type="hidden" value="{{$route->coordinates}}"></input>
            {{ Form::label('name', 'Distance (km)') }}
            <div>{{$route->distance}}</div>
        </div>


        <div id="map" class="map"></div>
        <input type="hidden" id="geodesic">
        <input type="hidden" name="mapswitch" value="show" checked="checked"/>

      <br/>


        @include('mapscript')


@endsection
