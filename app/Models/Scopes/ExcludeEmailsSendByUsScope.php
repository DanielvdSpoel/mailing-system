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
    }

    public function extend(Builder $builder)
    {
        $builder->macro('withEmailSendByUs', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }
}
