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

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::get('kosts', 'API\KostController@index');
Route::get('kosts/{id}', 'API\KostController@show');

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('details', 'API\UserController@details');
    Route::put('upgrade_status', 'API\UserController@upgradeStatus');
    Route::post('logout', 'API\UserController@logout');

    //Kost
    Route::resource('kosts', 'API\KostController', ['except' => 'index', 'show']);

    //Activity
    Route::post('activities/ask', 'API\ActivityController@askQuestion');
});
