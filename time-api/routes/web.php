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

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/csv', 'usageController@csvDataSavePanel')->middleware('auth');
Route::post('/csv', 'usageController@csvDataSavePanel')->middleware('auth');

Route::get('/restrictions', 'restrictionController@createRestictionPanel')->middleware('auth');
Route::post('/restrictions', 'restrictionController@restrictionPanel')->middleware('auth');

Route::get('app/detail/{id}','usageController@totalUseAplication');

