<?php
Route::get('/portal', 'PortalController@index');
Route::post('/portal', ['as' => 'portal', 'uses' => 'PortalController@open']);
Route::get('/portal/home', ['as' => 'portal.home', 'uses' => 'PortalController@home'])->middleware('portal');
Route::post('/portal/logout', ['as' => 'portal.logout', 'uses' => 'PortalController@logout'])->middleware('portal');
Route::post('/portal/list', 'PortalController@contract_list')->middleware('portal');
Route::get('/portal/tickets', ['as' => 'portal.tickets', 'uses' => 'PortalController@tickets'])->middleware('portal');
Route::post('/portal/tickets/list', 'PortalController@ticket_list')->middleware('portal');
Route::get('portal/create/ticket/{key}', ['as' => 'portal.create.ticket', 'uses' => 'PortalController@create_ticket'])->middleware('portal');
Route::post('/portal/ticket/save', 'PortalController@save_ticket')->middleware('portal');

