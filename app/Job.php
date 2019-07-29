<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $casts = [
        'company_id' => 'integer',
        'start' => 'datetime',
        'end' => 'datetime'
    ];

    public static $allowedIncludes = ['achievements', 'company'];

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
