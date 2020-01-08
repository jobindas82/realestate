<?php

Route::get('/report/filter/building', ['as' => 'reports.building.summary', 'uses' => 'BuildingController@building_summary_filter'])->middleware('auth');
Route::post('/report/response/flats', 'BuildingController@flat_response')->middleware('auth');
Route::post('/report/response/contracts', 'BuildingController@contract_response')->middleware('auth');
Route::post('/report/response/tenants', 'BuildingController@tenant_response')->middleware('auth');
Route::post('/report/response/finance/{type}', 'BuildingController@finance_list')->middleware('auth');
Route::post('/report/response/tickets', 'BuildingController@ticket_response')->middleware('auth');
Route::post('/report/response/ledgers', 'BuildingController@ledger_response')->middleware('auth');
Route::get('/report/drop/flat/{building_id}', 'BuildingController@flats_drop')->middleware('auth');
Route::get('/report/drop/contracts/{flat_id}', 'BuildingController@contracts_drop')->middleware('auth');
Route::get('/report/export/flat', 'BuildingController@export_flat')->middleware('auth');
Route::get('/report/export/contract', 'BuildingController@export_contract')->middleware('auth');
Route::get('/report/export/tenant', 'BuildingController@export_tenant')->middleware('auth');
Route::get('/report/export/finance', 'BuildingController@export_finance')->middleware('auth');
Route::get('/report/export/ticket', 'BuildingController@export_ticket')->middleware('auth');
Route::get('/report/export/ledger', 'BuildingController@export_ledger')->middleware('auth');
Route::get('/report/export/all', 'BuildingController@export_all')->middleware('auth');


//Finance reports
Route::get('/report/finance/gl', ['as' => 'reports.finance.gl', 'uses' => 'FinanceController@general_ledger'] )->middleware('auth');
Route::post('/report/finance/gl', 'FinanceController@general_ledger_list')->middleware('auth');
Route::get('/report/export/gl', 'FinanceController@general_ledger_excel')->middleware('auth');

Route::get('/report/finance/tb', ['as' => 'reports.finance.tb', 'uses' => 'FinanceController@trial_balance'] )->middleware('auth');
Route::post('/report/finance/tb', 'FinanceController@trial_balance_list')->middleware('auth');
Route::get('/report/export/tb', 'FinanceController@trial_balance_excel')->middleware('auth');

Route::get('/report/finance/bs', ['as' => 'reports.finance.bs', 'uses' => 'FinanceController@balance_sheet'] )->middleware('auth');
Route::post('/report/finance/bs_asset', 'FinanceController@balance_sheet_asset_list')->middleware('auth');
Route::post('/report/finance/bs_liability', 'FinanceController@balance_sheet_liability_list')->middleware('auth');
Route::get('/report/export/bs', 'FinanceController@balance_sheet_excel')->middleware('auth');

Route::get('/report/finance/tax', ['as' => 'reports.finance.tax', 'uses' => 'FinanceController@tax'] )->middleware('auth');
Route::post('/report/finance/tax', 'FinanceController@tax_list')->middleware('auth');
Route::get('/report/export/tax', 'FinanceController@tax_excel')->middleware('auth');

Route::get('/report/finance/cheque', ['as' => 'reports.finance.cheque', 'uses' => 'FinanceController@cheque'] )->middleware('auth');
Route::post('/report/finance/cheque', 'FinanceController@cheque_list')->middleware('auth');
Route::get('/report/export/cheque', 'FinanceController@cheque_export')->middleware('auth');