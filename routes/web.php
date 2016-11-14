<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

#Admin Routes
Route::get('admin/login', 'Backend\AuthController@redirectToGoogle');
Route::get('admin/logout', 'Backend\AuthController@logout');
Route::get('admin/callback', 'Backend\AuthController@handleGoogleCallback');


Route::get('admin', 'Backend\HomeController@index');

Route::get('test', 'Backend\MenuController@index');


Route::get('list-menu', 'Backend\MenuController@getMenus');

Route::get('list-sub-menu/{menu}/{menuType}', 'Backend\MenuController@getSubMenus');

Route::get('list-content/{menu}/{menuType}', 'Backend\MenuController@getContents');

Route::get('create-menu', 'Backend\MenuController@createMenuView');

Route::get('view-menu/{id}', 'Backend\MenuController@getMenuDetail');

Route::get('view-sub-menu/{id}', 'Backend\MenuController@getSubMenuDetail');

Route::get('create-sub-menu/{menu}/{menuType}', 'Backend\MenuController@createSubMenuView');

Route::get('create-content/{menu}/{menuType}', 'Backend\MenuController@createContentView');

Route::post('create_menu_form', 'Backend\MenuController@createMenu');

Route::post('update_menu_form', 'Backend\MenuController@updateMenu');

Route::post('create_sub_menu_form', 'Backend\MenuController@createSubMenu');

Route::post('update_sub_menu_form', 'Backend\MenuController@updateSubMenu');

Route::post('create_content_form', 'Backend\MenuController@createContent');

Route::resource('admin/posts', 'Backend\PostsController');
Route::resource('admin/categories', 'Backend\CategoriesController');

#Frontend Routes
Route::get('/', 'Frontend\MainController@index');
Route::get('home', 'Frontend\MainController@home');
Route::get('login', 'Frontend\AuthController@redirectToAuthServer');
Route::get('logout', 'Frontend\AuthController@logout');
Route::get('callback', 'Frontend\AuthController@callback');

