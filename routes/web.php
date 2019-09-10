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
Route::get('/users/create', [ 'as' => 'users.create' , 'uses' => 'UserController@create' ])->middleware('auth');
//Edit existing User
Route::get('/users/create/{id}',[ 'as' => 'users.edit' , 'uses' => 'UserController@create' ])->middleware('auth');
//Save or Update User
Route::post('/users/update',[ 'as' => 'users.update', 'uses' => 'UserController@update' ])->middleware('auth');
//Save or Update User
Route::post('/users/store',[ 'as' => 'users.store', 'uses' => 'UserController@store' ])->middleware('auth');
//Update password
Route::post('/users/changepword',[ 'as' => 'users.changepword', 'uses' => 'UserController@changepword' ])->middleware('auth');
