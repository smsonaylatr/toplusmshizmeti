<?php

namespace App\Services;

use App\Models\WhatsappMessage;
use App\Models\WhatsappSession;
use Carbon\Carbon;

class SpamGuard
{
    // Günlük gönderim limitleri
    const DAILY_LIMIT_DEFAULT = 500;
    const HOURLY_LIMIT_DEFAULT = 100;

    // Numara ısınma limitleri (gün => max mesaj)
    const WARMUP_SCHEDULE = [
        1 => 50,
        2 => 100,
        3 => 200,
        4 => 300,
        5 => 400,
        6 => 500,     // 6. günden sonra tam limit
    ];

    // Gönderim hızı seçenekleri (saniye aralıkları)
    const SPEED_OPTIONS = [
        'hizli' => ['label' => 'Hızlı (~30 sn)', 'min' => 25, 'max' => 35],
        'orta'  => ['label' => 'Orta (1-2 dk)',  'min' => 60, 'max' => 120],
        'yavas' => ['label' => 'Yavaş (3-5 dk)', 'min' => 180, 'max' => 300],
    ];

    // Spam kelimeleri (Türkçe)
    const SPAM_KEYWORDS = [
        'kazandınız', 'ücretsiz', 'bedava', 'tıklayın', 'hemen ara',
        'kaçırmayın', 'son şans', 'acele edin', 'sınırlı teklif',
        'kredi kartı', 'şifreniz', 'hesabınız askıya', 'doğrulama kodu',
        'para kazanın', 'yatırım fırsatı', 'bitcoin', 'kripto',
        'çekiliş', 'ödül', 'kampanya bitti', 'hemen tıkla',
    ];

    /**
     * Kredi yeterliliğini kontrol et
     */
    public static function checkCredits($user, int $count): ?string
    {
        $credits = $user->whatsapp_credits ?? 0;
        if ($credits < $count) {
            return "Yetersiz kredi. Gerekli: {$count}, Mevcut: {$credits}";
        }
        return null;
    }

    /**
     * Session'ın kullanıcıya ait olduğunu doğrula
     */
    public static function validateSession(string $sessionId, int $userId): ?string
    {
        if (empty($sessionId)) {
            return 'Lütfen gönderen numara seçin.';
        }

        $session = WhatsappSession::where('id', $sessionId)
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->first();

        if (!$session) {
            return 'Geçersiz veya aktif olmayan gönderen numara.';
        }

        return null;
    }

    /**
     * Günlük gönderim limitini kontrol et
     */
    public static function checkDailyLimit(int $userId, ?string $sessionId = null): ?string
    {
        $todayCount = WhatsappMessage::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        if ($todayCount >= self::DAILY_LIMIT_DEFAULT) {
            return "Günlük gönderim limitine ulaştınız ({$todayCount}/" . self::DAILY_LIMIT_DEFAULT . "). Yarın tekrar deneyin.";
        }

        // Saatlik limit
        $hourCount = WhatsappMessage::where('user_id', $userId)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->count();

        if ($hourCount >= self::HOURLY_LIMIT_DEFAULT) {
            return "Saatlik gönderim limitine ulaştınız ({$hourCount}/" . self::HOURLY_LIMIT_DEFAULT . "). 1 saat sonra tekrar deneyin.";
        }

        return null;
    }

    /**
     * Yeni numara ısınma kontrolü
     */
    public static function checkWarmup(string $sessionId, int $userId): ?string
    {
        $session = WhatsappSession::find($sessionId);
        if (!$session) return null;

        $daysSinceConnect = $session->connected_at
            ? Carbon::parse($session->connected_at)->diffInDays(Carbon::now()) + 1
            : 1;

        // 6 günden sonra ısınma tamamlanır
        if ($daysSinceConnect > 6) return null;

        $maxForDay = self::WARMUP_SCHEDULE[$daysSinceConnect] ?? self::DAILY_LIMIT_DEFAULT;

        $todaySessionCount = WhatsappMessage::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        if ($todaySessionCount >= $maxForDay) {
            return "Bu numara henüz yeni bağlandı. Isınma limitine ulaştınız ({$todaySessionCount}/{$maxForDay}). Gün: {$daysSinceConnect}/6";
        }

        return null;
    }

    /**
     * Spam içerik taraması — uyarı döner, bloklama değil
     */
    public static function checkSpamContent(string $message): array
    {
        $warnings = [];
        $lowerMessage = mb_strtolower($message);

        foreach (self::SPAM_KEYWORDS as $keyword) {
            if (str_contains($lowerMessage, mb_strtolower($keyword))) {
                $warnings[] = $keyword;
            }
        }

        return $warnings;
    }

    /**
     * Telefon numarası format doğrulama
     */
    public static function validatePhone(string $phone): bool
    {
        // Türkiye numarası: 5XX ile başlar, 10 veya 11 haneli
        $cleaned = preg_replace('/[\s\-\(\)\+]/', '', $phone);
        return (bool) preg_match('/^(0?5\d{9}|905\d{9})$/', $cleaned);
    }

    /**
     * Mesajlar arası gecikme hesapla (saniye)
     */
    public static function getDelay(string $speed = 'orta'): int
    {
        $opt = self::SPEED_OPTIONS[$speed] ?? self::SPEED_OPTIONS['orta'];
        return rand($opt['min'], $opt['max']);
    }

    /**
     * Hız seçeneğinin açıklamasını döner
     */
    public static function getSpeedLabel(string $speed = 'orta'): string
    {
        return self::SPEED_OPTIONS[$speed]['label'] ?? self::SPEED_OPTIONS['orta']['label'];
    }

    /**
     * Günlük kullanım istatistikleri
     */
    public static function getDailyStats(int $userId): array
    {
        $today = WhatsappMessage::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        return [
            'today' => $today,
            'daily_limit' => self::DAILY_LIMIT_DEFAULT,
            'hourly_limit' => self::HOURLY_LIMIT_DEFAULT,
            'remaining' => max(0, self::DAILY_LIMIT_DEFAULT - $today),
            'percentage' => min(100, round(($today / self::DAILY_LIMIT_DEFAULT) * 100)),
        ];
    }
}
