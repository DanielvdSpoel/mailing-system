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
//        $content = imap_qprint(imap_fetchbody($connection, 155, "2"));
//
//        echo $content;
//        die();
//        $structure = imap_fetchstructure($connection, 155); //11, 26
//        dd($structure);
//        $email = new Email();
//        EmailSupport::handlePart($structure, null, $connection, 46, $email);
//        dd($email);

        //collect all emails and loop over them
        $emailData = imap_search($connection, '');

        foreach ($emailData as $imapEmail) {
            //todo check if we already did this email

            if (Email::where('inbox_id', $inbox->id)->where('message_id', $imapEmail)->exists()) {
                continue;
            }

            //Create email object
            $email = new Email();
            $email->message_id = $imapEmail;

            //collect necessary parts of the email
            $structure = imap_fetchstructure($connection, $imapEmail);
            $header = imap_rfc822_parse_headers(imap_fetchheader($connection, $imapEmail));

            //collect the body
            EmailSupport::handlePart($structure, null, $connection, $imapEmail, $email);

            //Collect all other things
            $email->subject = $header->subject;

            $email->received_at = Carbon::parse($header->date)->setTimezone(config('app.timezone'))->toDateTimeString();
            $email->inbox_id = $inbox->id;

            $sender = $header->from[0];
            $senderEmailAddress = EmailAddress::firstOrCreate(
                ['email' => $sender->mailbox . '@' . $sender->host],
                [
                    'label' => $sender->personal ?? $sender->mailbox . '@' . $sender->host,
                    'mailbox' => $sender->mailbox,
                    'domain' => $sender->host
                ]
            );
            $email->sender_address_id = $senderEmailAddress->id;

            $reply_to = $header->reply_to[0];
            $replyToEmailAddress = EmailAddress::firstOrCreate(
                ['email' => $reply_to->mailbox . '@' . $reply_to->host],
                [
                    'label' => $reply_to->personal ?? $reply_to->mailbox . '@' . $reply_to->host,
                    'mailbox' => $reply_to->mailbox,
                    'domain' => $reply_to->host
                ]
            );
            $email->reply_to_address_id = $replyToEmailAddress->id;
            dump($email->message_id);
            try {
                $email->save();
            } catch (\Exception $e) {
                Log::critical("We could not save the email with id " . $email->message_id . " from inbox " . $email->inbox_id);
                Log::critical("Email was send by " . $email->senderAddress->email);
                Log::critical("Email subject was " . $email->subject);
            }

        }
    } else {
        dd(imap_errors());
    }

});
