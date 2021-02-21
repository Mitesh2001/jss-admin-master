<?php

use Illuminate\Support\Facades\Route;

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

// Front
Route::view('/', 'welcome');

Route::get('/registration-renewal/{individual}/{renewalRun}', 'IndividualRenewalController@index')
    ->name('front.individual_renewal')
    ->middleware('signed')
;

Route::post('/registration-renewal/{individual}/{renewalRun}', 'IndividualRenewalController@submit');

Route::get('/registration-renewal-thank-you/{individual}/{renewalRun}/{isFamily}/{isFamilyRenewalAlreadyPaid}/{transactionId?}', 'RenewalCompletionController@individualThankYou')
    ->name('front.individual_renewal_thank_you')
;

Route::get('/renewal-requested-already', 'RenewalCompletionController@requestedAlready');

// Admin
Route::name('admin.')->prefix('admin')->namespace('Admin')->group(function () {
    Route::namespace('Auth')->middleware('guest:web')->group(function () {
        // Login
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login');

        // Google two factor verification
        Route::get('/google-two-factor-code', 'LoginController@googleTwoFactorCode')->name('google_two_factor_code');
        Route::post('/google-two-factor-code', 'LoginController@googleTwoFactorCodeVerify');
    });

    // Logged in admin user required
    Route::middleware('auth:web')->group(function () {
        // Dashboard
        Route::get('/dashboard', 'UserController@dashboard')->name('dashboard');

        // Individuals
        Route::resource('individuals', 'IndividualController');
        Route::post('update-id-card', 'IndividualController@updateIdCard')->name('individuals.update_id_card');
        Route::get('individuals_datatables', 'IndividualController@datatables')->name('individuals.datatables');
        Route::get('individuals/filters/{filter?}', 'IndividualController@filter')->name('individuals.filter');

        // Events
        Route::resource(
            'individuals.events',
            'IndividualEventController',
            ['only' => ['store', 'update', 'destroy']]
        );

        Route::group([], base_path('routes/admin/renewals.php'));

        // ID Cards
        Route::get('id-cards', 'IdCardController@index')->name('id_cards.index');
        Route::get('id-cards/filter/{filter?}', 'IdCardController@filter')->name('id_cards.filter');
        Route::get('datatables', 'IdCardController@datatables')->name('id_cards.datatables');
        Route::post('remove-from-print-card', 'IdCardController@removeFromPrintCard')->name('id_cards.remove');
        Route::get('export-id-cards-to-csv', 'IdCardController@exportToCsv')->name('id_cards.export_to_csv');

        Route::get('printed-id-cards', 'IdCardController@printed')->name('id_cards.printed');
        Route::get('printed-datatables', 'IdCardController@printedDatatables')->name('id_cards.printed_datatables');

        Route::post('send-to-print-run', 'IdCardController@sendToPrintRun')->name('id_cards.send_to_print_run');
        Route::post('remove-from-print-run', 'IdCardController@removeFromPrintRun')->name('id_cards.remove_from_print_run');
        Route::post('clear-print-run', 'IdCardController@clearPrintRun')->name('id_cards.clear_print_run');
        Route::post('mark-as-printed', 'IdCardController@markAsPrinted')->name('id_cards.mark_as_printed');

        // Disciplines
        Route::resource(
            'individuals.disciplines',
            'IndividualDisciplineController',
            ['only' => ['store', 'update', 'destroy']]
        );

        // Range Officers
        Route::resource(
            'individuals.range_officers',
            'IndividualRangeOfficerController',
            ['only' => ['store', 'update', 'destroy']]
        );

        // Families
        Route::resource('families', 'FamilyController');
        Route::get('families_datatables', 'FamilyController@datatables')->name('families.datatables');
        Route::get('families/filters/{filter?}', 'FamilyController@filter')->name('families.filter');

        // Receipts
        Route::resource('receipts', 'ReceiptController');
        Route::get('receipts/{receipt}/print', 'ReceiptController@print')->name('receipts.print');
        Route::get('receipts_datatables', 'ReceiptController@datatables')->name('receipts.datatables');

        Route::resource(
            'receipts.payments',
            'ReceiptPaymentController',
            ['only' => ['store', 'update', 'destroy']]
        );

        Route::resource(
            'receipts.items',
            'ReceiptItemController',
            ['only' => ['store', 'update', 'destroy']]
        );

        // Disciplines
        Route::resource('disciplines', 'DisciplineController');
        Route::get('disciplines_datatables', 'DisciplineController@datatables')->name('disciplines.datatables');

        // Attendance
        Route::resource('calendar-events', 'CalendarEventController');
        Route::get('calendar_events_datatables', 'CalendarEventController@datatables')->name('calendar_events.datatables');
        Route::get('calendar-events-finalise/{calendar_event}', 'CalendarEventController@finalise')->name('calendar_events.finalise');
        Route::get('calendar-events-un-finalise/{calendar_event}', 'CalendarEventController@unFinalise')->name('calendar_events.unfinalise');
        Route::post('calendar-events-filter', 'CalendarEventController@filter')->name('calendar_events.filter');

        Route::resource(
            'calendar-event.scores',
            'CalendarEventScoreController',
            ['only' => ['index', 'store', 'destroy']]
        );

        // Reports
        Route::group([], base_path('routes/admin/reports.php'));

        // Disciplines
        Route::resource('users', 'UserController');
        Route::get('users_datatables', 'UserController@datatables')->name('users.datatables');

        // Firearms
        Route::get('firearms', 'FirearmController@index')->name('firearms.index');
        Route::get('firearms-datatables', 'FirearmController@datatables')->name('firearms.datatables');
        Route::post('firearms', 'FirearmController@store')->name('firearms.store');
        Route::put('firearms/{firearm}', 'FirearmController@update')->name('firearms.update');
        Route::post('firearms/remove-support', 'FirearmController@removeSupport')->name('firearms.remove_support');
        Route::post('firearms/mark-as-disposed', 'FirearmController@markAsDisposed')->name('firearms.mark_as_disposed');
        Route::post('firearms/filters', 'FirearmController@setFilters')->name('firearms.filter');

        // Keys
        Route::get('keys', 'KeyController@index')->name('keys.index');
        Route::get('keys-datatables', 'KeyController@datatables')->name('keys.datatables');
        Route::post('keys', 'KeyController@store')->name('keys.store');
        Route::put('keys/{key}', 'KeyController@update')->name('keys.update');
        Route::delete('keys/{key}', 'KeyController@destroy')->name('keys.destroy');
        Route::post('keys/{key}/mark-as-lost', 'KeyController@markAsLost')->name('keys.mark_as_lost');
        Route::post('keys/{key}/mark-as-returned', 'KeyController@markAsReturned')->name('keys.mark_as_returned');
        Route::post('keys/filters', 'KeyController@setFilters')->name('keys.filter');

        // Range Officers
        Route::get('range-officers', 'RangeOfficerController@index')->name('range_officers.index');
        Route::get('range-officers-datatables', 'RangeOfficerController@datatables')->name('range_officers.datatables');
        Route::post('range-officers', 'RangeOfficerController@store')->name('range_officers.store');
        Route::put('range-officers/{range_officer}', 'RangeOfficerController@update')->name('range_officers.update');
        Route::delete('range-officers/{range_officer}', 'RangeOfficerController@destroy')->name('range_officers.destroy');
        Route::post('range-officers/filters', 'RangeOfficerController@setFilters')->name('range_officers.filter');

        // Profile
        Route::get('/profile', 'UserController@profile')->name('profile');
        Route::post('/profile', 'UserController@profileUpdate');
        Route::post('/password', 'UserController@passwordUpdate')->name('password_update');

        Route::get('/google-two-factor', 'UserController@googleTwoFactor')->name('google_two_factor');
        Route::post('/google-two-factor', 'UserController@googleTwoFactorVerify')->name('verify_google_two_factor');
        Route::post('/disabled-google-two-factor', 'UserController@disabledGoogleTwoFactor')->name('disabled_google_two_factor');

        // Logout
        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    });
});

Route::group([], base_path('routes/captain/web.php'));
Route::group([], base_path('routes/front/web.php'));
