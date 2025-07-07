<?php

use App\Http\Controllers\Auth\NewPasswordResetController;
use App\Http\Controllers\Auth\AccountActivationController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;

Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('locale.login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('locale.login.store');

    Route::get('register', [RegisteredUserController::class, 'create'])->name('locale.register');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('locale.register.store');

    // Use custom password reset controller
    Route::get('forgot-password', [NewPasswordResetController::class, 'create'])->name('locale.password.request');
    Route::post('forgot-password', [NewPasswordResetController::class, 'sendResetLinkEmail'])->name('locale.password.email');

    // Disable the default password reset routes since we're sending the password directly
    // Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    // Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});



// Email verification routes are handled by Fortify automatically
// No need to define them manually here
