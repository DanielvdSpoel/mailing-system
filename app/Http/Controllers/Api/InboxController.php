<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InboxResource;
use App\Models\Inbox;

class InboxController extends Controller
{
    public function index()
    {
        return InboxResource::collection(Inbox::all());
    }

    public function show(InboxResource $inbox)
    {
        return new InboxResource($inbox);
    }
}
