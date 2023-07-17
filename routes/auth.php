<?php

use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\PasswordResets;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Auth\TwoFactors;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register auth routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "guest", "web" and "auth.basic"  middleware group. Now create something great!
|
*/

//Initial landing page uses the Guest Middleware
Route::middleware('guest')->group(function (){
    //GET
    Route::get('register',[Register::class,'landingPage'])
        ->name('register');
    Route::get('login',[Login::class,'landingPage'])
        ->name('login');
    Route::get('forgotten-password',[PasswordResets::class,'landingPage'])
        ->name('recoverPassword');

    //POST
    Route::post('register/process',[Register::class,'processRegistration'])
        ->name('auth.register');
    Route::post('login/process',[Login::class,'processLogin'])
        ->name('auth.login');
    Route::post('auth/otp',[TwoFactors::class,'processTwoFactor'])
        ->name('auth.otp');
    Route::post('forgotten-password/process',[PasswordResets::class,'processPasswordReset'])
        ->name('auth.recover');
    Route::post('reset-password/process',[PasswordResets::class,'processPasswordResetRequest'])
        ->name('auth.resetPassword');

});

//Signed urls specifically for email verification & Two-Factor Authentications
Route::middleware('signed')->group(function (){
    Route::get('/verify-email/{user}',[Register::class,'emailVerification'])
        ->name('verify.email.link');

    Route::get('/verify-password-reset/{user}/{token}',[PasswordResets::class,'resetPassword'])
        ->name('verify.password.reset');

    Route::get('/lock-account/{user}',[Login::class,'lockUserAccount'])
        ->name('lock.account');
});
