<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

use App\Workout;
use App\Exercise;
use App\Exercisetype;
use App\User;

class WorkoutController extends Controller
{



       /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        // get all the workouts
        $workouts = Workout::where('user_id',Auth::user()->id)->orderBy('created_at','desc')->paginate(10);

        // load the view and pass the workouts
        return view('workouts.index')
            ->with('workouts', $workouts);
    }
    public function calendar()
    {

         // get all the workouts
        $workouts = Workout::where('user_id',Auth::user()->id)->get();

        $events = array();

        foreach ($workouts  as $key => $value)
        {
            $jsonob =  new \stdClass();
            $jsonob->title = $value->name;
            $jsonob->start = $value->created_at;
            $jsonob->url = 'workouts/'.$value->id.'/edit';
            array_push($events,$jsonob);

        }


        // load the view and pass the workouts
        return view('workouts.calendar')
            ->with('workoutevents', json_encode($events));
    }

    public function time2seconds($t) {
        $ta = explode(':',$t);
        $ts =0;

        if (count($ta) > 1)
        {
            // hours 2 seconds
            $ts += ((int)$ta[0] * 60 * 60);
            // minutes 2 seconds
            $ts += ((int)$ta[1] *60);
            // seconds added
            $ts += (int)$ta[2];
        }
        return $ts;
    }

    public function charts(Request $request) {
        $curruserid = Auth::user()->id;
        $usersdata = [];

        $dateRange = $request->input('daterange');

        array_push($usersdata, $this->userStats($curruserid,$dateRange));
        $mergeddata = $usersdata;

        if (!empty($request->input('compare_user_id')))
        {
            array_push($usersdata,$this->userStats($request->input('compare_user_id'),$dateRange));


            $dates = array_merge(array_keys($usersdata[0]['dates']),array_keys($usersdata[1]['dates']));


            $mergeddata = [];

            foreach ($usersdata as $u)
            {

                ksort($u['dates']);

                foreach($dates as $d)
                {
                    if(!isset($u['dates'][$d]))
                    { // enter empty dataset
                        $u['dates'][$d] =
                        [
                        'expenditures' => null,
                        'times' =>  null,
                        'heart_avg' =>null,
                        'heart_max' =>null,
                        'fittimes' => null,
                        'fattimes' => null
                        ];
                    }

                }
                    array_push($mergeddata,$u);
            }
        }



        // restructure data
        $restructData = [];
        foreach($mergeddata as $m)
        {
            $dates = [];
            $expenditures = [];
            $times = [];
            $fittimes = [];
            $fattimes = [];
            $heartavg = [];
            $heartmax = [];

            ksort($m['dates']);

           foreach($m['dates'] as $k => $v)
           {
                //date array


                $kformat = \DateTime::createFromFormat('Y-m-d H:i:s',$k)->format('d.m.Y H:i:s');
                array_push($dates,$kformat);
                // expenditure array
                array_push($expenditures,$v['expenditures']);
                // time array
                array_push($times,$v['times']);
                // fittime array
                array_push($fittimes,$v['fittimes']);
                // fattime array
                array_push($fattimes,$v['fattimes']);
                // heart_avg array
                array_push($heartavg,$v['heart_avg']);
                // heart_max array
                array_push($heartmax,$v['heart_max']);
           }

            $dataob =
            [
                'dateData' => $dates,
                'expenditures' => $expenditures,
                'times' => $times,
                'fattimes' => $fattimes,
                'fittimes' => $fittimes,
                'heart_avg' => $heartavg,
                'heart_max' => $heartmax,

            ];
            $dataob['yearcal'] = $m['yearcal'];
            $dataob['currmonthcal'] = $m['currmonthcal'];
            $dataob['lastmonthcal'] = $m['lastmonthcal'];
            if (isset($m['calinrange']))
            {
              $dataob['calinrange'] = $m['calinrange'];
            }
            array_push($restructData,$dataob);
        }



        $users = User::where('id', '!=', Auth::id())->orderBy('name','asc')->get();


        return view('workouts.charts')
                ->with('users', $users)
                ->with('compareuser',$request->input('compare_user_id'))
                ->with('usersdata',$restructData);

    }

    public function userStats($id,$dateRange) {

        $dateRange = preg_split('/-/',$dateRange);


        if (count($dateRange) > 1)
        {

        $startrange = \DateTime::createFromFormat("d.m.Y",trim($dateRange[0],' '))->format('Y-m-d');
        $endrange = \DateTime::createFromFormat("d.m.Y",trim($dateRange[1],' '))->format('Y-m-d');

        $workout = Workout::where('user_id',$id)
             ->whereBetween('created_at', array($startrange, $endrange))
             ->orderBy('created_at','asc')
             ->get();

        }
        else
        {
        $workout = Workout::where('user_id',$id)->orderBy('created_at','asc')
             ->get();
        }
         $data = [];

         $i=1;
        foreach($workout as $w)
        {
           // time in seconds
           $time = $this->time2seconds($w->time);
           // fit and fat percentages
           if ($time > 0)
           {
            $fitpercent = $this->time2seconds($w->fittime) / ($time / 100);
            $fatpercent = $this->time2seconds($w->fattime) / ($time / 100);
           }
           else
           {
            $fitpercent = 0;
            $fatpercent = 0;
           }

            $data[$w->created_at] =
            [
            'expenditures' => $w->expenditure,
            'times' =>  $time / 60 / 60,
            'heart_avg' =>$w->heart_avg,
            'heart_max' =>$w->heart_max,
            'fittimes' => $fitpercent,
            'fattimes' => $fatpercent
            ];

        }



        if (count($dateRange) > 1)
        {
            $datas =  [
                'dates' => $data,
                'yearcal' =>$this->expenditureyear(date('Y')),
                'currmonthcal' => $this->expendituremonth(date('Y'),date('m')),
                'lastmonthcal' => $this->expendituremonth(date('Y'),date('m', strtotime("first day of previous month"))),
                'calinrange' => $this-> expenditureinrange($id,$startrange,$endrange)
            ];

        }
        else
        {
          $datas =  [
            'dates' => $data,
            'yearcal' =>$this->expenditureyear(date('Y')),
            'currmonthcal' => $this->expendituremonth(date('Y'),date('m')),
            'lastmonthcal' => $this->expendituremonth(date('Y'),date('m', strtotime("first day of previous month")))
          ];
        }
        return $datas;
    }


    public function expenditureyear ($year) {

        $workouts = Workout::where('user_id',Auth::user()->id)
                            ->whereYear('created_at', '=', $year)
                            ->get();

        $maxexpenditure =0;

        foreach($workouts as $workout)
        {
            $maxexpenditure += intval($workout->expenditure);
        }

        return $maxexpenditure;

    }
    public function expendituremonth($year,$month) {

        $workouts = Workout::where('user_id',Auth::user()->id)
              ->whereMonth('created_at', '=', $month)
              ->whereYear('created_at', '=', $year)
              ->get();

        $maxexpenditure =0;

        foreach($workouts as $workout)
        {   
            $maxexpenditure += intval($workout->expenditure);
        }

        return $maxexpenditure;
    }
    public function expenditureinrange($uid,$startdate,$enddate) {

        $workouts = Workout::where('user_id',$uid)
              ->whereBetween('created_at', array($startdate, $enddate))
              ->get();

        $maxexpenditure =0;

        foreach($workouts as $workout)
        {
            $maxexpenditure += intval($workout->expenditure);
        }

        return $maxexpenditure;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        $count = count(Workout::where('user_id',Auth::user()->id)->get());
        // load the create form (app/views/workouts/create.blade.php)
        return view('workouts.create')
            ->with('workoutscount', $count);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $workout = new Workout;

        $this->validate($request, [
            'created_at' => 'required'
        ]);


        $this->saveWorkout($workout, $request);

        return redirect()->route('workouts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
         // get the workout
        $workout = Workout::find($id);


        // show the view and pass the workout to it
        return view('workouts.show')
            ->with('workout', $workout);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
          // get the workouts
        $workout = Workout::find($id);
        // show the edit form and pass the workouts
        return view('workouts.edit')
            ->with('workout', $workout);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,Request $request)
    {
        $workout = Workout::find($id);
        $this->saveWorkout($workout,$request);
        \Session::flash('flash_message', 'Updated workout!');
        return redirect()->route('workouts.index');
    }

    public function saveWorkout($workout, $request) {
        $workout->name = $request->input('name');
        $d = \DateTime::createFromFormat("d.m.Y H:i:s",$request->input('created_at'));
        $workout->created_at =$d;
        $workout->expenditure = $request->input('expenditure');
        $t = $request->input('time');
        $fit_t = $request->input('fittime');
        $fat_t = $request->input('fattime');


        $workout->time  = $t;
        $workout->heart_avg = $request->input('heart_avg');
        $workout->heart_max = $request->input('heart_max');
        $workout->fittime  = $fit_t;
        $workout->fattime  = $fat_t;
        $workout->user_id = Auth::user()->id;
        $workout->save();
        \Session::flash('flash_message', 'Successfully saved the workout!');
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
        $workout = Workout::find($id);
        $workout->delete();

        // redirect
        \Session::flash('flash_message', 'Successfully deleted the workout!');
        return redirect()->route('workouts.index');
    }
}
