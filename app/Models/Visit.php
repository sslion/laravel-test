<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'ip_address',
        'city',
        'country',
        'device_type',
        'browser',
        'user_agent',
        'page_url',
        'visited_at'
    ];
    
    protected $casts = [
        'visited_at' => 'datetime'
    ];
}