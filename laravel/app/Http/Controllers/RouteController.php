<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Route;


class RouteController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
         // get all the route
        $route = Route::orderBy('name','asc')->paginate(10);

        // load the view and pass the route
        return view('routes.index')
            ->with('routes', $route);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // load the create form (app/views/exercistypes/create.blade.php)
        return view('routes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $route = new route;

        $this->validate($request, [
            'name' => 'required'
        ]);


        $this->saveroute($route, $request);

        // get all the route
        $route = Route::orderBy('name','asc')->paginate(10);

        // load the view and pass the route
        return view('routes.index')
            ->with('routes', $route);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
         // get the route
        $route = Route::find($id);


        // show the view and pass the route to it
        return view('routes.show')
            ->with('route', $route);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
          // get the routes
        $route = Route::find($id);

        // show the edit form and pass the routes
        return view('routes.edit')
            ->with('route', $route);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,Request $request)
    {
        $route = Route::find($id);
        $this->saveroute($route,$request);
        \Session::flash('flash_message', 'Updated route!');
        return redirect()->route('routes.index');
    }

    public function saveroute($route, $request) {
        $route->name = $request->input('name');
        $route->coordinates = $request->input('coordinates');
        $route->distance = $request->input('distance');
        $route->save();
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
        $route = Route::find($id);

        try {
        $route->delete();
        // redirect
        \Session::flash('flash_message', 'Successfully deleted the route!');

        } catch ( \Illuminate\Database\QueryException $e) {

             return redirect()->route('routes.index')->withErrors(substr($e->errorInfo[2],0,stripos($e->errorInfo[2],' (')));
        }


          // get the routes
        $route = Route::orderBy('name','asc')->paginate(10);

        // load the view and pass the route
        return view('routes.index')
            ->with('routes', $route);
    }

}
