<?php

use Illuminate\Http\Request;



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//USER 
Route::post('/user', 'UserController@createUser' );
Route::post('/user/login', 'UserController@loginUser' );
Route::get('/user/one/{id}', 'UserController@getUser' );
Route::post('/user/password/reset', 'UserController@sendMail');

//////data and csv
Route::post('save/data','usageController@csvDataSave');
Route::get('usages/{id}','usageController@getUsages');
Route::get('totallocation/{id}','usageController@totalUseLocation');

///////

//////aplications
Route::post('app/create','applicationController@createApplication');
Route::get('app/get/{id}','applicationController@getApp');
Route::delete('app/delete/{id}','applicationController@deleteApp');

///////restrictions
Route::post('restrictions/create','restrictionController@createRestiction');
Route::get('restrictions/getall/{id}','restrictionController@getAllRestrictions');
Route::post('restrictions/update','restrictionController@updateRestriction');
Route::delete('restrictions/delete/{id}','restrictionController@deleteRestriction');
///////


Route::get('app/detail/{id}','usageController@totalUseAplication');


