<?php

namespace GiorgioSpa\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class Admin extends Authenticatable
{
    protected $guard_name = 'admin-api';

    protected $fillable = [
        'role_id', 'name', 'email', 'password', 'avatar', 'api_token',
    ];

    protected $hidden = [
        'password'
    ];

    /**
     * 昵称检索
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeName($query, $name)
    {
        return is_null($name) ? $query : $query->where('name', $name);
    }

    /**
     * 邮箱检索
     * @param $query
     * @param $email
     * @return mixed
     */
    public function scopeEmail($query, $email)
    {
        return is_null($email) ? $query : $query->where('email', $email);
    }

    /**
     * 对应角色
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    /**
     * 权限检测
     * @param $path
     * @return mixed
     */
    public function hasAuth($path)
    {
        $role_id = Auth::user()->role->id;
        $element = Element::where('path', $path)->first();
        $has = $element ? RoleElement::where('element_id', $element->id)->where('role_id', $role_id)->count() : 0;
        return $has;
    }

}
