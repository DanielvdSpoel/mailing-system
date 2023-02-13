<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\InboxController;
use App\Http\Controllers\Api\LabelController;
use App\Http\Controllers\Api\RefreshController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/auth/login', [AuthenticationController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('inboxes', InboxController::class)->only(['index', 'show']);
    Route::apiResource('labels', LabelController::class)->only(['index', 'show']);
    Route::apiResource('emails', EmailController::class)->only(['index']);

    Route::post('/refresh', RefreshController::class)->name('refresh');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

