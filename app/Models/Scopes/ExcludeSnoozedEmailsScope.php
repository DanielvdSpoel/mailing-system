<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class ExcludeSnoozedEmailsScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNull('snoozed_until')->orWhere('snoozed_until', '<', DB::raw('NOW()'));
    }

    public function extend(Builder $builder)
    {
        $builder->macro('withSnoozed', function (Builder $builder, $withSnoozed = true) {
            if (!$withSnoozed) {
                return $builder->withoutSnoozed();
            }
            return $builder->withoutGlobalScope($this);
        });

        $builder->macro('withoutSnoozed', function (Builder $builder) {
            return $builder->withoutGlobalScope($this)->whereNull('snoozed_until')->orWhere('snoozed_until', '<', DB::raw('NOW()'));
        });

        $builder->macro('onlySnoozed', function (Builder $builder) {
            return $builder->withoutGlobalScope($this)->whereNotNull('snoozed_until')->orWhere('snoozed_until', '>', DB::raw('NOW()'));
        });
    }
}
