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

Route::get('/', 'HomeController@index');
Route::get('/list', 'ListController@show');
Route::get('/login', 'LoginController@twitter');
Route::get('/test', function(){
  return "test";
});
Route::get('/login/twitter/callback', 'LoginController@twitterCallback');
Route::get('/twitter/get/tweets', 'TwitterApiController@getTimelineElements');
