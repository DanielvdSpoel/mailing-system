<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function body(Email $email)
    {
        return view('emails.body', ['email' => $email]);

    }
}
