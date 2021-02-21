<?php
// Renewal Runs
Route::resource('renewal-runs', 'RenewalRunController');
Route::get('renewal-runs-toggle-status/{renewalRun}', 'RenewalRunController@toggleStatus')->name('renewal_runs.status');

Route::get('renewals-run-details/{renewalRun}', 'RenewalRunController@details')->name('renewal_runs.details');

Route::get('renewals-run-details/{renewalRun}/submitted-filters/{submittedFilter?}', 'RenewalRunController@submittedFilter')->name('renewal_runs.submitted_filter');

Route::post('send_renewals_emails', 'SendRenewalEmailsController@index')->name('send_renewals_emails');

Route::get('add-active-to-renewal-run/{renewalRunId?}', 'AddToRenewalRunController@active')->name('add_active_to_renewal_run');
Route::get('add-to-renewal-run/{individualId?}', 'AddToRenewalRunController@single')->name('add_to_renewal_run');
Route::get('remove-from-renewal-run/{renewalRunId?}/{individualId?}', 'AddToRenewalRunController@remove')->name('remove_from_renewal_run');


Route::get('renewal-submissions', 'IndividualRenewalController@index')->name('individual_renewal_submissions');
Route::get('renewal-submissions-datatables', 'IndividualRenewalController@getIndividualRenewals')->name('individual_renewal_submissions_datatables');
Route::get('renewal-submissions/filters/{filter?}', 'IndividualRenewalController@filter')->name('individual_renewals_filter');
Route::get('individual_renewals/payment-type-filters/{paymentTypeFilter?}', 'IndividualRenewalController@paymentTypeFilter')->name('individual_renewals_payment_type_filter');

// Process renewals
Route::post('individual-renewal-process-renewal/{individualRenewal?}', 'ProcessRenewalController@processRenewal')
    ->name('individual_renewals.process_renewal')
;
