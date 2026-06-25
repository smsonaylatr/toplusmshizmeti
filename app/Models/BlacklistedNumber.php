<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlacklistedNumber extends Model
{
    protected $fillable = [
        'user_id',
        'phone_number',
        'reason',
        'source', // 'manual' or 'auto_reject'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
