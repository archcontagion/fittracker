<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::resource('workouts', 'WorkoutController');
Route::get('calendar', 'WorkoutController@calendar');
Route::get('expenditureyear/{year}','WorkoutController@expenditureyear');
Route::get('expendituremonth/{year}/{month}','WorkoutController@expendituremonth');
Route::get('charts','WorkoutController@charts');
Route::resource('exercises', 'ExerciseController');
Route::get('stats','ExerciseController@recordStats');
Route::post('stats','ExerciseController@recordStats');
Route::get('stat/{type_id}','ExerciseController@exercisesStat');
Route::get('exercises/{id}', 'ExerciseController@index');
Route::get('exercises/create/{id}', 'ExerciseController@typechoice');
Route::get('exercises/create/{type}/{id}', 'ExerciseController@create');
Route::resource('routes', 'RouteController');
Route::put('importexerciseset/{id}','ExerciseController@importSet');
Route::get('deleteallexercises/{id}', 'ExerciseController@destroyAll');
Route::resource('exercisetypes', 'ExercisetypesController');
Route::resource('exercisesets', 'ExercisesetController');
Route::get('newexecisesetjson','ExercisesetController@newexecisesetJson');
Route::get('exercisesetjson/{id}','ExercisesetController@exercisesInSet');


