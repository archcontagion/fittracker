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
use App\Route;
use App\Helpers\Helper;

class ExerciseController extends Controller
{


    public function index($id)
    {

        $exercisetypes = Exercisetype::orderBy('name','asc')->get();
        $workout = Workout::find($id);
        $exercises = Exercise::where('workout_id',$id)->get();
        $exarr = $this->getSortedExercises($exercises);
        $routes = Route::orderBy('name','asc')->pluck('name', 'id');

        $pageStart = Paginator::resolveCurrentPage();
        $perPage = 10;
        $offSet = ($pageStart * $perPage) - $perPage;
        $items = array_slice($exarr, $offSet, $perPage, true);

        $paginated = new LengthAwarePaginator($items,count($exarr), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));


        // load the view and pass
        return view('exercises.index')
            ->with('workout',$workout)
            ->with('exercises',$paginated)
            ->with('exercisetypes', $exercisetypes)
            ->with('routes',$routes);
    }


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
    public function create($type_id,$id)
    {
        $exercisetypes = Exercisetype::where('type',$type_id)->orderBy('name','asc')->get();
        $workout = Workout::find($id);
        $exercises = Exercise::where('workout_id',$id)->get();
        $exarr = $this->getSortedExercises($exercises);
        $routes = Route::orderBy('name','asc')->pluck('name', 'id');


        $pageStart = Paginator::resolveCurrentPage();
        $perPage = 10;
        $offSet = ($pageStart * $perPage) - $perPage;
        $items = array_slice($exarr, $offSet, $perPage, true);

        $paginated = new LengthAwarePaginator($items,count($exarr), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));


        // load the create form (app/views/exercises/create.blade.php)
        return view('exercises.create')
            ->with('workout',$workout)
            ->with ('type_id',$type_id)
            ->with('exercises',$paginated)
            ->with('exercisetypes', $exercisetypes)
            ->with('routes',$routes);
    }

    public function typechoice($id) {

       $workout = Workout::find($id);
       // load the create form (app/views/exercises/typechoice.blade.php)
        return view('exercises.typechoice')
            ->with('workout',$workout);
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
        $routes = Route::orderBy('name','asc')->pluck('name', 'id');

        $pageStart = Paginator::resolveCurrentPage();
        $perPage = 10;
        $offSet = ($pageStart * $perPage) - $perPage;
        $items = array_slice($exarr, $offSet, $perPage, true);

        $paginated = new LengthAwarePaginator($items,count($exarr), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
        $paginated->setPath('/exercises/'.$request->input('workout_id'));


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
        $routes = Route::orderBy('name','asc')->pluck('name', 'id');

        // show the edit form and pass the exercises
        return view('exercises.edit')
            ->with('workout',$workout)
            ->with('exercise', $exercise)
            ->with('exercisetypes',$exercisetypes)
            ->with('routes',$routes);
    }

    public function exercisesStat($type_id, Request $request)
    {
        $exercises = Exercise::where('type_id',$type_id)->where('user_id', Auth::id())->get();

        $compareExercises = null;
        $compstats = null;
        $excompare = [];
        $exresult = [];
        $dates = [];

        $type = Exercisetype::where('id',$type_id)->get();


        if (!empty($request->input('compare_user_id')))
        {
         $compareExercises = Exercise::where('user_id', $request->input('compare_user_id'))->where('type_id',$type_id)->get();

          $compstats = $this->fillData($compareExercises,$type[0]);

          foreach($compstats['data'] as $key => $value)
          {
            array_push($dates,$key);
          }

        }
        $exstats = $this->fillData($exercises,$type[0]);
        $info = ['type' => $exstats['type'], 'labelname' => $exstats['labelname']];


        foreach($exstats['data'] as $key => $value)
        {
          array_push($dates,$key);
        }


        // only user who have data on exercisetype and are not current user
        $users = User::where('id', '!=', Auth::id())->orderBy('name','asc')->get();
        foreach($users as $key => $value)
        {
          if(count(Exercise::where('type_id',$type_id)->where('user_id',$value->id)->get()) == 0)
          {
            unset($users[$key]);
          }
        }
        $dates = array_unique($dates);
        sort($dates);

        // fill gaps in range
        foreach($dates as $key => $value)
        {

          if(isset($exstats['data'][$value]))
          {
             $exresult[$key]= $exstats['data'][$value];
          }
          else
          {
            $exresult[$key]= null;
          }
          if (!empty($request->input('compare_user_id')))
          {
            if(isset($compstats['data'][$value]))
            {
              $excompare[$key] = $compstats['data'][$value];
            }
            else
            {
              $excompare[$key] = null;
            }
          }
        }


        $dates ="'" . implode("','", $dates) . "'";


        return view('exercises.stat')
            ->with('compareuser',$users)
            ->with('info',$info)
            ->with('compstats',$excompare)
            ->with('exercises',$exresult)
            ->with('dates',$dates);
    }

    public function recordStats (Request $request) {
        $exercises = Exercise::where('user_id', Auth::id())->get();

        $allworkouts = Workout::where('user_id', Auth::id())->get();

        $firstday = new \DateTime('first day of this month');
        $lastday = new \DateTime('last day of this month');
        $currentyear = date("Y");
        $lmonth = $firstday->modify( 'last month' );
        $lyear = $lmonth->format('Y');
        $lmonth = ltrim($lmonth->format('m'), '0');
        $selectyear =  (!empty($request->input('selectyear'))) ? $request->input('selectyear') : $lyear;
        $selectmonth = (!empty($request->input('selectmonth'))) ? $request->input('selectmonth') : $lmonth;
        $currentmonth = Workout::where('user_id', Auth::id())->whereMonth('created_at', '=', date("m"))->whereYear('created_at','=',$selectyear)->get();

        $dateObj   = \DateTime::createFromFormat('!m.Y', $selectmonth.'.'.$selectyear);
        $monthName = $dateObj->format('F');

        $firstday = (!empty($request->input('selectmonth'))) ? new \DateTime('first day of '.$monthName)   : new \DateTime('first day of last month');
        $lastday =  (!empty($request->input('selectmonth'))) ? new \DateTime('last day of '.$monthName)   : new \DateTime('last day of last month');

        $selectedmonth  = Workout::where('user_id', Auth::id())->whereMonth('created_at', '=', $selectmonth)->whereYear('created_at','=',$selectyear)->get();

        $completecalories = 0;
        $currentmonthcal  = 0;
        $selectedmonthcal = 0;

        foreach($allworkouts as $w)
        {
          if (is_numeric($w->expenditure)) {	
            $completecalories += $w->expenditure;
      	  }
        }
        foreach( $currentmonth as $w)
        {
          if (is_numeric($w->expenditure)) {
            $currentmonthcal += $w->expenditure;
          }  
        }
        foreach($selectedmonth as $w)
        {
        	if (is_numeric($w->expenditure)) {
              $selectedmonthcal += $w->expenditure;
            }  
        }

        $btypes = [];
        $wtypes = [];
        $ctypes = [];
        $stypes = [];
        $body=  [];
        $weight=[];
        $cardio=[];
        $static=[];

        foreach ($exercises as  $e) {


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
            ->with('currentyear',$currentyear)
            ->with('selectmonth',$lmonth)
            ->with('selectyear',$selectyear)
            ->with('completecalories',$completecalories)
            ->with('selectedmonthcal',$selectedmonthcal)
            ->with('currentmonthcal',$currentmonthcal)
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

          $exstats = [];
          $label='';
          $etype = $type->type;
          $etypeId = $type->id;


          foreach($exercises as $e)
          {
              $workout = Workout::where('id',$e->workout_id)->get();

              switch ($etype)
              {
                case 'Body':
                  $exstats[$workout[0]->created_at] = $e->total;
                  $label = 'Sets * Reps';
                break;
                case 'Weight':
                  $exstats[$workout[0]->created_at] = $e->total;
                  $label = 'Sets * Reps * Weight';
                break;
                case 'Static':
                  $exstats[$workout[0]->created_at] = $e->duration;
                  $label = 'Duration';
                break;
                case 'Cardio':
                  $exstats[$workout[0]->created_at] = $e->distance;
                  $label = 'Distance';
                break;
              }

          }


          return ['type'=> Helper::getExercisetypeName($etypeId),'labelname' => $label, 'data' => $exstats];
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
        $paginated->setPath('/exercises/'.$id);
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
        $exercise->total = ($request->input('reps') && $request->input('sets')) ? $request->input('sets') * $request->input('reps') : 0;
        $exercise->weight = $request->input('weight');
        $exercise->distance = (Route::find($request->input('route_id')) != null) ? Route::find($request->input('route_id'))->distance : $request->input('distance');
        $exercise->duration  = $request->input('duration');
        $exercise->workout_id = $request->input('workout_id');
        $exercise->user_id = Auth::user()->id;
        $exercise->route_id = ($request->input('route_id') == 0) ? null : $request->input('route_id');
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
        $exercise->user_id = Auth::user()->id;
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
        $paginated->setPath('/exercises/'.$id);


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
        $paginated->setPath('/exercises/'.$id);
                // show the view and pass the Exercise to it
        return view('exercises.index')
            ->with('workout',$workout)
            ->with('exercises', $paginated)
            ->with('exercisesets',$exercisesets)
            ->with('exercisetypes', $exercisetypes);
    }

}
