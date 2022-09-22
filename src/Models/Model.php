<?php

namespace App\Models;

use Illuminate\Support\Carbon;

class Model extends \Illuminate\Database\Eloquent\Model
{

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return Carbon::instance($date)->toDateTimeString();
    }

}