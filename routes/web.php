<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/room', 'RoomController@index')->name('room');

Route::resource('project', 'ProjectController');

$projects = \App\Project::all();
foreach ($projects as $project){
    Route::get($project->name, 'ProjectController@show');
}

Route::group(['domain' => '{projects}.localhost'], function () {
    Route::get('projects{{name}}}', function ($projects) {
        //
    });
});