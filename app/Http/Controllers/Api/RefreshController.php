<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RefreshRequest;
use App\Jobs\ProcessIncomingEmail;
use App\Models\Inbox;
use Illuminate\Http\Request;

class RefreshController extends Controller
{
    public function __invoke(RefreshRequest $request)
    {
        if ($request->has('inbox_id')) {
            ProcessIncomingEmail::dispatchSync(Inbox::find($request->inbox_id));

        } else {
            foreach (Inbox::all() as $inbox) {
                ProcessIncomingEmail::dispatchSync($inbox);
            }
        }

        return response()->noContent();
    }
}
