<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VirtualPosOrder extends Model
{
    protected $fillable = [
        'user_id',
        'package_type',
        'package_name', 'sms_amount',
        'price', 'vat_amount', 'total_amount',
        'status', 'merchant_oid', 'paytr_payment_amount',
        'card_last_four', 'paid_at', 'failure_message',
    ];

    protected function casts(): array
    {
        return [
            'price'               => 'decimal:2',
            'vat_amount'          => 'decimal:2',
            'total_amount'        => 'decimal:2',
            'paid_at'             => 'datetime',
            'sms_amount'          => 'integer',
            'paytr_payment_amount'=> 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Sadece ödenmiş siparişler */
    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }

    /** Bekleyen siparişler */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /** Başarısız siparişler */
    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', 'failed');
    }

    /** SMS miktarını formatla (5.000 gibi) */
    public function getSmsAmountFormattedAttribute(): string
    {
        return number_format($this->sms_amount, 0, ',', '.');
    }

    /** Durum için Türkçe etiket */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'paid'      => 'Ödendi',
            'pending'   => 'Bekliyor',
            'failed'    => 'Başarısız',
            'cancelled' => 'İptal',
            default     => $this->status,
        };
    }

    /** Durum için renk sınıfı (Tailwind/CSS) */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'paid'      => 'green',
            'pending'   => 'yellow',
            'failed'    => 'red',
            'cancelled' => 'gray',
            default     => 'gray',
        };
    }
}
