<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class ExcludeDraftEmailsScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('is_draft', false);
    }

    public function extend(Builder $builder)
    {
        $builder->macro('withDraft', function (Builder $builder, $withDraft = true) {
            if (!$withDraft) {
                return $builder->withoutDraft();
            }
            return $builder->withoutGlobalScope($this);
        });

        $builder->macro('withoutDraft', function (Builder $builder) {
            return $builder->withoutGlobalScope($this)->where('is_draft', false);
        });

        $builder->macro('onlyDraft', function (Builder $builder, $onlyDraft = true) {
            if (!$onlyDraft) {
                return $builder->withoutDraft();
            }
            return $builder->withoutGlobalScope($this)->where('is_draft', true);
        });
    }
}
