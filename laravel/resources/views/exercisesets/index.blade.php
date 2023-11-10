@extends('layouts.app')

@section('title', 'Workouts')

@section('nav')
<li>
       <a title="Create a new exercise set" href="{{ URL::to('exercisesets/create') }}">
            Create an exercise set
       </a>
</li>

@stop
@section('content')
<h1>All the exercise sets</h1>

<!-- if there are creation errors, they will show here -->
@if (count($errors) > 0)
 <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
@endif

@include('modal')

<!-- will be used to show any messages -->
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif


    @foreach($exercisesets as $set)
    <div class="row">
        <div class="col-xs-6">
            <h4>{{$set->name}}</h4>
        </div>
        <div class="col-xs-6">
        <a  style="margin:0 5px 0 5px;" class="btn btn-small btn-info" href="{{ URL::to('exercisesets/' . $set->id . '/edit') }}">
            <span class="btntext">Edit this Exerciseset</span>
            <span class="btnicon">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            </span>

        </a>
        {{ Form::open(['method' => 'DELETE', 'class' => 'inlineForm form-delete', 'route' => ['exercisesets.destroy', $set->id]]) }}
            {{ Form::hidden('id', $set->id) }}
            {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
        {{ Form::close() }}
        </div>
    </div>
    @endforeach
    {{$exercisesets->links()}}

@endsection



