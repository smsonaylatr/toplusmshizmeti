<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'reason', 'details', 'severity',
        'is_resolved', 'resolved_by', 'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
            'is_resolved' => 'boolean',
            'resolved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
