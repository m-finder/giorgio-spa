<?php

namespace App\Models;

use App\Models\Filters\RoleFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory, RoleFilter, SoftDeletes;

    protected $fillable = [
        'name',
        'brief',
        'status',
        'guard_name',
        'is_super',
    ];

    protected $hidden = [
        'guard_name',
	    'pivot'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'is_super' => 'bool',
    ];

    public function getName()
    {
        return $this->getAttribute('name');
    }

}
