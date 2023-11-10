@extends('layouts.app')

@section('title', 'Create Exerciseset')

@section('nav')
<li>
<a title="Back to Exercisesetlist" href="{{ URL::to('exercisesets') }}">
  <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
</a>
</li>
<li>
        <a title="Create a new Exerciseset" href="{{ URL::to('exercisesets/create') }}">Create a exerciseset</a>
</li>

@endsection
@section('content')
<h1>Create a Exerciseset</h1>

<!-- if there are creation errors, they will show here -->
@if (count($errors) > 0)
 <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
@endif

{{ Form::model($exerciseset, array('route' => array('exercisesets.update', $exerciseset->id), 'method' => 'PUT')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
    </div>

    <div ng-app="ngApp" ng-controller="ngCtrl" ng-init="loadData({{$exerciseset->id}})" class="form-group">
        <table class="exercisetable table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Sets</th>
                    <th>Reps</th>
                    <th>Weight</th>
                    <th>Duration</th>
                    <th>Distance</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="setexercise in setexercises" id="setexercise<%setexercise.id%>">
                    <td>

                    <input type="hidden" ng-model="setexercise.id" value="<%setexercise.id%>">
                    <select name="type_id" ng-model="setexercise.type_id" class="form-control exercisetype_select">
                       @foreach($exercisetypes as $key => $value)
                           <option ng-selected="{{$value->id}} == <%setexercise.type_id%>" value="{{$value->id}}">{{$value->name}}</option>
                       @endforeach
                    </select>

                    </td>
                    <td><input class="form-control" type="number" ng-model="setexercise.sets" value="<%setexercise.sets%>"></input></td>
                    <td><input class="form-control" type="number" ng-model="setexercise.reps" value="<%setexercise.reps%>"></input></td>
                    <td><input class="form-control" type="number" ng-model="setexercise.weight" value="<%setexercise.weight%>"></input></td>
                    <td><input class="form-control" type="time" ng-model="setexercise.duration" value="<%setexercise.duration%>"></input></td>
                    <td><input class="form-control" type="number" ng-model="setexercise.distance" value="<%setexercise.distance%>"></input></td>
                    <td><button type="button" data-itemId="<%$index%>" ng-click="deleteExercise($event)" class="btn btn-danger">Delete</button></td>
                </tr>
            </tbody>
        </table>
        <div class="form-group">
            <buton ng-click="addExercise()" class="btn btn-info">Add exercise</buton>
        </div>
        <div class="form-group">
            <input name="data" type="hidden" class="form-control" value="<% setexercises | json %>"/>
        </div>
    </div>
    <div class="form-group">
        {{ Form::hidden('id', $exerciseset->id, array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Edit the exerciseset!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
@endsection

