<?php

namespace GiorgioSpa\Models;

use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
    protected $fillable = [
        'menu_id', 'name', 'code', 'method', 'path',
    ];

    public function scopeMenuId($query)
    {
        return request('menu_id') ? $query->where('menu_id', request('menu_id')) : $query;
    }

    public static function isUnique()
    {
        $element = self::where(function ($query) {
            $query->where('name', request('name'))->orWhere('code', request('code'))->orWhere('path', request('path'));
        })->where('id', '!=', request('id'))->first();
        return is_null($element) ? true : false;
    }
}
