<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::check()) {
            $user = Auth::user();

            // If user belongs to a school (School Admin), restrict to their school_id
            if ($user->school_id && !$user->hasRole('Super Admin')) {
                $builder->where($model->getTable() . '.school_id', $user->school_id);
            }
        }
    }
}
