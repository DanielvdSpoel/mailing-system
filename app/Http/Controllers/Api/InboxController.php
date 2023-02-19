<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InboxResource;
use App\Models\Inbox;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index(Request $request)
    {
        $inboxes = Inbox::query()
            ->with(['senderAddresses']);

        if ($request->get('search')) {
            $inboxes->where('name', 'like', '%'.$request->get('search').'%')
                ->orWhereHas('senderAddresses', function ($query) use ($request) {
                    $query->where('email', 'like', '%'.$request->get('search').'%');
                });
        }

        $inboxes->orderBy('name');

        return InboxResource::collection($inboxes->get());
    }

    public function show(Inbox $inbox)
    {
        return new InboxResource($inbox);
    }
}
