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


//Authentication Routes
Auth::routes();

//Default Routes
Route::get('/', 'HomeController@index')->middleware('auth');
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');


/**
 * User Master Routes
 */
//User List
Route::get('/users/index', [ 'as' => 'users.index' , 'uses' => 'UserController@index' ])->middleware('auth');
//AJAX DATA
Route::post('/users/getlist', 'UserController@userlist')->middleware('auth');
//Create new user
Route::get('/users/create', 'UserController@create')->middleware('auth');
//Edit existing User
Route::get('/users/create/{id}', 'UserController@create')->middleware('auth');
//Save or Update User
Route::post('/users/update',[ 'as' => 'users.update', 'uses' => 'UserController@update' ])->middleware('auth');
