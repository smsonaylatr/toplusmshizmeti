<?php

namespace App\Services\Guard;

use App\Models\MessageFilter as MessageFilterModel;

class MessageFilter
{
    /**
     * BDK uyumlu yasaklı kelimeler (varsayılan)
     */
    private array $bdkKeywords = [
        'kredi kartı', 'tc kimlik', 'şifre', 'parola', 'banka hesap',
        'para transfer', 'kazandınız', 'ödül', 'tebrikler', 'hemen tıkla',
        'bedava', 'ücretsiz kredi', 'yatırım fırsatı', 'garantili kazanç',
        'hızlı para', 'zengin ol', 'borsa sinyali', 'bitcoin kazan',
        'kumar', 'bahis', 'casino', 'slot', 'canlı bahis',
        'erotik', 'cinsel', 'yetişkin', 'sex', 'porn',
        'uyuşturucu', 'silah', 'sahte', 'korsan',
    ];

    /**
     * Spam kalıpları
     */
    private array $spamPatterns = [
        '/(\d{4}[\s-]?){3}\d{4}/',     // Kredi kartı numarası
        '/\d{11}/',                      // TC Kimlik
        '/bit\.ly|tinyurl|goo\.gl/i',   // Kısaltılmış URL
        '/(.)\1{5,}/',                   // Tekrarlı karakterler (aaaaaa)
        '/[A-Z]{10,}/',                  // Aşırı büyük harf
    ];

    /**
     * Mesajı analiz et
     * @return array{status: string, score: int, flags: array, details: array}
     */
    public function analyze(string $message): array
    {
        $flags = [];
        $score = 0;

        // 1. Veritabanındaki filtrelerle kontrol
        $dbFilters = MessageFilterModel::where('is_active', true)->get();
        foreach ($dbFilters as $filter) {
            $matched = false;
            if ($filter->is_regex) {
                $matched = (bool) preg_match($filter->pattern, $message);
            } else {
                $matched = str_contains(mb_strtolower($message), mb_strtolower($filter->pattern));
            }

            if ($matched) {
                $severityScore = match ($filter->severity) {
                    'high' => 40,
                    'medium' => 20,
                    'low' => 10,
                };
                $score += $severityScore;
                $flags[] = [
                    'type' => $filter->category,
                    'pattern' => $filter->pattern,
                    'severity' => $filter->severity,
                    'message' => "Filtre eşleşmesi: {$filter->pattern}",
                ];
            }
        }

        // 2. BDK kelime kontrolü
        $lowerMessage = mb_strtolower($message);
        foreach ($this->bdkKeywords as $keyword) {
            if (str_contains($lowerMessage, $keyword)) {
                $score += 15;
                $flags[] = [
                    'type' => 'bdk',
                    'pattern' => $keyword,
                    'severity' => 'medium',
                    'message' => "BDK yasaklı kelime: {$keyword}",
                ];
            }
        }

        // 3. Spam pattern kontrolü
        foreach ($this->spamPatterns as $pattern) {
            if (preg_match($pattern, $message)) {
                $score += 25;
                $flags[] = [
                    'type' => 'spam',
                    'pattern' => $pattern,
                    'severity' => 'high',
                    'message' => "Spam pattern tespit edildi",
                ];
            }
        }

        // 4. Mesaj uzunluk kontrolü
        if (mb_strlen($message) < 10) {
            $score += 5;
            $flags[] = [
                'type' => 'spam',
                'pattern' => 'short_message',
                'severity' => 'low',
                'message' => 'Çok kısa mesaj',
            ];
        }

        // Skor sınırla
        $score = min(100, $score);

        // Sonuç belirle
        $status = match (true) {
            $score >= 70 => 'block',
            $score >= 30 => 'warn',
            default => 'pass',
        };

        return [
            'status' => $status,
            'score' => $score,
            'flags' => $flags,
            'details' => [
                'total_checks' => count($this->bdkKeywords) + count($this->spamPatterns) + $dbFilters->count(),
                'matched_flags' => count($flags),
            ],
        ];
    }
}
