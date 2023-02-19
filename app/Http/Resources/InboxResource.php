<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InboxResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array|\JsonSerializable|\Illuminate\Contracts\Support\Arrayable
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'last_successful_connection_at' => $this->last_successful_connection_at,
            'senderAddresses' => $this->senderAddresses->pluck('email'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
