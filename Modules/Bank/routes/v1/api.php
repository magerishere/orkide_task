<?php

use Illuminate\Support\Facades\Route;
use Modules\Bank\Http\Controllers\V1\Api\BankController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware([])->prefix('banks')->as('banks.')->controller(BankController::class)->group(function () {
    Route::prefix('{user}')->group(function() {
        Route::post('/card_to_card','cardToCard');
    });
});
