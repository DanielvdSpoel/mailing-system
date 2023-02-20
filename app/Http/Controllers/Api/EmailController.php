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
            ->with(['inbox', 'labels', 'senderAddress', 'conversation', 'inbox.senderAddresses'])
            ->withTrashed($request->get('include_deleted', false) == 'true')
            ->withDraft($request->get('include_drafts', false) == 'true')
            ->withSnoozed($request->get('include_snoozed', false) == 'true')
            ->withArchived($request->get('include_archived', false) == 'true')
            ->withEmailsSendByUs($request->get('include_emails_send_by_us', false))
            ->orderBy('received_at', 'desc')
            ->when($request->get('limit'), function ($query, $limit) {
                return $query->limit($limit);
            });

        if ($request->inbox_id) {
            $email->where('inbox_id', $request->inbox_id);
        }

        if ($request->label_id) {
            $email->whereHas('labels', function ($query) use ($request) {
                $query->where('label_id', $request->label_id);
            });
        }

        if ($request->sender_id) {
            $email->where('sender_address_id', $request->sender_id);
        }

        if ($request->search) {
            $email->where(function ($query) use ($request) {
                $query->where('subject', 'like', '%'.$request->search.'%')
                    ->orWhere('text_body', 'like', '%'.$request->search.'%')
                    ->orWhere('html_body', 'like', '%'.$request->search.'%')
                    ->orWhereHas('senderAddress', function ($query) use ($request) {
                        $query->where('email', 'like', '%'.$request->search.'%');
                    });
            });
        }

        if ($request->get('no_pagination')) {
            $email = $email->get();
        } else {
            $email = $email->paginate($request->get('per_page', 25));
        }

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
            if (! isset($data[$key])) {
                continue;
            }
            $data[explode('_', $key)[1].'_at'] = $data[$key] ?? false ? Carbon::now() : null;
            unset($data[$key]);
        }

        if (isset($data['labels'])) {
            $email->labels()->sync($data['labels']);
            unset($data['labels']);
        }

        $email->withoutGlobalScopes()->update($data);
    }
}
