<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRiskScore extends Model
{
    protected $fillable = [
        'user_id', 'risk_score', 'spam_score', 'compliance_score',
        'behavior_score', 'total_flags', 'last_flag_at',
    ];

    protected function casts(): array
    {
        return [
            'last_flag_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRiskLevelAttribute(): string
    {
        return match (true) {
            $this->risk_score >= 80 => 'critical',
            $this->risk_score >= 60 => 'high',
            $this->risk_score >= 30 => 'medium',
            default => 'low',
        };
    }
}
