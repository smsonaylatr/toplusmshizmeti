<?php

namespace App\Services;

use App\Models\VirtualPosOrder;
use Illuminate\Http\Request;

class PayTRService
{
    private string $merchantId;
    private string $merchantKey;
    private string $merchantSalt;
    private int    $testMode;
    private string $iframeUrl;

    public function __construct()
    {
        $this->merchantId   = config('paytr.merchant_id', '');
        $this->merchantKey  = config('paytr.merchant_key', '');
        $this->merchantSalt = config('paytr.merchant_salt', '');
        $this->testMode     = (int) config('paytr.test_mode', 1);
        $this->iframeUrl    = config('paytr.iframe_url', 'https://www.paytr.com/odeme/api/get-token');
    }

    /**
     * PayTR'dan iFrame token al.
     * Başarısızsa RuntimeException fırlatır.
     */
    public function getIframeToken(VirtualPosOrder $order, Request $request): string
    {
        // Runtime'da ayarları SystemSetting'den yenile (admin değiştirmiş olabilir)
        $this->refreshFromSettings();

        $user  = $order->user;
        $email = $user->email;
        $phone = preg_replace('/\D/', '', $user->phone ?? '5000000000');
        if (strlen($phone) < 10) {
            $phone = '5000000000';
        }

        // Sepet: tek kalem
        $basketItems = json_encode([
            [$order->package_name, number_format($order->total_amount, 2, '.', ''), 1],
        ]);

        // PayTR taraf için tutar kuruş cinsinden tam sayı
        $paymentAmount = $order->paytr_payment_amount; // zaten kuruş

        // Kullanıcı IP
        $userIp = $request->ip();

        // Callback & success URL'ler
        $callbackUrl = route('payment.callback');
        $successUrl  = route('payment.result');
        $failUrl     = route('payment.result');
        $orderId     = $order->merchant_oid;

        // Hash oluştur (PayTR dökümantasyonuna göre)
        $hashStr = $this->merchantId
            . $userIp
            . $orderId
            . $email
            . $paymentAmount
            . $basketItems
            . 0            // no_installment
            . 0            // max_installment
            . 'TL'
            . $this->testMode
            . $this->merchantSalt;

        $token = base64_encode(hash_hmac('sha256', $hashStr, $this->merchantKey, true));

        $postData = [
            'merchant_id'       => $this->merchantId,
            'user_ip'           => $userIp,
            'merchant_oid'      => $orderId,
            'email'             => $email,
            'payment_amount'    => $paymentAmount,
            'paytr_token'       => $token,
            'user_basket'       => $basketItems,
            'debug_on'          => 1,
            'no_installment'    => 0,
            'max_installment'   => 0,
            'user_name'         => $user->name,
            'user_address'      => $user->address ?? 'Türkiye',
            'user_phone'        => $phone,
            'merchant_ok_url'   => $successUrl,
            'merchant_fail_url' => $failUrl,
            'timeout_limit'     => 30,
            'currency'          => 'TL',
            'test_mode'         => $this->testMode,
            'lang'              => 'tr',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->iframeUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($ch);
        $errno  = curl_errno($ch);
        curl_close($ch);

        if ($errno) {
            throw new \RuntimeException('PayTR bağlantı hatası: curl hata kodu ' . $errno);
        }

        $decoded = json_decode($result, true);

        if (! isset($decoded['status'])) {
            throw new \RuntimeException('PayTR geçersiz yanıt: ' . $result);
        }

        if ($decoded['status'] !== 'success') {
            $reason = $decoded['reason'] ?? 'Bilinmeyen hata';
            throw new \RuntimeException('PayTR token alınamadı: ' . $reason);
        }

        return $decoded['token'];
    }

    /**
     * PayTR callback hash doğrulaması.
     * Doğruysa true, yanlışsa false döner.
     */
    public function validateCallback(array $post): bool
    {
        $this->refreshFromSettings();

        $hash = base64_encode(
            hash_hmac(
                'sha256',
                $post['merchant_oid'] . $this->merchantSalt . $post['status'] . $post['total_amount'],
                $this->merchantKey,
                true
            )
        );

        return hash_equals($hash, $post['hash']);
    }

    /**
     * Benzersiz merchant_oid üret: SMS{userId}_{timestamp}
     */
    public function generateMerchantOid(int $userId): string
    {
        return 'SMS' . $userId . '_' . time();
    }

    /**
     * Admin panelinden değiştirilen ayarları yenile.
     */
    private function refreshFromSettings(): void
    {
        $id   = \App\Models\SystemSetting::get('paytr_merchant_id', '');
        $key  = \App\Models\SystemSetting::get('paytr_merchant_key', '');
        $salt = \App\Models\SystemSetting::get('paytr_merchant_salt', '');
        $test = (int) \App\Models\SystemSetting::get('paytr_test_mode', 1);

        if ($id)   $this->merchantId   = $id;
        if ($key)  $this->merchantKey  = $key;
        if ($salt) $this->merchantSalt = $salt;
        $this->testMode = $test;
    }
}
