<?php

namespace App\Services\Guard;

use App\Models\User;
use App\Models\UserRiskScore;
use App\Models\SmsMessage;
use App\Models\WhatsappMessage;
use App\Models\GuardLog;
use App\Models\BlacklistedNumber;

class RiskAnalyzer
{
    /**
     * Kullanıcı risk skorunu yeniden hesapla
     */
    public function calculateRisk(User $user): UserRiskScore
    {
        $spamScore = $this->calculateSpamScore($user);
        $complianceScore = $this->calculateComplianceScore($user);
        $behaviorScore = $this->calculateBehaviorScore($user);

        $riskScore = (int) round(($spamScore * 0.4) + ($complianceScore * 0.3) + ($behaviorScore * 0.3));
        $riskScore = min(100, max(0, $riskScore));

        return UserRiskScore::updateOrCreate(
            ['user_id' => $user->id],
            [
                'risk_score' => $riskScore,
                'spam_score' => $spamScore,
                'compliance_score' => $complianceScore,
                'behavior_score' => $behaviorScore,
            ]
        );
    }

    /**
     * Spam skoru: Gönderim hızı + ret oranı
     */
    private function calculateSpamScore(User $user): int
    {
        $score = 0;

        // Son 24 saatteki mesaj sayısı
        $todayMessages = SmsMessage::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDay())
            ->count();
        $todayWhatsapp = WhatsappMessage::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDay())
            ->count();

        $totalToday = $todayMessages + $todayWhatsapp;

        if ($totalToday > 500) $score += 40;
        elseif ($totalToday > 200) $score += 20;
        elseif ($totalToday > 100) $score += 10;

        // Ret oranı
        $totalSms = SmsMessage::where('user_id', $user->id)->count();
        if ($totalSms > 10) {
            $failedSms = SmsMessage::where('user_id', $user->id)->where('status', 'failed')->count();
            $failRate = $failedSms / $totalSms;
            if ($failRate > 0.5) $score += 30;
            elseif ($failRate > 0.3) $score += 15;
        }

        // Kara listeye eklenen numara sayısı
        $blacklisted = BlacklistedNumber::where('user_id', $user->id)->count();
        if ($blacklisted > 50) $score += 20;
        elseif ($blacklisted > 20) $score += 10;

        return min(100, $score);
    }

    /**
     * Uyumluluk skoru: Evrak + gönderici adı durumu
     */
    private function calculateComplianceScore(User $user): int
    {
        $score = 0;

        // Hesap yaşı
        $accountAge = $user->created_at->diffInDays(now());
        if ($accountAge < 1) $score += 30;
        elseif ($accountAge < 7) $score += 15;

        // Guard log flag sayısı
        $flags = GuardLog::where('user_id', $user->id)
            ->where('is_resolved', false)
            ->count();

        if ($flags > 10) $score += 40;
        elseif ($flags > 5) $score += 20;
        elseif ($flags > 0) $score += 10;

        return min(100, $score);
    }

    /**
     * Davranış skoru: Gönderim zamanlaması + kalıp
     */
    private function calculateBehaviorScore(User $user): int
    {
        $score = 0;

        // Gece gönderimi (00:00 - 06:00)
        $nightMessages = SmsMessage::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subWeek())
            ->whereRaw("HOUR(created_at) BETWEEN 0 AND 5")
            ->count();

        if ($nightMessages > 50) $score += 30;
        elseif ($nightMessages > 10) $score += 15;

        // Son 1 saatte yoğun gönderim
        $lastHour = SmsMessage::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($lastHour > 100) $score += 40;
        elseif ($lastHour > 50) $score += 20;

        return min(100, $score);
    }
}
