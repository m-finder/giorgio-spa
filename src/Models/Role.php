<?php

namespace GiorgioSpa\Models;

use GiorgioSpa\Models\Filters\RoleFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\PermissionRegistrar;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory, RoleFilter, SoftDeletes;

    protected $table = 'roles';

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

    protected string $guard_name = 'sanctum';

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

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            PermissionRegistrar::$pivotRole,
            PermissionRegistrar::$pivotPermission
        );
    }
}
