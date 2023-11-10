<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Exercise;
use App\User;
use App\Workout;
use App\Exercisetype;
use App\Exerciseset;
use App\Helpers\Helper;

class ExerciseController extends Controller
{
    public function getSortedExercises($e)
    {
        $exarr =[];
        foreach ($e as $key => $value) {
            $value['name'] =  Helper::getExercisetypeName($value->type_id);
            array_push($exarr, $value);
        }
        usort($exarr, $this->sortCrit('name'));

        return $exarr;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($id)
    {
        $exercisetypes = Exercisetype::orderBy('name','asc')->get();
        $workout = Workout::find($id);
        $exercises = Exercise::where('workout_id',$id)->get();
        $exarr = $this->getSortedExercises($exercises);



        // load the create form (app/views/exercises/create.blade.php)
        return view('exercises.create')
            ->with('workout',$workout)
            ->with('exercises',$exarr)
            ->with('exercisetypes', $exercisetypes);
    }

    public function importSet($id, Request $request)
    {
        $workout = Workout::find($id);

        $json = json_decode($request->input('set'));

        foreach($json as $j)
        {   $exercise = new Exercise;
            $j->workout_id = $id;
            $this->saveExercisebyJson($exercise,$j);
        }

        $exercisetypes = Exercisetype::all();
        $exercises = Exercise::where('workout_id',$id)->get();
        $exercisesets = Exerciseset::all();
        $exarr = $this->getSortedExercises($exercises);

        $pageStart = Paginator::resolveCurrentPage();
        $perPage = 10;
        $offSet = ($pageStart * $perPage) - $perPage;
        $items = array_slice($exarr, $offSet, $perPage, true);

        $paginated = new LengthAwarePaginator($items,count($exarr), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));

        return view('exercises.index')
            ->with('workout',$workout)
            ->with('exercises', $paginated)
            ->with('exercisesets',$exercisesets)
            ->with('exercisetypes', $exercisetypes);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $exercise = new Exercise;
        $this->saveExercise($exercise, $request);



        $workout = Workout::find($request->input('workout_id'));
        $exercisetypes = Exercisetype::all();
        $exercisesets = Exerciseset::all();
        $exercises = Exercise::where('workout_id',$request->input('workout_id'))->get();
        $exarr = $this->getSortedExercises($exercises);

        $pageStart = Paginator::resolveCurrentPage();
        $perPage = 10;
        $offSet = ($pageStart * $perPage) - $perPage;
        $items = array_slice($exarr, $offSet, $perPage, true);

        $paginated = new LengthAwarePaginator($items,count($exarr), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));



        return view('exercises.index')
            ->with('workout',$workout)
            ->with('exercises', $paginated)
            ->with('exercisesets',$exercisesets)
            ->with('exercisetypes', $exercisetypes);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
         // get the Exercise
        $exercises = Exercise::where('workout_id',$id)->get();
        $exarr = $this->getSortedExercises($exercises);
        $workout = Workout::find($id);
        $exercisetypes = Exercisetype::all();
        $exercisesets = Exerciseset::all();

        $pageStart = Paginator::resolveCurrentPage();
        $perPage = 10;
        $offSet = ($pageStart * $perPage) - $perPage;
        $items = array_slice($exarr, $offSet, $perPage, true);

