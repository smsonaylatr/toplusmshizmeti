<?php

namespace App\Services\Guard;

use App\Models\User;
use App\Models\GuardLog;
use App\Models\Notification;

class BehaviorMonitor
{
    private MessageFilter $messageFilter;
    private RiskAnalyzer $riskAnalyzer;

    public function __construct()
    {
        $this->messageFilter = new MessageFilter();
        $this->riskAnalyzer = new RiskAnalyzer();
    }

    /**
     * Mesaj gönderilmeden önce kontrol et
     * @return array{allowed: bool, warnings: array, blocked_reason: string|null}
     */
    public function checkBeforeSend(User $user, string $message): array
    {
        $result = ['allowed' => true, 'warnings' => [], 'blocked_reason' => null];

        // 1. Kullanıcı askıya alınmış mı?
        if ($user->is_suspended) {
            $result['allowed'] = false;
            $result['blocked_reason'] = 'Hesabınız askıya alınmıştır. Destek ile iletişime geçin.';
            return $result;
        }

        // 2. Mesaj filtreleme
        $filterResult = $this->messageFilter->analyze($message);

        if ($filterResult['status'] === 'block') {
            $result['allowed'] = false;
            $result['blocked_reason'] = 'Mesajınız güvenlik kontrolünden geçemedi.';

            // Log oluştur
            GuardLog::create([
                'user_id' => $user->id,
                'action' => 'block_message',
                'reason' => 'Mesaj içeriği engellendi',
                'details' => $filterResult,
                'severity' => 'high',
            ]);

            // Risk skorunu güncelle
            $this->riskAnalyzer->calculateRisk($user);

            return $result;
        }

        if ($filterResult['status'] === 'warn') {
            $result['warnings'] = array_map(fn($f) => $f['message'], $filterResult['flags']);

            GuardLog::create([
                'user_id' => $user->id,
                'action' => 'warn',
                'reason' => 'Mesaj uyarı aldı',
                'details' => $filterResult,
                'severity' => 'medium',
            ]);
        }

        // 3. Risk kontrolü
        $riskScore = $this->riskAnalyzer->calculateRisk($user);

        if ($riskScore->risk_score >= 80) {
            // Kritik risk — otomatik askıya al
            $this->suspendUser($user, 'Yüksek risk skoru: ' . $riskScore->risk_score);
            $result['allowed'] = false;
            $result['blocked_reason'] = 'Hesabınız güvenlik nedeniyle askıya alınmıştır.';
            return $result;
        }

        if ($riskScore->risk_score >= 50) {
            $result['warnings'][] = 'Hesabınızda olağandışı aktivite tespit edildi.';
        }

        return $result;
    }

    /**
     * Kullanıcıyı askıya al + bildirim gönder
     */
    public function suspendUser(User $user, string $reason): void
    {
        $user->update([
            'is_suspended' => true,
            'suspended_at' => now(),
            'suspension_reason' => $reason,
        ]);

        GuardLog::create([
            'user_id' => $user->id,
            'action' => 'suspend',
            'reason' => $reason,
            'severity' => 'critical',
        ]);

        // Kullanıcıya bildirim
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Hesap Askıya Alındı',
            'message' => "Hesabınız güvenlik nedeniyle askıya alınmıştır. Sebep: {$reason}",
            'type' => 'error',
        ]);
    }
}
