<?php
//Contracts 
Route::get('/contract/index', ['as' => 'contract.index', 'uses' => 'ContractController@index'])->middleware('auth');
Route::post('/contract/list', 'ContractController@list')->middleware('auth');
Route::get('/contract/create', ['as' => 'contract.create', 'uses' => 'ContractController@create'])->middleware('auth');
Route::get('/contract/create/{key}', ['as' => 'contract.create', 'uses' => 'ContractController@create'])->middleware('auth');
Route::get('/contract/renew/{key}', ['as' => 'contract.create', 'uses' => 'ContractController@renew'])->middleware('auth');
Route::get('/contract/settlement/{key}', ['as' => 'contract.create', 'uses' => 'ContractController@settlement'])->middleware('auth');
Route::post('/contract/save', 'ContractController@save')->middleware('auth');
Route::get('/contract/export/{key}', ['as' => 'contract.export', 'uses' => 'ContractController@export'])->middleware('auth');
Route::post('/contract/fetch', 'ContractController@fetch')->middleware('auth');
Route::post('/contract/details', 'ContractController@details')->middleware('auth');
Route::get('/contract/cheques/{key}', ['as' => 'contract.create', 'uses' => 'ContractController@create_cheques'])->middleware('auth');
Route::get('/contract/cheques/list/{key}', 'ContractController@cheques_list' )->middleware('auth');
Route::post('/contract/cheques/save', 'ContractController@save_cheques')->middleware('auth');
Route::post('/contract/settlement/early', 'ContractController@save_early_settlement')->middleware('auth');
Route::post('/contract/settlement/expired', 'ContractController@save_expired_settlement')->middleware('auth');
Route::get('/contract/pdf', 'ContractController@pdf')->middleware('auth');
Route::get('/contract/excel', 'ContractController@excel')->middleware('auth');
//end
