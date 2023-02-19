<?php

use App\Http\Controllers\EmailController;
use App\Models\Email;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/admin');
Route::redirect('/login', '/admin/login')->name('login');
Route::middleware(Authenticate::class)->group(function () {
    Route::get('/emails/{email}/body', [EmailController::class, 'body'])->name('emails.body');
});
