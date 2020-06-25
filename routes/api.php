<?php

use Illuminate\Http\Request;


Route::group(['prefix' => 'v1'], function()
{
     Route::resource('meeting', 'MeetingController', [
          'except' => ['create','edit']
     ]);

     Route::resource('meeting/register', 'RegisterController', [
          'only' => ['store','destroy']
     ]);

     Route::post('user/register', [
          'uses' => 'AuthController@store'
     ]);

     Route::post('user/signin', [
          'uses' => 'AuthController@signin'
     ]);
});
