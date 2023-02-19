<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'text_body' => $this->text_body,
            'html_body' => $this->html_body,
            'sender' => $this->senderAddress,
            'inbox' => new InboxResource($this->inbox),
            'labels' => LabelResource::collection($this->labels),
            'received_at' => $this->received_at,
            'conversation' => $this->conversation,
            'is_read' => (bool)$this->read_at,
            'is_draft' => (bool)$this->is_draft,
            'is_archived' => (bool)$this->archived_at,
            'is_deleted' => (bool)$this->deleted_at,

        ];
    }
}
