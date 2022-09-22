<?php

namespace App\Models\Filters;

use Illuminate\Database\Eloquent\Builder;

trait RoleFilter
{
    use Filter;

    protected function nameFilter($name): Builder
    {
        return $this->builder->where('name', 'like', '%' . $name . '%');
    }
}
