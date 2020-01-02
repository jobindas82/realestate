<?php

Route::get('/report/filter/building', ['as' => 'reports.building.summary', 'uses' => 'BuildingController@building_summary_filter'])->middleware('auth');
Route::post('/report/response/flats', 'BuildingController@flat_response')->middleware('auth');
Route::post('/report/response/contracts', 'BuildingController@contract_response')->middleware('auth');
Route::get('/report/drop/flat/{building_id}', 'BuildingController@flats_drop')->middleware('auth');
Route::get('/report/drop/contracts/{flat_id}', 'BuildingController@contracts_drop')->middleware('auth');