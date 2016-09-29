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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/get-template/{id}', 'MainController@getTemplates');
Route::get('/get-templates/', 'MainController@getAllTemplates');

Route::get('/get-screen/{id}', 'MainController@getScreen');
Route::get('/get-screens/', 'MainController@getAllScreens');
Route::get('/get-screens-children/{id}', 'MainController@getScreenDescendant');

