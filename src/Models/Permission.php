<?php

namespace GiorgioSpa\Models;

use GiorgioSpa\Models\Filters\PermissionFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory, PermissionFilter;

    protected $fillable = [
        'name',
        'name_zh_cn',
        'method',
        'uri',
        'guard_name',
        'type',
    ];

    protected $hidden = [
        'guard_name',
	    'pivot'
    ];

    protected string $guard_name = 'sanctum';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
