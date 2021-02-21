<?php

Route::name('front.')->group(function () {
    Route::get('/member-portal', 'RegisterController@index')->name('member_portal');

    Route::post('/register', 'RegisterController@register');

    Route::post('/change-password', 'RegisterController@changePassword');

    Route::post('/login', 'RegisterController@login');

    Route::post('/update-member-details', 'RegisterController@updateMemberDetails')->middleware('auth:member');

    Route::post('/print-attendances', 'RegisterController@printAttendances')->middleware('auth:member');

    Route::get('/download-attendances', 'RegisterController@downloadAttendances')->middleware('auth:member');

    Route::post('/forgot-password', 'RegisterController@forgotPassword');

    Route::post('/logout', 'RegisterController@logout');
});
