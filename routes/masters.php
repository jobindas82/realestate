<?php

/**
 * Master Routes
 */

//Flat
Route::get('/masters/flat/index', ['as' => 'masters.flat_type.index', 'uses' => 'MasterController@flat_type_index'])->middleware('auth');
Route::get('/masters/flat/create', ['as' => 'masters.flat_type.create', 'uses' => 'MasterController@flat_type_create'])->middleware('auth');
Route::post('/masters/flat/getlist', 'MasterController@flat_type_list')->middleware('auth');
Route::get('/masters/flat/create/{key}', ['as' => 'masters.flat_type.create', 'uses' => 'MasterController@flat_type_create'])->middleware('auth');
Route::post('/masters/flat/save', 'MasterController@flat_type_save')->middleware('auth');

//Construction Type
Route::get('/masters/construction/index', ['as' => 'masters.construction_type.index', 'uses' => 'MasterController@construction_type_index'])->middleware('auth');
Route::get('/masters/construction/create', ['as' => 'masters.construction_type.create', 'uses' => 'MasterController@construction_type_create'])->middleware('auth');
Route::post('/masters/construction/getlist', 'MasterController@construction_type_list')->middleware('auth');
Route::get('/masters/construction/create/{key}', ['as' => 'masters.construction_type.create', 'uses' => 'MasterController@construction_type_create'])->middleware('auth');
Route::post('/masters/construction/save', 'MasterController@construction_type_save')->middleware('auth');

//Country
Route::get('/masters/country/index', ['as' => 'masters.country.index', 'uses' => 'MasterController@country_index'])->middleware('auth');
Route::get('/masters/country/create', ['as' => 'masters.country.create', 'uses' => 'MasterController@country_create'])->middleware('auth');
Route::post('/masters/country/getlist', 'MasterController@country_list')->middleware('auth');
Route::get('/masters/country/create/{key}', ['as' => 'masters.country.create', 'uses' => 'MasterController@country_create'])->middleware('auth');
Route::post('/masters/country/save', 'MasterController@country_save')->middleware('auth'); 

//Location
Route::get('/masters/location/index', ['as' => 'masters.location.index', 'uses' => 'MasterController@location_index'])->middleware('auth');
Route::get('/masters/location/create', ['as' => 'masters.location.create', 'uses' => 'MasterController@location_create'])->middleware('auth');
Route::post('/masters/location/getlist', 'MasterController@location_list')->middleware('auth');
Route::get('/masters/location/create/{key}', ['as' => 'masters.location.create', 'uses' => 'MasterController@location_create'])->middleware('auth');
Route::post('/masters/location/save', 'MasterController@location_save')->middleware('auth');

//Job Type
Route::get('/masters/job/index', ['as' => 'masters.job.index', 'uses' => 'MasterController@job_type_index'])->middleware('auth');
Route::get('/masters/job/create', ['as' => 'masters.job.create', 'uses' => 'MasterController@job_type_create'])->middleware('auth');
Route::post('/masters/job/getlist', 'MasterController@job_type_list')->middleware('auth');
Route::get('/masters/job/create/{key}', ['as' => 'masters.job.create', 'uses' => 'MasterController@job_type_create'])->middleware('auth');
Route::post('/masters/job/save', 'MasterController@job_type_save')->middleware('auth');

//Tax Code
Route::get('/masters/tax/index', ['as' => 'masters.tax.index', 'uses' => 'MasterController@tax_code_index'])->middleware('auth');
Route::get('/masters/tax/create', ['as' => 'masters.tax.create', 'uses' => 'MasterController@tax_code_create'])->middleware('auth');
Route::post('/masters/tax/getlist', 'MasterController@tax_code_list')->middleware('auth');
Route::get('/masters/tax/create/{key}', ['as' => 'masters.tax.create', 'uses' => 'MasterController@tax_code_create'])->middleware('auth');
Route::post('/masters/tax/save', 'MasterController@tax_code_save')->middleware('auth');
Route::post('/masters/tax/fetch', 'MasterController@fetch_tax')->middleware('auth');

//Masters End
