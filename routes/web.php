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

//Contracts 
Route::get('/contract/index', ['as' => 'contract.index', 'uses' => 'ContractController@index'])->middleware('auth');
Route::post('/contract/list', 'ContractController@list')->middleware('auth');
Route::get('/contract/create', ['as' => 'contract.create', 'uses' => 'ContractController@create'])->middleware('auth');
Route::get('/contract/create/{key}', ['as' => 'contract.create', 'uses' => 'ContractController@create'])->middleware('auth');
Route::post('/contract/save', 'ContractController@save')->middleware('auth');
Route::get('/contract/export/{key}', ['as' => 'contract.export', 'uses' => 'ContractController@export'])->middleware('auth');
//end

//Finance
Route::get('/finance/receipt', ['as' => 'finance.receipt.index', 'uses' => 'FinanceController@receipt_index'])->middleware('auth');
Route::post('/finance/receipt/list', 'FinanceController@list')->middleware('auth');
Route::get('/finance/receipt/create', ['as' => 'finance.receipt.create', 'uses' => 'FinanceController@receipt_create'])->middleware('auth');
Route::get('/finance/receipt/create/{key}', ['as' => 'finance.receipt.create', 'uses' => 'FinanceController@receipt_create'])->middleware('auth');
Route::post('/finance/receipt/save', 'FinanceController@receipt_save')->middleware('auth');
//end

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


/**
 * dependent data
 */
Route::get('/building/flats/{building_id}', ['as' => 'building.locations', 'uses' => 'BuildingController@flats'])->middleware('auth');
Route::get('/masters/locations/{country_id}', ['as' => 'masters.locations', 'uses' => 'MasterController@locations'])->middleware('auth');
//End


//Helpers
Route::post('/theme', 'UserController@update_theme')->middleware('auth');

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
