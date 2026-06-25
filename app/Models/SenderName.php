<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SenderName extends Model
{
    protected $fillable = ['user_id', 'name', 'status', 'is_default'];

    protected $casts = ['is_default' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kullanıcı için bu başlığı varsayılan yap, diğerlerini sıfırla.
     */
    public function setAsDefault(): void
    {
        static::where('user_id', $this->user_id)->update(['is_default' => false]);
        $this->update(['is_default' => true]);
    }

    /**
     * Kullanıcının varsayılan gönderici adını döner.
     */
    public static function defaultForUser(int $userId): ?string
    {
        return static::where('user_id', $userId)
            ->where('status', 'approved')
            ->where('is_default', true)
            ->value('name');
    }
}
