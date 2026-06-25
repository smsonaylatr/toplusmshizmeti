<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'user_id', 'type', 'file_path', 'original_name', 'status', 'rejection_reason',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'contract' => 'Sözleşme',
            'identity' => 'Kimlik',
            'residence' => 'İkametgah',
            'tax_plate' => 'Vergi Levhası',
            'activity_certificate' => 'Faaliyet Belgesi',
            'signature_circular' => 'İmza Sirküsü',
            default => $this->type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Beklemede',
            'approved' => 'Onaylandı',
            'rejected' => 'Reddedildi',
            default => $this->status,
        };
    }
}
