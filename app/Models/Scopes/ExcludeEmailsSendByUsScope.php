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
        $builder->macro('withEmailsSendByUs', function (Builder $builder, $withEmailsSendByUs = true) {
            if (!$withEmailsSendByUs) {
                return $builder->withoutEmailsSendByUs();
            }
            return $builder->withoutGlobalScope($this);
        });

        $builder->macro('withoutEmailsSendByUs', function (Builder $builder) {
            return $builder->withoutGlobalScope($this)->where('email_send_by_us', false);
        });

        $builder->macro('onlyEmailsSendByUs', function (Builder $builder) {
            return $builder->withoutGlobalScope($this)->whereNotNull('email_send_by_us', true);
        });
    }
}
