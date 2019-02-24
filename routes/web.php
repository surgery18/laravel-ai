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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', 'PageController@index')->name('home_page');
Route::get('/editor', 'PageController@new_level')->name('editor');
Route::get('/editor/{level}', 'PageController@select_level');
Route::get('/play/{level}', 'PageController@play_level');
Route::post('/levels', 'LevelController@store');
Route::put('/levels/{level}', 'LevelController@update');
