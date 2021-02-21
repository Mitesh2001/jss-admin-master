<?php
// Payments
Route::get('payments-report', 'ReportController@paymentReport')->name('reports.payments');

Route::get('payments-datatables', 'ReportController@datatables')->name('reports.payments.datatables');
Route::post('payments-filter', 'ReportController@filter')->name('reports.payments.filter');
Route::get('payments-report/{type}/{startDate}/{endDate}', 'ReportController@print')->name('reports.payments.print');

// Members
Route::get('members-report', 'MembersReportController@index')->name('reports.members');

Route::get('members-datatables', 'MembersReportController@datatables')->name('reports.members.datatables');
Route::post('members-filter', 'MembersReportController@filter')->name('reports.members.filter');
Route::get('members-report/{discipline}/{expiration_status}/{membership_status}', 'MembersReportController@print')->name('reports.members.print');
Route::get('members-report-csv/{discipline}/{expiration_status}/{membership_status}', 'MembersReportController@exportCsv')->name('reports.members.csv');

// WWC Cards
Route::get('wwc-cards-report', 'WwcCardsReportController@index')->name('reports.wwc_cards');

Route::get('wwc-cards-datatables', 'WwcCardsReportController@datatables')->name('reports.wwc_cards.datatables');
Route::get('wwc-cards-report-print', 'WwcCardsReportController@print')->name('reports.wwc_cards.print');
