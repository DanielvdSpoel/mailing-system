<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\InboxController;
use App\Http\Controllers\Api\LabelController;
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
    Route::prefix('/inboxes')->group(function () {
        Route::get('/', [InboxController::class, 'index']);
//        Route::get('/paginated', [InboxController::class, 'store']);
//        Route::get('/{id}', [InboxController::class, 'show']);
    });
    Route::prefix('/labels')->group(function () {
        Route::get('/', [LabelController::class, 'index']);
//        Route::get('/paginated', [InboxController::class, 'store']);
//        Route::get('/{id}', [InboxController::class, 'show']);
    });
    Route::prefix('/emails')->group(function () {
        Route::get('/', [EmailController::class, 'index']);
    });
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

