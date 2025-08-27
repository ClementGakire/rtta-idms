<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $table = 'user_sessions';

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
