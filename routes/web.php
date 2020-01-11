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
 * Properties Route
 */
//Building
Route::get('/building/index', ['as' => 'building.index', 'uses' => 'BuildingController@index'])->middleware('auth');
Route::get('/building/create', ['as' => 'building.create', 'uses' => 'BuildingController@create'])->middleware('auth');
Route::get('/building/create/{key}', ['as' => 'building.create', 'uses' => 'BuildingController@create'])->middleware('auth');
Route::post('/building/save/basic', 'BuildingController@save_basics')->middleware('auth');
Route::post('/building/save/depreciation', 'BuildingController@save_depreciation')->middleware('auth');
Route::post('/building/status', 'BuildingController@update_status')->middleware('auth');

//Flats
Route::get('/building/flat', ['as' => 'building.flat.create', 'uses' => 'BuildingController@flat_create'])->middleware('auth');
Route::post('/building/flat/list', 'BuildingController@flat_list')->middleware('auth');
Route::post('/building/flat/save', 'BuildingController@flat_save')->middleware('auth');
Route::post('/building/flat/status', 'BuildingController@flat_status')->middleware('auth');
Route::get('/building/flat/all/{key}', ['as' => 'building.flat.all', 'uses' => 'BuildingController@flat_all'])->middleware('auth');
Route::get('/building/flat/all/{status}/{key}', ['as' => 'building.flat.all', 'uses' => 'BuildingController@flat_active'])->middleware('auth');
Route::post('/flat/fetch', 'BuildingController@fetch_flat')->middleware('auth');
//properties routes end

//Ledgers & Groups 
Route::get('/ledger/groups', ['as' => 'ledger.group.index', 'uses' => 'LedgerController@group_index'])->middleware('auth');
Route::post('/ledger/groups/list', 'LedgerController@list')->middleware('auth');
Route::get('/ledger/groups/create', ['as' => 'ledger.group.create', 'uses' => 'LedgerController@group_create'])->middleware('auth');
Route::get('/ledger/groups/create/{key}', ['as' => 'ledger.group.create', 'uses' => 'LedgerController@group_create'])->middleware('auth');
Route::post('/ledger/groups/save', 'LedgerController@group_save')->middleware('auth');

//Ledger
Route::get('/ledger', ['as' => 'ledger.index', 'uses' => 'LedgerController@index'])->middleware('auth');
Route::post('/ledger/list', 'LedgerController@list')->middleware('auth');
Route::get('/ledger/create', ['as' => 'ledger.create', 'uses' => 'LedgerController@create'])->middleware('auth');
Route::get('/ledger/create/{key}', ['as' => 'ledger.create', 'uses' => 'LedgerController@create'])->middleware('auth');
Route::post('/ledger/save', 'LedgerController@save')->middleware('auth');
//end

//Tenants
Route::get('/tenant/index', ['as' => 'tenant.index', 'uses' => 'TenantController@index'])->middleware('auth');
Route::post('/tenant/list', 'TenantController@list')->middleware('auth');
Route::get('/tenant/create', ['as' => 'tenant.create', 'uses' => 'TenantController@create'])->middleware('auth');
Route::get('/tenant/create/{key}', ['as' => 'tenant.create', 'uses' => 'TenantController@create'])->middleware('auth');
Route::post('/tenant/save', 'TenantController@save')->middleware('auth');
Route::post('/tenant/status', 'TenantController@status')->middleware('auth');
Route::post('/tenant/query', 'TenantController@query')->middleware('auth');
Route::post('/tenant/fetch', 'TenantController@fetch')->middleware('auth');
Route::get('/tenant/pdf', 'TenantController@pdf')->middleware('auth');
Route::get('/tenant/excel', 'TenantController@excel')->middleware('auth');

//Document Handler
Route::post('/document/get_documents', 'DocumentController@get_documents')->middleware('auth');
Route::get('/document/create', ['as' => 'document.create', 'uses' => 'DocumentController@create'])->middleware('auth');
Route::post('/document/update_document', 'DocumentController@update_document')->middleware('auth');
Route::post('/document/upload', 'DocumentController@upload')->middleware('auth');
Route::post('/document/remove', 'DocumentController@destroy')->middleware('auth');
Route::post('/document/remove_with_ref', 'DocumentController@destroy_with_id')->middleware('auth');
Route::get('/document/download', ['as' => 'document.download', 'uses' => 'DocumentController@download'])->middleware('auth');
Route::get('/document/all/{from}/{parent}', ['as' => 'document.all', 'uses' => 'DocumentController@document_all'])->middleware('auth');

/**
 * dependent data
 */
Route::get('/building/flats/{building_id}', ['as' => 'building.locations', 'uses' => 'BuildingController@flats'])->middleware('auth');
Route::get('/masters/locations/{country_id}', ['as' => 'masters.locations', 'uses' => 'MasterController@locations'])->middleware('auth');
//End


//Helpers
Route::post('/theme', 'UserController@update_theme')->middleware('auth');
Route::get('/expiring_contracts', 'HomeController@expiring_contracts')->middleware('auth');
Route::get('/uncleared_payments', 'HomeController@uncleared_payments')->middleware('auth');
Route::get('/uncleared_receipts', 'HomeController@uncleared_receipts')->middleware('auth');


//FM
Route::get('/fm/tickets', ['as' => 'fm.tickets', 'uses' => 'FmController@tickets'])->middleware('auth');
Route::post('/fm/tickets/list', 'FmController@ticket_list')->middleware('auth');
Route::get('/fm/ticket/create', ['as' => 'fm.tickets.create', 'uses' => 'FmController@ticket_create'])->middleware('auth');
Route::get('/fm/ticket/create/{key}', ['as' => 'fm.tickets.create', 'uses' => 'FmController@ticket_create'])->middleware('auth');
Route::post('/fm/tickets/save', 'FmController@ticket_save')->middleware('auth');
Route::get('/fm/job/create', ['as' => 'fm.tickets.create', 'uses' => 'FmController@job_create'])->middleware('auth');
Route::get('/fm/job/create/{key}', ['as' => 'fm.tickets.create', 'uses' => 'FmController@job_create'])->middleware('auth');
Route::post('/fm/jobs/save', 'FmController@jobs_save')->middleware('auth');
Route::post('/fm/jobs/{action}/{ticket_id}', 'FmController@update_job')->middleware('auth');
//FM End

/**
 * User Master Routes
 */
Route::get('/users/index', ['as' => 'users.index', 'uses' => 'UserController@index'])->middleware('auth');
Route::post('/users/getlist', 'UserController@userlist')->middleware('auth');
Route::get('/users/create', ['as' => 'users.create', 'uses' => 'UserController@create'])->middleware('auth');
Route::get('/users/create/{key}', ['as' => 'users.edit', 'uses' => 'UserController@create'])->middleware('auth');
Route::post('/users/update', ['as' => 'users.update', 'uses' => 'UserController@update'])->middleware('auth');
Route::post('/users/store', ['as' => 'users.store', 'uses' => 'UserController@store'])->middleware('auth');
Route::post('/users/changepword', ['as' => 'users.changepword', 'uses' => 'UserController@changepword'])->middleware('auth');
