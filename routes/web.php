<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\
{
    AttendanceController
};

Route::get('/', function () {
    return view('pages/user-home');
})->name('home');

Route::get('/user-manual-timein', function () {
    return view('pages/user-manual-timein');
})->name('user.manual.timein');

Route::post('/register-attendance', [AttendanceController::class, 'registerAttendance'])->name('register.attendance');

Route::get('/user-success-timein', function () {
    return view('pages/user-success-timein');
})->name('user.success.timein');