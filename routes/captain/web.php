<?php

Route::name('captain.')->prefix('captain')->namespace('Captain')->group(function () {
    Route::namespace('Auth')->middleware('guest:captain')->group(function () {
        // Login
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login');

        // Google two factor verification
        Route::get('/google-two-factor-code', 'LoginController@googleTwoFactorCode')->name('google_two_factor_code');
        Route::post('/google-two-factor-code', 'LoginController@googleTwoFactorCodeVerify');
    });

    // Logged in admin user required
    Route::middleware('auth:captain')->group(function () {
        // Dashboard
        Route::get('/dashboard', 'UserController@dashboard')->name('dashboard');

        // Attendance
        Route::resource('calendar-events', 'CalendarEventController');
        Route::get('calendar_events_datatables', 'CalendarEventController@datatables')->name('calendar_events.datatables');
        Route::get('calendar-events-finalise/{calendar_event}', 'CalendarEventController@finalise')->name('calendar_events.finalise');
        Route::get('calendar-events-un-finalise/{calendar_event}', 'CalendarEventController@unFinalise')->name('calendar_events.unfinalise');
        Route::resource(
            'calendar-event.scores',
            'CalendarEventScoreController',
            ['only' => ['index', 'store', 'destroy']]
        );
        Route::post('calendar-events-filter', 'CalendarEventController@filter')->name('calendar_events.filter');

        // Profile
        Route::get('/profile', 'UserController@profile')->name('profile');
        Route::post('/profile', 'UserController@profileUpdate');
        Route::post('/password', 'UserController@passwordUpdate')->name('password_update');

        // Google two factor
        Route::get('/google-two-factor', 'UserController@googleTwoFactor')->name('google_two_factor');
        Route::post('/google-two-factor', 'UserController@googleTwoFactorVerify')->name('verify_google_two_factor');
        Route::post('/disabled-google-two-factor', 'UserController@disabledGoogleTwoFactor')->name('disabled_google_two_factor');

        // Logout
        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    });
});
