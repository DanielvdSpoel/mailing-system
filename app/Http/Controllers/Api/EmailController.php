<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BatchUpdateRequest;
use App\Http\Requests\Api\UpdateEmailRequest;
use App\Http\Resources\EmailResource;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EmailController extends Controller
{
    public function index(Request $request)
    {
        $email = Email::query()
            ->with(['inbox', 'labels', 'senderAddress', 'conversation', 'inbox.senderAddresses']);

        if ($request->inbox_id) {
            $email->where('inbox_id', $request->inbox_id);
        }

        if (!$request->get('included_archived', false)) {
            $email->whereNull('archived_at');
        }

        if (!$request->get('included_deleted', false)) {
            $email->whereNull('deleted_at');
        }

        if (!$request->get('included_drafts', false)) {
            $email->where('is_draft', false);
        }

        if (!$request->get('included_emails_send_by_us', false)) {
            $email->where('email_send_by_us', false);
        }

        $email->orderBy('received_at', 'desc');

        $per_page = $request->get('per_page', 25);
        $email = $email->paginate($per_page);

        return EmailResource::collection($email);

    }

    public function show(Email $email)
    {
        return new EmailResource($email);
    }

    public function update(UpdateEmailRequest $request, Email $email)
    {
        $this->updateEmail($email, $request->validated());

        return response()->noContent();
    }

    public function batchUpdate(BatchUpdateRequest $request)
    {
        $data = $request->validated();

        $ids = $data['ids'];
        unset($data['ids']);

        Email::whereIn('id', $ids)->each(function ($email) use ($data) {
            $this->updateEmail($email, $data);
        });

        return response()->noContent();
    }

    private function updateEmail(Email $email, $data): void
    {
        foreach (['is_archived', 'is_deleted', 'is_read'] as $key) {
            if (!isset($data[$key])) {
                continue;
            }
            $data[explode('_', $key)[1] . '_at'] = $data[$key] ?? false ? Carbon::now() : null;
            unset($data[$key]);
        }

        if (isset($data['labels'])) {
            $email->labels()->sync($data['labels']);
            unset($data['labels']);
        }

        $email->update($data);

    }

}
