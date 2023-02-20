<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class ExcludeMarkedAsSpamEmailsScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNull('marked_as_spam_at');
    }

    public function extend(Builder $builder)
    {
        $builder->macro('withSpam', function (Builder $builder, $withSpam = true) {
            if (!$withSpam) {
                return $builder->withoutSpam();
            }
            return $builder->withoutGlobalScope($this);
        });

        $builder->macro('withoutSpam', function (Builder $builder) {
            return $builder->withoutGlobalScope($this)->whereNull('marked_as_spam_at');
        });

        $builder->macro('onlySpam', function (Builder $builder, $onlySpam = true) {
            if (!$onlySpam) {
                return $builder->withoutSpam();
            }
            return $builder->withoutGlobalScope($this)->whereNotNull('marked_as_spam_at');
        });
    }
}
