<?php

namespace GiorgioSpa\Models\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait Filter
{
    public Builder $builder;

    /**
     * scope
     */
    public function scopeFilter($query, array $validated): Builder
    {
        $this->builder = $query;

        return $this->apply($validated);
    }

    protected function apply(array $validated): Builder
    {
        // 保留 0 和 false
        $validated = array_filter($validated, function ($val) {
            return ! ($val === '' || is_null($val));
        });
        foreach ($validated as $name => $value) {
            $data = [$value];
            $method = Str::camel($name).'Filter';

            if ($this->callable($data, $name)) {
                if (method_exists($this, $method)) {
                    call_user_func_array([$this, $method], $data);
                }
            }
        }

        return $this->builder;
    }

    /**
     * 是否可调用
     */
    protected function callable($data, $name): bool
    {
        return ! is_null($data) || in_array($name, ['id', 'created_at', 'updated_at', 'start_date', 'end_date']);
    }

    /**
     * 名称查找
     */
    protected function nameFilter($name): Builder
    {
        if (in_array('name', $this->fillable)) {
            return $this->builder->where('name', 'like', '%'.$name.'%');
        }

        return $this->builder;
    }

    /**
     * id 查找
     */
    protected function idFilter($id): Builder
    {
        return $this->builder->where('id', '=', $id);
    }

    /**
     * 创建日期
     */
    protected function startDateFilter($date): Builder
    {
        $start = $date.' 00:00:00';

        return $this->builder->where('created_at', '>=', $start);
    }

    /**
     * 创建日期
     */
    protected function endDateFilter($date): Builder
    {
        $end = $date.' 23:59:59';

        return $this->builder->where('created_at', '<=', $end);
    }
}
