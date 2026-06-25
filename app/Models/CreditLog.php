<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditLog extends Model
{
    protected $fillable = [
        'user_id', 'type', 'action', 'amount', 'balance_after', 'description', 'reference',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kredi ekle ve logla
     */
    public static function record(int $userId, string $type, string $action, int $amount, ?int $balanceAfter = null, ?string $description = null, ?string $reference = null): self
    {
        return static::create([
            'user_id'      => $userId,
            'type'         => $type,
            'action'       => $action,
            'amount'       => $amount,
            'balance_after'=> $balanceAfter,
            'description'  => $description,
            'reference'    => $reference,
        ]);
    }
}
