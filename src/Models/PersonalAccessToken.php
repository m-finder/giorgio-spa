<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasFactory;

    protected $fillable = [
        'name',
        'token',
        'abilities',
    ];

    public static function handleOldToken()
    {
        admin()->tokens()
            ->where('name','!=','invalid_token')
            ->where('last_used_at','<',Carbon::now()->subMinutes(config('sanctum.expire_minute')))
            ->delete();
        self::query()->where('tokenable_id','=',admin()->getKey())->update(['name' => 'invalid_token']);
    }
}
