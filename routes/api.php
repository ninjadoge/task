<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TravelPaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Models\Payment;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Nezaštićene rute
Route::post('/register',[UserController::class, 'register']);


// Zaštićene rute
Route::group(['middleware' => ['auth:sanctum']], function () {
    
    // Payments
    Route::get('/payments',[PaymentController::class, 'index']);
    Route::get('/payments/{id}',[PaymentController::class, 'show']);
    Route::post('/payments',[PaymentController::class, 'save']);
    Route::put('/payments/{id}',[PaymentController::class, 'update']);
    Route::delete('/payments/{id}',[PaymentController::class, 'destroy']);

    // Travel payments
    Route::get('/travel-payments',[TravelPaymentController::class, 'index']);
    Route::get('/travel-payments/{id}',[TravelPaymentController::class, 'show']);
    Route::post('/travel-payments',[TravelPaymentController::class, 'save']);
    Route::put('/travel-payments/{id}',[TravelPaymentController::class, 'update']);
    Route::delete('/travel-payments/{id}',[TravelPaymentController::class, 'destroy']);

    // Approve payments
    Route::post('/approve-payment/{id}',[ReportController::class, 'approve']);
    
    // Report
    Route::get('/report',[ReportController::class, 'report']);

    Route::post('/logout',[UserController::class, 'destroyToken']);

});
