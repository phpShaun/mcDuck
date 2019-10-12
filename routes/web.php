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

Route::get('/home',         'DuckController@index')->name('ducks.home'); // Displays Duck Data
Route::get('/ducks',        'DuckController@index')->name('ducks'); // Displays Duck Data
Route::get('/ducks/create', 'DuckController@create')->name('ducks.create'); // Displays Duck Create form

Route::post('/ducks',           'DuckController@store')->name('ducks.store'); // Create a new Duck
Route::post('/ducks/{id}/feed', 'DuckController@feed')->middleware('validateDuck')->name('ducks.feed'); // Feed the duck
Route::post('/ducks/{id}/heal', 'DuckController@heal')->middleware('validateDuck')->name('ducks.heal'); // Heal the duck
Route::post('/ducks/{id}/play', 'DuckController@play')->middleware('validateDuck')->name('ducks.play'); // play with the duck