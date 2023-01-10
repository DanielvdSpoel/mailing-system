<?php

use App\Http\Controllers\EmailController;
use App\Models\Email;
use App\Models\EmailAddress;
use App\Supports\EmailRuleSupport\EmailRuleHandler;
use App\Supports\EmailRuleSupport\Enumns\RuleOperation;
use App\Supports\EmailSupport;
use Carbon\Carbon;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use Webklex\PHPIMAP\Folder;
use function Filament\Support\get_model_label;

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
    dd(EmailRuleHandler::getActionBlocks());

});
Route::get('/inbox/{inbox}', function (App\Models\Inbox $inbox) {
    $connection = $inbox->getClientConnection();
    if ($connection) {

        //collect all emails and loop over them
        $emailData = imap_search($connection, '');

        foreach ($emailData as $imapEmail) {
            if (Email::where('inbox_id', $inbox->id)->where('message_id', $imapEmail)->exists()) {
                continue;
            }
            Email::createFromImap($connection, $imapEmail, $inbox);
        }
    } else {
        dd(imap_errors());
    }

});
