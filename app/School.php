<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $casts = [
        'graduated' => 'datetime'
    ];

    public static $allowedIncludes = [];
}
