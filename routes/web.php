<?php

use App\Models\Email;
use App\Models\EmailAddress;
use App\Supports\EmailSupport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Webklex\PHPIMAP\Folder;

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
Route::get('/inbox/{inbox}', function (App\Models\Inbox $inbox) {
    $connection = $inbox->getClientConnection();
    if ($connection) {

        //collect all emails and loop over them
        $emailData = imap_search($connection, '');

        foreach ($emailData as $imapEmail) {
            if (Email::where('inbox_id', $inbox->id)->where('message_id', $imapEmail)->exists()) {
                continue;
            }
        }
    } else {
        dd(imap_errors());
    }

});
