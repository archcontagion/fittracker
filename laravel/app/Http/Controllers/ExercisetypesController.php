<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;
use App\Exercisetype;


class ExercisetypesController extends Controller
{



       /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
         // get all the exercisetype
        $exercisetype = ExerciseType::orderBy('name','asc')->paginate(10);

        // load the view and pass the exercisetype
        return view('exercisetypes.index')
            ->with('exercisetypes', $exercisetype);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // load the create form (app/views/exercistypes/create.blade.php)
        return view('exercisetypes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $exercisetype = new Exercisetype;

        $this->validate($request, [
            'name' => 'required'
        ]);


        $this->saveExercisetype($exercisetype, $request);

        // get all the exercisetype
        $exercisetype = ExerciseType::orderBy('name','asc')->paginate(10);

        // load the view and pass the exercisetype
        return view('exercisetypes.index')
            ->with('exercisetypes', $exercisetype);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
         // get the exercisetype
        $exercisetype = Exercisetype::find($id);


        // show the view and pass the exercisetype to it
        return view('exercisetypes.show')
            ->with('exercisetype', $exercisetype);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
          // get the exercisetypes
        $exercisetype = Exercisetype::find($id);

        // show the edit form and pass the exercisetypes
        return view('exercisetypes.edit')
            ->with('exercisetype', $exercisetype);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,Request $request)
    {
        $exercisetype = Exercisetype::find($id);
        $this->saveExercisetype($exercisetype,$request);
        \Session::flash('flash_message', 'Updated Exercisetype!');
        return redirect()->route('exercisetypes.index');
    }

    public function saveExercisetype($exercisetype, $request) {
        $exercisetype->name = $request->input('name');
        $exercisetype->type = $request->input('type');
        $exercisetype->save();
        \Session::flash('flash_message', 'Successfully saved the exercise type!');
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
        $exercisetype = Exercisetype::find($id);

        try {
        $exercisetype->delete();
        // redirect
        \Session::flash('flash_message', 'Successfully deleted the exercisetype!');

        } catch ( \Illuminate\Database\QueryException $e) {

             return redirect()->route('exercisetypes.index')->withErrors(substr($e->errorInfo[2],0,stripos($e->errorInfo[2],' (')));
        }


          // get the exercisetypes
        $exercisetype = Exercisetype::orderBy('name','asc')->paginate(10);

        // load the view and pass the exercisetype
        return view('exercisetypes.index')
            ->with('exercisetypes', $exercisetype);
    }
}
