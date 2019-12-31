<?php

Route::get('/finance/receipt', ['as' => 'finance.receipt.index', 'uses' => 'FinanceController@receipt_index'])->middleware('auth');
Route::post('/finance/receipt/list', 'FinanceController@list')->middleware('auth');
Route::get('/finance/receipt/create', ['as' => 'finance.receipt.create', 'uses' => 'FinanceController@receipt_create'])->middleware('auth');
Route::get('/finance/receipt/create/{key}', ['as' => 'finance.receipt.create', 'uses' => 'FinanceController@receipt_create'])->middleware('auth');
Route::post('/finance/receipt/save', 'FinanceController@receipt_save')->middleware('auth');
Route::get('/finance/cheques', ['as' => 'finance.cheque', 'uses' => 'FinanceController@cheque_management'])->middleware('auth');
Route::post('/finance/cheques/list', 'FinanceController@cheque_list')->middleware('auth');
Route::post('/finance/cheques/save', 'FinanceController@update_cheques')->middleware('auth');
Route::post('/finance/cheques/revert', 'FinanceController@revert_cheques')->middleware('auth');

Route::get('/finance/payment', ['as' => 'finance.payment.index', 'uses' => 'FinanceController@payment_index'])->middleware('auth');
Route::post('/finance/payment/list', 'FinanceController@list')->middleware('auth');
Route::get('/finance/payment/create', ['as' => 'finance.payment.create', 'uses' => 'FinanceController@payment_create'])->middleware('auth');
Route::get('/finance/payment/create/{key}', ['as' => 'finance.payment.create', 'uses' => 'FinanceController@payment_create'])->middleware('auth');
Route::post('/finance/payment/save', 'FinanceController@payment_save')->middleware('auth');

Route::get('/finance/journal', ['as' => 'finance.journal.index', 'uses' => 'FinanceController@journal_index'])->middleware('auth');
Route::post('/finance/journal/list', 'FinanceController@list')->middleware('auth');
Route::get('/finance/journal/create', ['as' => 'finance.journal.create', 'uses' => 'FinanceController@journal_create'])->middleware('auth');
Route::get('/finance/journal/create/{key}', ['as' => 'finance.journal.create', 'uses' => 'FinanceController@journal_create'])->middleware('auth');
Route::post('/finance/journal/save', 'FinanceController@journal_save')->middleware('auth');

Route::post('/finance/status', 'FinanceController@update_status')->middleware('auth');
Route::get('/finance/export/{key}', ['as' => 'finance.export', 'uses' => 'FinanceController@export'])->middleware('auth');
Route::get('/finance/export/invoice/{key}', ['as' => 'finance.export', 'uses' => 'FinanceController@export_invoice'])->middleware('auth');

