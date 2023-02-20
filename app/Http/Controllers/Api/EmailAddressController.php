<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmailAddressResource;
use App\Models\EmailAddress;
use Illuminate\Http\Request;

class EmailAddressController extends Controller
{
    public function index(Request $request)
    {
        $emailAddresses = EmailAddress::query();

        if ($request->get('search')) {
            $emailAddresses->where('email', 'like', '%'.$request->get('search').'%')
                ->orWhere('name', 'like', '%'.$request->get('search').'%');
        }

        if ($request->get('limit')) {
            $emailAddresses->limit($request->get('limit'));
        }

        $emailAddresses->orderBy('name');

        return EmailAddressResource::collection($emailAddresses->get());
    }

    public function show(EmailAddress $emailAddress)
    {
        return new EmailAddressResource($emailAddress);
    }
}
