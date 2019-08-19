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

Route::group(['prefix' => '/auth', ['middleware' => 'throttle:20,5']], function() {
    Route::post('/register', 'Auth\RegisterController@register');
    Route::post('/login', 'Auth\LoginController@login');
});

/*
|--------------------------------------------------------------------------
| User and Posts Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'jwt.auth'], function() {
    Route::get('/me', 'MeController@index');
    Route::get('/auth/logout', 'MeController@logout');
    //voting
    Route::get('/vote', 'PostsController@vote');
    Route::get('/num_votes', 'PostsController@countVotes');
    //posting
    Route::post('/send_post', 'PostsController@publishPost');
    Route::post('/update_post', 'PostsController@updatePost');
    //hashtag
    
});

Route::get('/posts', 'PostsController@show');
Route::get('/test', 'PostsController@index');