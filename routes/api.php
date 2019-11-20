<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::prefix('auth')->group(function(){
    Route::get('init','AppController@init');

    Route::post('login','AppController@login');
    Route::post('loginMobile','AppController@loginMobile');
    Route::post('register','AppController@register');
    Route::post('registerMobile','AppController@registerMobile');
    Route::post('logout','AppController@logout');
});

Route::get('/test',function(){
	echo "Hello";
});


Route::group([ 'prefix'=>'v1'], function() {
    
    Route::resource('category','CategoryController');
    Route::resource('complaint','ComplaintController');
    Route::resource('enotice','EnoticeController');
    Route::get('getMessages','EnoticeController@index');
    Route::post('getStudentComplaints','ComplaintController@getStudentComplaints');
    Route::put('retractComplaint/{id}','ComplaintController@update');
    Route::put('updateComplaint/{id}','ComplaintController@update');
    Route::get('fetchPendingComplaint','ComplaintController@fetchPendingComplaint');
   // Route::resource('enotice', 'EnoticeController');
});
