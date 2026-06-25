<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsApprovalQueue extends Model
{
    protected $table = 'sms_approval_queue';

    protected $fillable = [
        'user_id', 'campaign_id', 'sender_name', 'message',
        'recipients', 'recipient_count', 'status',
        'reviewed_by', 'reviewed_at', 'reject_reason', 'sent_count',
    ];

    protected $casts = [
        'recipients'  => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
