<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BatchUpdateRequest;
use App\Http\Resources\EmailCollection;
use App\Http\Resources\EmailResource;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

    public function batchUpdate(BatchUpdateRequest $request)
    {
        $data = $request->validated();
        foreach (['is_archived', 'is_deleted', 'is_read'] as $var) {
            if (isset($data[$var]) && $data[$var]) {
                unset($data[$var]);
                $data[explode('_', $var)[1] . '_at'] = Carbon::now();
            } else if (isset($data[$var])){
                unset($data[$var]);
                $data[explode('_', $var)[1] . '_at'] = null;
            }
        }
        $ids = $data['ids'];
        unset($data['ids']);

        Email::whereIn('id', $ids)->withoutGlobalScopes()->update($data);

        return response()->json([
            'message' => 'Emails have been updated',
        ]);
    }
}
