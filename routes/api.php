<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@store');

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('logout', 'UserController@logout');

    Route::get('admin/{limit}/{offset}', "AdminController@getAll"); //read pelanggaran
	Route::post('admin', 'AdminController@store'); //create pelanggaran
	Route::put('admin/{id}', "AdminController@update"); //update pelanggaran
	Route::delete('admin/{id}', "AdminController@delete"); //delete pelanggaran
    
    Route::get('tujuan/{limit}/{offset}', "TujuanController@getAll"); //read pelanggaran
	Route::post('tujuan', 'TujuanController@store'); //create pelanggaran
	Route::put('tujuan/{id}', "TujuanController@update"); //update pelanggaran
	Route::delete('tujuan/{id}', "TujuanController@delete"); //delete pelanggaran
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
