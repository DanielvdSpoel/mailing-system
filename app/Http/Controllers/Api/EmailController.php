<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmailCollection;
use App\Http\Resources\EmailResource;
use App\Models\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function index(Request $request)
    {
        $email = Email::query();

        if ($request->inbox_id) {
            $email->where('inbox_id', $request->inbox_id);
        }

        $email->orderBy('received_at', 'desc');

        $per_page = $request->get('per_page', 25);
        $email = $email->paginate($per_page);

        return EmailResource::collection($email);


    }
}
