<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Exception;
use App\Http\Requests;
use App\Exerciseset;
use App\Exercisetype;

class ExercisesetController extends Controller
{
    public function index()
    {

         // get all
        $sets = Exerciseset::orderBy('name','asc')->paginate(10);

        // load the view and pass
        return view('exercisesets.index')
            ->with('exercisesets', $sets);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // load the create form (app/views/exercissets/create.blade.php)
        $exercisetypes = Exercisetype::orderBy('name','asc')->get();
        $exercisesetcount = count(Exerciseset::all());

        return view('exercisesets.create')
         ->with('exercisesetcount', $exercisesetcount)
         ->with('exercisetypes', $exercisetypes);
    }

    public function exercisesInSet($id)
    {
        $set =  Exerciseset::where('id',$id)->get();

        $data = $set[0]->data;

        return $data;

    }

    public function loadJSON($filename) {
        $path = storage_path() . "/json/${filename}.json";

        if (!File::exists($path)) {
            throw new Exception("Invalid File");
        }

        $file = File::get($path); // string

        return json_encode($file);
    }

    public function newexecisesetJson() {
        return json_decode($this->loadJSON('exerciseset_config'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $set = new Exerciseset;

        $this->validate($request, [
            'name' => 'required'
        ]);

        $set->data = $this->loadJSON('exerciseset_config');

        $this->saveExerciseset($set, $request);

        // get all the exerciseset
        $sets = Exerciseset::orderBy('name','asc')->paginate(10);

        // load the view and pass the exerciseset
        return view('exercisesets.index')
            ->with('exercisesets', $sets);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
         // get the exerciseset
        $set = Exerciseset::find($id);


        // show the view and pass the exerciseset to it
        return view('exercisesets.show')
            ->with('exerciseset', $set);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
          // get the exercisesets
        $set = Exerciseset::find($id);
        $exercisetypes = Exercisetype::orderBy('name','asc')->get();
        $exercisetypesinset = [];

        $data = json_decode($set->data);

        foreach ($data as $d) {
            if(isset($d->type_id))
            {
                array_push($exercisetypesinset,Exercisetype::find($d->id));

            }
        }

        // show the edit form and pass the exercisesets
        return view('exercisesets.edit')
            ->with('exercisetypes', $exercisetypes)
            ->with('exercisetypesinset', $exercisetypesinset)
            ->with('exerciseset', $set);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,Request $request)
    {
        $set = Exerciseset::find($id);
        $this->saveExerciseset($set,$request);
        \Session::flash('flash_message', 'Updated Exerciseset!');
        return redirect()->route('exercisesets.index');
    }

    public function saveExerciseset($exerciseset, $request) {
        $exerciseset->name = $request->input('name');
        $exerciseset->data = $request->input('data');
        $exerciseset->save();
        \Session::flash('flash_message', 'Successfully saved the exercise set!');
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
        $set = Exerciseset::find($id);
        $set->delete();

        // redirect
        \Session::flash('flash_message', 'Successfully deleted the exerciseset!');
          // get the exercisesets
        $set = Exerciseset::orderBy('name','asc')->paginate(10);

        // load the view and pass the exerciseset
        return view('exercisesets.index')
            ->with('exercisesets', $set);
    }
}
