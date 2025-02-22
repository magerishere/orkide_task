<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\V1\Api\UserController;

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

Route::middleware([])->prefix('users')->as('users.')->controller(UserController::class)->group(function () {
    Route::get('/most/transactions', 'usersWhichMostTransactions');
});
