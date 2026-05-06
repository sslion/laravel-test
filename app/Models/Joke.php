<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Joke extends Model
{
    protected $fillable = [
        'joke_id',
        'type',
        'setup',
        'punchline',
        'fetched_at'
    ];
    
    protected $casts = [
        'fetched_at' => 'datetime'
    ];
}