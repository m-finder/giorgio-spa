<?php

namespace GiorgioSpa\Models;

use GiorgioSpa\Models\Filters\AdminFilter;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, SoftDeletes, HasFactory, Notifiable, HasRoles, HasApiTokens, AdminFilter;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected string $guard_name = 'custom';

    const STATUS_ENABLED = 'enabled';
    const STATUS_DISABLED = 'disabled';




    protected function serializeDate(\DateTimeInterface $date): string
    {
        return Carbon::instance($date)->toDateTimeString();
    }

    public function isDisabled(): bool
    {
        return $this->getAttribute('status') == self::STATUS_DISABLED;
    }

    /**
     * @param string $name
     * @param array|string[] $abilities
     * @param null $merchantId
     * @return NewAccessToken
     */
    public function createToken(string $name, array $abilities = ['*'], $merchantId = null): NewAccessToken
    {
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => $abilities,
            'merchant_id' => $merchantId,
            'last_used_at' => now()
        ]);

        return new NewAccessToken($token, $token->getKey() . '|' . $plainTextToken);
    }

    public function updateToken($array)
    {
        $this->currentAccessToken()->update($array);
    }

    public function getPhone()
    {
        return $this->getAttribute('phone');
    }

    public function isSuper(): bool
    {
        $roleIds = Role::query()->where('is_super', '=', 1)
            ->get(['id'])->pluck('id')->toArray();
        return $this->hasAnyRole($roleIds);
    }

    public function getRoleIds(): \Illuminate\Support\Collection
    {
        return $this->roles->pluck('id');
    }

    public static function create($data): \Illuminate\Database\Eloquent\Model|Admin|\Illuminate\Database\Eloquent\Builder
    {
        $data['password'] = $data['password'] ?? bcrypt('abc123');
        return self::query()->firstOrCreate([
            'phone' => $data['phone']
        ],$data);
    }
}
