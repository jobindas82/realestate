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
 * Master Routes
 */

//Flat
Route::get('/masters/flat/index', [ 'as' => 'masters.flat_type.index' , 'uses' => 'MasterController@flat_type_index' ])->middleware('auth');
Route::get('/masters/flat/create', [ 'as' => 'masters.flat_type.create' , 'uses' => 'MasterController@flat_type_create' ])->middleware('auth');
Route::post('/masters/flat/getlist', 'MasterController@flat_type_list')->middleware('auth');
Route::get('/masters/flat/create/{key}',[ 'as' => 'masters.flat_type.create' , 'uses' => 'MasterController@flat_type_create' ])->middleware('auth');
Route::post('/masters/flat/save', 'MasterController@flat_type_save')->middleware('auth');

//Construction Type
Route::get('/masters/construction/index', [ 'as' => 'masters.construction_type.index' , 'uses' => 'MasterController@construction_type_index' ])->middleware('auth');
Route::get('/masters/construction/create', [ 'as' => 'masters.construction_type.create' , 'uses' => 'MasterController@construction_type_create' ])->middleware('auth');
Route::post('/masters/construction/getlist', 'MasterController@construction_type_list')->middleware('auth');
Route::get('/masters/construction/create/{key}',[ 'as' => 'masters.construction_type.create' , 'uses' => 'MasterController@construction_type_create' ])->middleware('auth');
Route::post('/masters/construction/save', 'MasterController@construction_type_save')->middleware('auth');

//Country
Route::get('/masters/country/index', [ 'as' => 'masters.country.index' , 'uses' => 'MasterController@country_index' ])->middleware('auth');
Route::get('/masters/country/create', [ 'as' => 'masters.country.create' , 'uses' => 'MasterController@country_create' ])->middleware('auth');
Route::post('/masters/country/getlist', 'MasterController@country_list')->middleware('auth');
Route::get('/masters/country/create/{key}',[ 'as' => 'masters.country.create' , 'uses' => 'MasterController@country_create' ])->middleware('auth');
Route::post('/masters/country/save', 'MasterController@country_save')->middleware('auth');

//Location
Route::get('/masters/location/index', [ 'as' => 'masters.location.index' , 'uses' => 'MasterController@location_index' ])->middleware('auth');
Route::get('/masters/location/create', [ 'as' => 'masters.location.create' , 'uses' => 'MasterController@location_create' ])->middleware('auth');
Route::post('/masters/location/getlist', 'MasterController@location_list')->middleware('auth');
Route::get('/masters/location/create/{key}',[ 'as' => 'masters.location.create' , 'uses' => 'MasterController@location_create' ])->middleware('auth');
Route::post('/masters/location/save', 'MasterController@location_save')->middleware('auth');


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
Route::get('/users/create/{key}',[ 'as' => 'users.edit' , 'uses' => 'UserController@create' ])->middleware('auth');
//Save or Update User
Route::post('/users/update',[ 'as' => 'users.update', 'uses' => 'UserController@update' ])->middleware('auth');
//Save or Update User
Route::post('/users/store',[ 'as' => 'users.store', 'uses' => 'UserController@store' ])->middleware('auth');
//Update password
Route::post('/users/changepword',[ 'as' => 'users.changepword', 'uses' => 'UserController@changepword' ])->middleware('auth');
