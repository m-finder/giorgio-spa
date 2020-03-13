<?php

namespace GiorgioSpa\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    protected $fillable = [
        'id', 'parent_id', 'name', 'title', 'path', 'icon', 'component', 'redirect', 'order_num', 'hidden', 'affix'
    ];

    protected $appends = ['meta'];

    public function elements()
    {
        return $this->hasMany(Element::class, 'menu_id', 'id');
    }

    /**
     * 追加数据，用于前端路由
     * @return array
     */
    public function getMetaAttribute()
    {
        return $this->attributes['meta'] = [
            'title' => $this->title,
            'affix' => $this->affix,
        ];
    }
}
