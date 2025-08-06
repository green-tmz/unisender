<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LaravelUnisender\Http\Controllers\UnisenderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Unisender API Routes
|--------------------------------------------------------------------------
|
| Routes for Unisender API integration
|
*/

Route::prefix('unisender')->group(function () {
    // SMS operations
    Route::post('/sms', [UnisenderController::class, 'sendSms']);
    
    // Email operations
    Route::post('/email', [UnisenderController::class, 'sendEmail']);
    
    // Contact list operations
    Route::get('/lists', [UnisenderController::class, 'getLists']);
    Route::post('/lists', [UnisenderController::class, 'createList']);
    
    // Contact operations
    Route::post('/subscribe', [UnisenderController::class, 'subscribe']);
    Route::get('/contact', [UnisenderController::class, 'getContact']);
    
    // Campaign operations
    Route::get('/campaigns', [UnisenderController::class, 'getCampaigns']);
    
    // Field operations
    Route::get('/fields', [UnisenderController::class, 'getFields']);
}); 