        $paginated = new LengthAwarePaginator($items,count($exarr), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));


        // show the view and pass the Exercise to it
        return view('exercises.index')
            ->with('workout',$workout)
            ->with('exercisesets',$exercisesets)
            ->with('exercises', $paginated);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
          // get the exercises
        $exercise = Exercise::find($id);
        $workout = Workout::find($exercise->workout_id);
        $exercisetypes = Exercisetype::all();


        // show the edit form and pass the exercises
        return view('exercises.edit')
            ->with('workout',$workout)
            ->with('exercise', $exercise)
            ->with('exercisetypes',$exercisetypes);
    }

    public function exercisesStat($type_id, Request $request)
    {
        $exercises = Exercise::where('type_id',$type_id)->where('user_id', Auth::id())->get();

        $compareExercises = null;
        $compstats = null;
        $dates = [];

        $type = Exercisetype::where('id',$type_id)->get();


        if (!empty($request->input('compare_user_id')))
        {
         $compareExercises = Exercise::where('user_id', $request->input('compare_user_id'))->where('type_id',$type_id)->get();
         $compstats = $this->fillData($compareExercises,$type[0]);
         $dates =  array_unique (array_merge ($compstats[1], $dates));
        }

        $exstats = $this->fillData($exercises,$type[0]);
        $dates =  array_unique (array_merge ($exstats[1], $dates));


        $users = User::where('id', '!=', Auth::id())->orderBy('name','asc')->get();


        return view('exercises.stat')
            ->with('compareuser',$users)
            ->with('compstats',$compstats[0])
            ->with('exercises',$exstats[0])
            ->with('dates',$dates);
    }

    public function recordStats () {
        $exercises = Exercise::all();

        $btypes = [];
        $wtypes = [];
        $ctypes = [];
        $stypes = [];
        $body=  [];
        $weight=[];
        $cardio=[];
        $static=[];

        foreach ($exercises as $e) {

           if(Exercisetype::find($e->type_id)->type == 'Body')
           {
               $btypes[$e->id] = $e->type_id;
           }
           if(Exercisetype::find($e->type_id)->type == 'Weight')
           {
               $wtypes[$e->id] = $e->type_id;
           }
           if(Exercisetype::find($e->type_id)->type == 'Cardio')
           {
               $ctypes[$e->id] = $e->type_id;
           }
           if(Exercisetype::find($e->type_id)->type == 'Static')
           {
               $stypes[$e->id] = $e->type_id;
           }
        }
        $btypes = array_unique($btypes);
        $wtypes = array_unique($wtypes);
        $ctypes = array_unique($ctypes);
        $stypes = array_unique($stypes);

        foreach ($btypes as $key => $value) {
           if (null !== Helper::getMaxTypeSet($value,'total')[0])
          {
            $tempOb = Helper::getMaxTypeSet($value,'total')[0];
            $tempOb['name'] = Helper::getExercisetypeName($tempOb->type_id);
            array_push($body, $tempOb);
          }
        }
        foreach ($wtypes as $key => $value) {
          if (null !== Helper::getMaxTypeSet($value,'total')[0])
          {
            $tempOb = Helper::getMaxTypeSet($value,'total')[0];
            $tempOb['name'] = Helper::getExercisetypeName($tempOb->type_id);
            array_push($weight, $tempOb);
          }
        }
        foreach ($ctypes as $key => $value) {
          if (null !== Helper::getMaxTypeSet($value,'distance')[0])
          {
            $tempOb = Helper::getMaxTypeSet($value,'distance')[0];
            $tempOb['name'] = Helper::getExercisetypeName($tempOb->type_id);
            array_push($cardio, $tempOb);
          }
        }
        foreach ($stypes as $key => $value) {
          if (null !== Helper::getMaxTypeSet($value,'duration')[0])
          {
            $tempOb = Helper::getMaxTypeSet($value,'duration')[0];
            $tempOb['name'] = Helper::getExercisetypeName($tempOb->type_id);
            array_push($static, $tempOb);
          }
        }

        usort($body, $this->sortCrit('name'));
        usort($weight, $this->sortCrit('name'));
        usort($cardio, $this->sortCrit('name'));
        usort($static, $this->sortCrit('name'));

           return view('exercises.stats')
            ->with('body',$body)
            ->with('weight',$weight)
            ->with('cardio',$cardio)
            ->with('static',$static);

    }

    public function sortCrit($sort) {
      return function($a, $b) use($sort) {
        if($a->$sort > $b->$sort) return 1;
        if($a->$sort < $b->$sort) return -1;
        return 0;
      };
    }

    public function fillData ($exercises,$type)
        {
          $dates = [];
          $exstats = [];
          $label='';
          $etype = $type->type;
          $etypeId = $type->id;


          foreach($exercises as $e)
          {
              $workout = Workout::where('id',$e->workout_id)->get();
              array_push($dates,$workout[0]->created_at);


              switch ($etype)
              {
                case 'Body':
                  array_push($exstats, $e->total);
                  $label = 'Sets * Reps';
                break;
                case 'Weight':
                  array_push($exstats, $e->total);
                  $label = 'Sets * Reps * Weight';
                break;
                case 'Static':
                  array_push($exstats, $e->duration);
                  $label = 'Duration';
                break;
                case 'Cardio':
                  array_push($exstats, $e->distance);
                  $label = 'Distance';
                break;
              }

          }


          return [['type'=> Helper::getExercisetypeName($etypeId),'labelname' => $label, 'data' => $exstats],$dates];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,Request $request)
    {
        $exercise = Exercise::find($id);
        $this->saveExercise($exercise,$request);


        $exercises = Exercise::where('workout_id',$exercise->workout_id)->get();
        $workout = Workout::find($exercise->workout_id);
        $exercisetypes = Exercisetype::all();
        $exarr = $this->getSortedExercises($exercises);
        $exercisesets = Exerciseset::all();

        $pageStart = Paginator::resolveCurrentPage();
        $perPage = 10;
        $offSet = ($pageStart * $perPage) - $perPage;
        $items = array_slice($exarr, $offSet, $perPage, true);

        $paginated = new LengthAwarePaginator($items,count($exarr), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));

        // show the view and pass the Exercise to it
        return view('exercises.index')
            ->with('workout',$workout)
            ->with('exercisesets',$exercisesets)
            ->with('exercises', $paginated)
            ->with('exercisetypes', $exercisetypes);

    }

    public function saveExercise($exercise, $request) {
        $exercise->type_id =(int)$request->input('type_id');
        $exercise->reps = ($request->input('reps')) ? $request->input('reps') : 0;
        $exercise->sets = ($request->input('sets')) ? $request->input('sets') : 0;
        $exercise->total = $request->input('sets') * $request->input('reps');
        $exercise->weight = $request->input('weight');
        $exercise->distance = $request->input('distance');
        $exercise->duration  = $request->input('duration');
        $exercise->workout_id = $request->input('workout_id');
        $exercise->user_id = Auth::user()->id;
        $exercise->save();
        \Session::flash('flash_message', 'Successfully created/updated the exercise!');
    }
    public function saveExercisebyJson($exercise, $json) {
        $j = $json;
        $exercise->type_id =(int)$j->type_id;
        $exercise->reps = (isset($j->reps)) ? $j->reps : 0;
        $exercise->sets = (isset($j->sets)) ? $j->sets : 0;
        $exercise->total = (isset($j->reps) && isset($j->sets)) ? $j->sets * $j->reps : 0;
        $exercise->weight = (isset($j->weight)) ? $j->weight : 0;
        $exercise->distance = (isset($j->distance)) ? $j->distance : 0;
        $exercise->duration  = (isset($j->duration)) ? $j->duration : 0;
        $exercise->workout_id = $j->workout_id;
        $exercise->save();
        \Session::flash('flash_message', 'Successfully created/updated the exercise!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        // delete
        $exercise = exercise::find($id);
        $workId = $exercise->workout_id;
        $exercise->delete();
        \Session::flash('flash_message', 'Successfully deleted the exercise!');

        $exercises = Exercise::where('workout_id',$workId)->get();
        $exercisesets = Exerciseset::all();
        $workout = Workout::find($workId);
        $exercisetypes = Exercisetype::all();
        $exarr = $this->getSortedExercises($exercises);

        $pageStart = Paginator::resolveCurrentPage();
        $perPage = 10;
        $offSet = ($pageStart * $perPage) - $perPage;
        $items = array_slice($exarr, $offSet, $perPage, true);

        $paginated = new LengthAwarePaginator($items,count($exarr), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));



            foreach ($exercisetypes  as $key => $value)
            {
               $typeinfo[$key+1] = $value->name;

            }


        // show the view and pass the Exercise to it
        return view('exercises.index')
            ->with('workout',$workout)
            ->with('exercises', $paginated)
            ->with('exercisesets',$exercisesets)
            ->with('typeinfo', $typeinfo)
            ->with('exercisetypes', $exercisetypes);

    }

    /**
    * Remove all exercises from workout
     *
     * @param  int  $id
     * @return Response
     */
    public function destroyAll($id)
    {
        $workout_id = $id;

        Exercise::where('workout_id', $workout_id)->delete();

        $exercises = Exercise::where('workout_id',$workout_id)->get();
        $exercisesets = Exerciseset::all();
        $workout = Workout::find($workout_id);
        $exercisetypes = Exercisetype::all();
        $exarr = $this->getSortedExercises($exercises);

        $pageStart = Paginator::resolveCurrentPage();
        $perPage = 10;
        $offSet = ($pageStart * $perPage) - $perPage;
        $items = array_slice($exarr, $offSet, $perPage, true);

        $paginated = new LengthAwarePaginator($items,count($exarr), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));

                // show the view and pass the Exercise to it
        return view('exercises.index')
            ->with('workout',$workout)
            ->with('exercises', $paginated)
            ->with('exercisesets',$exercisesets)
            ->with('exercisetypes', $exercisetypes);
    }

}
