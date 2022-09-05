<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerifyEmailController;


Route::post("register", [AuthController::class, ("register")]);
Route::post("login", [AuthController::class, ("login")]);

// Verify email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');



Route::group(["prefix" => "v1", "middleware" => "auth:sanctum"], function(){
    // Resend link to verify email
    Route::post('/email/verify/resend', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response()->json([
            "message" => "Email verification sent!"
        ]);
    })->name('verification.send');


    Route::get("me", [AuthController::class, ("getme")]);
    Route::post("refresh", [AuthController::class, ("refresh")]);
    Route::post("logout", [AuthController::class, ("logout")]);
});
