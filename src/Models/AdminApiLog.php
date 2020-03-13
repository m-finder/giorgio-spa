<?php

namespace GiorgioSpa\Models;

use Illuminate\Database\Eloquent\Model;

class AdminApiLog extends Model
{
    protected $fillable = [
        'request_url', 'request_content', 'user'
    ];

    public function scopeUser($query)
    {
        return request('user') ? $query->where('user', request('user')) : $query;
    }
}
