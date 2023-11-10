@extends('layouts.app')

@section('title', 'Edit Workout')

@section('nav')
<li>
    <a href="{{ URL::to('exercisetypes/create') }}">Create a Exercisetype</a>
</li>
@stop
@section('content')
<h1>All the exercisetypes</h1>

<!-- if there are creation errors, they will show here -->
@if (count($errors) > 0)
 <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
@endif

<!-- will be used to show any messages -->
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

<table class="table table-striped table-bordered">
    <thead>

            <td>Name</td>
            <td>Type</td>
            <td>Actions</td>
        </tr>
    </thead>
    <tbody>
    @foreach($exercisetypes as $key => $value)
        <tr>
            <td>{{ $value->name }}</td>
            <td>{{ $value->type }}</td>

            <!-- we will also add show, edit, and delete buttons -->
            <td>

                <!-- delete the Workout (uses the destroy method DESTROY /exercisetypes/{id} -->
                <!-- we will add this later since its a little more complicated than the other two buttons -->

                <!-- edit this Workout (uses the edit method found at GET /exercisetypes/{id}/edit -->
                <a class="btn btn-small btn-info" href="{{ URL::to('exercisetypes/' . $value->id . '/edit') }}">
                   <span class="btntext">Edit this Exercise Type</span>
                   <span class="btnicon">
                     <span class="glyphicon glyphicon-edit" aria-hidden="true">
                   </span>

                </a>


                <!-- delete this Workout (uses the edit method found at GET /workouts/{id}/edit -->
                {{ Form::open(['method' => 'DELETE', 'class' => 'inlineForm', 'route' => ['exercisetypes.destroy', $value->id]]) }}
                    {{ Form::hidden('id', $value->id) }}
                    {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                {{ Form::close() }}

            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{$exercisetypes->links()}}
@endsection
