<?php

namespace App\Models\Filters;

use Illuminate\Database\Eloquent\Builder;

trait AdminFilter
{
    use Filter;

    protected function nameFilter($name): Builder
    {
        return $this->builder->where('name', 'like', '%' . $name . '%');
    }

    protected function roleIdFilter($roleId): Builder
    {
        return $this->builder->whereHas('roles', function ($q) use ($roleId) {
            $q->where('id', '=', $roleId);
        });
    }

    protected function phoneFilter($phone): Builder
    {
        return $this->builder->where('phone', '=',  $phone );
    }

    protected function emailFilter($email): Builder
    {
        return $this->builder->where('email', 'like', '%' . $email . '%');
    }
}
