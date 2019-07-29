<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $casts = [
        'job_id' => 'integer'
    ];

    protected $fillable = ['blurb'];

    public static $allowedIncludes = ['job'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
