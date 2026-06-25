<?php

namespace App\Services;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VatanSmsService
{
    protected string $baseUrl = 'https://api.vatansms.net/api/v1';

    protected function credentials(): array
    {
        return [
            'api_id'  => SystemSetting::get('vatansms_api_id', ''),
            'api_key' => SystemSetting::get('vatansms_api_key', ''),
        ];
    }

    /**
     * Kullanıcıya özgü credentials.
     * Evraklı + API key atanmış → kendi key'i
     * Diğer tüm durumlar → sistem ana hesabı
     */
    protected function credentialsForUser(User $user): array
    {
        if ($user->hasVatanSmsAccount()) {
            return [
                'api_id'  => $user->vatansms_api_key,
                'api_key' => $user->vatansms_api_secret,
            ];
        }
        return $this->credentials();
    }

    protected function defaultSender(): string
    {
        return SystemSetting::get('vatansms_sender', '');
    }

    protected function messageType(): string
    {
        return SystemSetting::get('vatansms_message_type', 'normal');
    }

    /**
     * 1-N SMS: Aynı mesajı birden fazla numaraya gönder.
     */
    public function send1toN(string $message, array $phones, ?string $sendTime = null, ?string $sender = null): array
    {
        $payload = array_merge($this->credentials(), [
            'sender'               => $sender ?? $this->defaultSender(),
            'message_type'        => $this->messageType(),
            'message'             => $message,
            'message_content_type' => 'bilgi',
            'phones'              => array_values($phones),
        ]);

        if ($sendTime) {
            $payload['send_time'] = $sendTime;
        }

        // Debug: gönderilen payload'ı logla (api_key gizle)
        Log::info('VatanSMS send1toN request', [
            'sender' => $payload['sender'],
            'phones' => $payload['phones'],
            'message_type' => $payload['message_type'],
        ]);

        $result = $this->post('/1toN', $payload);

        Log::info('VatanSMS send1toN response', $result);

        return $result;
    }

    /**
     * N-N SMS: Her numaraya özel mesaj gönder.
     * $phonesMessages = [['phone' => '5xx', 'message' => '...'], ...]
     */
    public function sendNtoN(array $phonesMessages, ?string $sendTime = null, ?string $sender = null): array
    {
        $payload = array_merge($this->credentials(), [
            'sender'               => $sender ?? $this->defaultSender(),
            'message_type'        => $this->messageType(),
            'message_content_type' => 'bilgi',
            'phones'              => $phonesMessages,
        ]);

        if ($sendTime) {
            $payload['send_time'] = $sendTime;
        }

        return $this->post('/NtoN', $payload);
    }

    /**
     * Gönderici adlarını sorgula (sistem hesabı).
     */
    public function getSenders(): array
    {
        return $this->post('/senders', $this->credentials());
    }

    /**
     * Belirli bir kullanıcının başlıklarını çeker.
     * Evraklı kullanıcı → kendi API hesabı
     * Evrakssız → sistem hesabı
     */
    public function getSendersForUser(User $user): array
    {
        $creds = $this->credentialsForUser($user);
        $result = $this->post('/senders', $creds);
        // API yanıt yapısına göre başlık listesini çıkar
        return $result['data'] ?? $result['senders'] ?? [];
    }

    /**
     * Kullanıcı adına SMS gönderir.
     * Evraklı + API → kendi hesabından direkt
     * Diğer → sistem hesabından gönderilir (onay kuyruğu ayrıca kontrol edilmeli)
     */
    public function sendSmsForUser(User $user, string $message, array $phones, string $sender): array
    {
        $creds = $this->credentialsForUser($user);
        $payload = array_merge($creds, [
            'sender'               => $sender,
            'message_type'        => $this->messageType(),
            'message'             => $message,
            'message_content_type'=> 'bilgi',
            'phones'              => array_values($phones),
        ]);
        return $this->post('/1toN', $payload);
    }

    /**
     * Kullanıcı API bağlantısını test eder ve başlık listesini döner.
     */
    public function testConnectionForUser(User $user): array
    {
        if (! $user->hasVatanSmsAccount()) {
            return ['success' => false, 'message' => 'Kullanıcıya VatanSMS API bilgileri atanmamış.'];
        }
        $senders = $this->getSendersForUser($user);
        if (empty($senders)) {
            return ['success' => false, 'message' => 'API bilgileri geçersiz veya başlık bulunamadı.', 'senders' => []];
        }
        return ['success' => true, 'message' => count($senders) . ' başlık bulundu.', 'senders' => $senders];
    }

    /**
     * Kullanıcı bilgilerini al (bakiye vb).
     */
    public function getUserInfo(): array
    {
        return $this->post('/user/information', $this->credentials());
    }

    /**
     * Rapor detayı.
     */
    public function getReport(int $reportId, int $page = 1, int $pageSize = 20): array
    {
        $payload = array_merge($this->credentials(), ['report_id' => $reportId]);
        return $this->post("/report/detail?page={$page}&pageSize={$pageSize}", $payload);
    }

    /**
     * Tarih bazlı rapor.
     */
    public function getReportBetween(string $startDate, string $endDate): array
    {
        $payload = array_merge($this->credentials(), [
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ]);
        return $this->post('/report/between', $payload);
    }

    /**
     * Tekil rapor sonucu.
     */
    public function getReportSingle(int $reportId): array
    {
        $payload = array_merge($this->credentials(), ['report_id' => $reportId]);
        return $this->post('/report/single', $payload);
    }

    /**
     * İleri tarihli SMS iptal.
     */
    public function cancelFutureSms(int $id): array
    {
        $payload = array_merge($this->credentials(), ['id' => $id]);
        return $this->post('/cancel/future-sms', $payload);
    }

    /**
     * Bağlantı testi — kullanıcı bilgisini çekmeyi dener.
     */
    public function testConnection(): array
    {
        $creds = $this->credentials();
        if (empty($creds['api_id']) || empty($creds['api_key'])) {
            return ['success' => false, 'message' => 'API kimlik bilgileri girilmemiş.'];
        }

        $result = $this->getUserInfo();

        if ($result['success'] ?? false) {
            return $result;
        }

        return array_merge($result, ['success' => false]);
    }

    /**
     * HTTP POST isteği gönder.
     */
    protected function post(string $endpoint, array $payload): array
    {
        try {
            $response = Http::timeout(15)
                ->post($this->baseUrl . $endpoint, $payload);

            $body = $response->json() ?? [];

            if ($response->successful()) {
                return array_merge(['success' => true], $body);
            }

            Log::warning('VatanSMS API error', [
                'endpoint' => $endpoint,
                'status'   => $response->status(),
                'body'     => $body,
            ]);

            return array_merge(['success' => false], $body);
        } catch (\Throwable $e) {
            Log::error('VatanSMS API exception', [
                'endpoint'  => $endpoint,
                'exception' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
