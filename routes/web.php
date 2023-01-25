<?php

use App\Http\Controllers\EmailController;
use App\Jobs\FilterEmail;
use App\Jobs\ProcessIncomingEmail;
use App\Jobs\SaveEmailAttachments;
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

Route::middleware(Authenticate::class)->group(function () {
    Route::get('/emails/{email}/body', [EmailController::class, 'body'])->name('emails.body');
});

Route::get('/test', function () {
//    $email = Email::find(1);
//    $connection = $email->inbox->getClientConnection($email->inbox->getConnectionString());
//    dump(imap_fetch_overview($connection, $email->message_uid, FT_UID));
////    SaveEmailAttachments::dispatchSync($email);
//    dd("Finished");

});
Route::get('/inbox/{inbox}', function (App\Models\Inbox $inbox) {

    ProcessIncomingEmail::dispatchSync($inbox);
});
