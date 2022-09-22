<?php

namespace App\Models\Filters;

use Illuminate\Database\Eloquent\Builder;

trait PermissionFilter
{
    use Filter;

    public function typeFilter($type): Builder
    {
        return $this->builder->where('type', '=', $type);
    }
}
