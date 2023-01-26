<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class ExcludeEmailsSendByUsScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('email_send_by_us', false);

//        $builder->leftJoin('email_addresses', 'email_addresses.id', '=', 'emails.sender_address_id')
//            ->whereNotExists(function ($query) {
//                $query->select(DB::raw(1))
//                    ->from('email_address_inbox')
//                    ->whereRaw('email_address_inbox.inbox_id = 1 AND email_address_inbox.email_address_id = email_addresses.id');
//            });
        //$this->inbox->senderAddresses()->pluck('email')->contains($this->senderAddress->email);

//        $builder->where('is_email_send_by_us', false);
    }
}
