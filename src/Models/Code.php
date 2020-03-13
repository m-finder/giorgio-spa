<?php

namespace GiorgioSpa\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = [
        'email', 'code', 'is_used'
    ];
}
