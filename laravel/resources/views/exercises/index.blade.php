@extends('layouts.app')

@section('title', 'Edit Workout')

@section('nav')
<li>
<a title="Back to Workoutlist" href="{{ URL::to('workouts') }}">
  <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
</a>
</li>
<li>
<a title="Create a new Exercise" href="{{ URL::to('exercises/create/'.$workout->id. '/') }}">Create a Exercise</a>
</li>


@endsection
@section('content')
<h1>All the exercises of {{$workout->name}}</h1>


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
            <td></td>
            <td>Name</td>
            <td>Sets</td>
            <td>Reps</td>
            <td>Weight</td>
            <td>Total (S * R * W)</td>
            <td>Duration</td>
            <td>Distance</td>
            <td>Avg. Speed (km/h)</td>
            <td>Avg. Time per km</td>
        </tr>
    </thead>
    <tbody>

    @foreach($exercises as $key => $value)
        <tr>
            <!-- we will also add show, edit, and delete buttons -->
            <td>

                <!-- delete the Exercise (uses the destroy method DESTROY /exercises/{id} -->
                <!-- we will add this later since its a little more complicated than the other two buttons -->

                <!-- edit this Exercise (uses the edit method found at GET /exercises/{id}/edit -->
                <a class="btn btn-small btn-info" href="{{ URL::to('exercises/' . $value->id . '/edit') }}">Edit</a>
                <!-- delete this Workout (uses the edit method found at GET /workouts/{id}/edit -->
                {{ Form::open(['method' => 'DELETE', 'class' => 'inlineForm', 'route' => ['exercises.destroy', $value->id]]) }}
                    {{ Form::hidden('id', $value->id) }}
                    <input type="submit" class="btn btn-danger" value="Delete"></input>
                {{ Form::close() }}
                <button data-href="{{URL::to('stat/'.$value->type_id)}}" type="button" title="Stats of exercise" class="exercisestatsModal btn btn-info"  data-toggle="modal" data-target="#statsModal">
                  <span class="glyphicon glyphicon-stats"></span>
                </button>
            </td>
            <td>{{ $value->name}}</td>
            @if(Helper::getExerciseNametype($value->type_id) != 'Cardio')
            <td>{{ $value->sets }}</td>
            @else
            <td>-</td>
            @endif
            @if(Helper::getExerciseNametype($value->type_id) != 'Cardio')
            <td>{{ $value->reps }}</td>
            @else
            <td>-</td>
            @endif
            @if(Helper::getExerciseNametype($value->type_id) != 'Body')
            <td>{{ $value->weight }}</td>
            @else
            <td>-</td>
            @endif
            @if(Helper::getExerciseNametype($value->type_id) != 'Cardio')
            <td>{{ $value->total }}</td>
            @else
            <td>-</td>
            @endif
            @if(Helper::getExerciseNametype($value->type_id) == 'Cardio')
             <td>{{ $value->duration }}</td>
            @else
            <td>-</td>
            @endif

            @if(Helper::getExerciseNametype($value->type_id) == 'Cardio')
            <td>{{ $value->distance }}</td>
            @else
            <td>-</td>
            @endif
            @if(Helper::getExerciseNametype($value->type_id) == 'Cardio')
            <td>{{Helper::avgSpeed($value->distance, $value->duration)}}
            @else
            <td>-</td>
            @endif
            @if(Helper::getExerciseNametype($value->type_id) == 'Cardio')
            <td>{{Helper::avgTimeperKm($value->distance, $value->duration)}}
            @else
            <td>-</td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
{{$exercises->links()}}
<br/>
<button type="button" class="btn btn-default" data-toggle="modal" data-target="#importModal">Import Exerciseset</button>
<button type="button" data-href="{{URL::to('deleteallexercises/'.$workout->id)}}" class="btn btn-danger btn" data-toggle="modal" data-target="#confirmModal">Delete all exercises</button>

  <!-- Modal -->
  <div class="modal fade" id="confirmModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Delete all exercises from workout?</h4>
        </div>
        <div class="modal-body">
          <p>Do you want to delete all?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger">Delete</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="importModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Import Exerciseset</h4>
        </div>
        {{ Form::open(['method' => 'PUT', 'class' => 'inlineForm', 'url' => ['importexerciseset', $workout->id]]) }}
        <div class="modal-body">

         <select name="set" class="form-control">
             @foreach($exercisesets as $set)
                <option value="{{$set->data}}">{{$set->name}}</option>
             @endforeach
         </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-ok">Select</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="statsModal" role="dialog">
    <div class="modal-dialog full-screen">
      <div class="modal-content">
        <div class="modal-header">


          <h4 class="modal-title">Exercise Stats</h4>
        </div>
        <div class="modal-body">
          <iframe frameborder="0" scrolling="no" class="modaliframe" height="100%" width="100%"></iframe>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection

