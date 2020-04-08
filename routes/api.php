<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@store');

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('logout', 'UserController@logout');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
