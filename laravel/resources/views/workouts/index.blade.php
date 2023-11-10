@extends('layouts.app')

@section('title', 'Workouts')

@section('nav')
<li>
       <a title="Create a new Workout" href="{{ URL::to('workouts/create') }}">
            Create a Workout
       </a>
</li>

@stop
@section('content')
<h1>All the workouts</h1>

<!-- will be used to show any messages -->
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

@include('modal')

<table class="table table-striped table-bordered workoutlist"  data-toggle="dataTable" data-form="deleteForm">
    <thead>

            <td>Actions</td>
            <td>Name</td>
            <td>Date</td>
        </tr>
    </thead>
    <tbody>
    @foreach($workouts as $key => $value)
        <tr>
 <td>
  <!-- we will also add show, edit, and delete buttons -->
                <!-- delete the Workout (uses the destroy method DESTROY /workouts/{id} -->
                <!-- we will add this later since its a little more complicated than the other two buttons -->

                <!-- edit this Workout (uses the edit method found at GET /workouts/{id}/edit -->
                <a class="btn btn-small btn-info" href="{{ URL::to('workouts/' . $value->id . '/edit') }}">
                    <span class="btntext">Edit this Workout</span>
                    <span class="btnicon">
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    </span>
                </a>
                <!-- edit this Workout (uses the edit method found at GET /workouts/{id}/edit -->
                <a class="btn btn-small btn-info" href="{{ URL::to('exercises/' . $value->id . '/') }}">
                    <span class="btntext">List Exercises</span>
                    <span class="btnicon">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </span>
                </a>

                <!-- delete this Workout (uses the edit method found at GET /workouts/{id}/edit -->
                {{ Form::open(['method' => 'DELETE', 'class' => 'inlineForm form-delete', 'route' => ['workouts.destroy', $value->id]]) }}
                    {{ Form::hidden('id', $value->id) }}
                    <button class="btn btn-danger" name="delete_modal">
                        <span class="btntext">Delete Workout</span>
                        <span class="btnicon">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                        </span>
                    </button>

                {{ Form::close() }}

            </td>
            <td>{{ $value->name }} <span class="badge">{{$value->expenditure}}</span></td>
            <td>{{ date('d.m.Y H:i:s', strtotime($value->created_at)) }}</td>



        </tr>
    @endforeach
    </tbody>
</table>
{{$workouts->links()}}

@endsection



