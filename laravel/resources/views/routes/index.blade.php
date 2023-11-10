@extends('layouts.app')

@section('title', 'Edit Workout')

@section('nav')
<li>
    <a href="{{ URL::to('routes/create') }}">Create a Route</a>
</li>


@endsection
@section('content')

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
        </tr>
    </thead>
    <tbody>
    @foreach($routes as $key => $value)
        <tr>
            <td>{{ $value->name }}</td>

            <!-- we will also add show, edit, and delete buttons -->
            <td>

                <!-- delete the Workout (uses the destroy method DESTROY /routes/{id} -->
                <!-- we will add this later since its a little more complicated than the other two buttons -->

                <!-- edit this Workout (uses the edit method found at GET /routes/{id}/edit -->
                <a class="btn btn-small btn-info" href="{{ URL::to('routes/' . $value->id . '/edit') }}">Edit this route</a>


                <!-- delete this Workout (uses the edit method found at GET /workouts/{id}/edit -->
                {{ Form::open(['method' => 'DELETE', 'class' => 'inlineForm', 'route' => ['routes.destroy', $value->id]]) }}
                    {{ Form::hidden('id', $value->id) }}
                    {{ Form::submit('Delete the route', ['class' => 'btn btn-danger']) }}
                {{ Form::close() }}

            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{$routes->links()}}
@endsection
