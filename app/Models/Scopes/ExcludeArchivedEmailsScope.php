<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class ExcludeArchivedEmailsScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNull('archived_at');
    }

    public function extend(Builder $builder)
    {
        $builder->macro('withArchived', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }
}
