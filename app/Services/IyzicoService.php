<?php

namespace App\Services;

use App\Models\VirtualPosOrder;
use Illuminate\Http\Request;
use Iyzipay\Model\CheckoutFormInitialize;
use Iyzipay\Model\CheckoutForm;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Options as IyzicoOptions;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Iyzipay\Request\RetrieveCheckoutFormRequest;

class IyzicoService
{
    private IyzicoOptions $options;

    public function __construct()
    {
        $this->options = $this->buildOptions();
    }

    /**
     * iyzico Checkout Form token al.
     * Başarısızsa RuntimeException fırlatır.
     */
    public function getCheckoutForm(VirtualPosOrder $order, Request $request): CheckoutFormInitialize
    {
        $this->refreshFromSettings();

        $user  = $order->user;
        $total = number_format($order->total_amount, 2, '.', '');
        $price = number_format($order->price, 2, '.', '');

        $iyzRequest = new CreateCheckoutFormInitializeRequest();
        $iyzRequest->setLocale('tr');
        $iyzRequest->setConversationId($order->merchant_oid);
        $iyzRequest->setPrice($total);        // KDV dahil toplam
        $iyzRequest->setPaidPrice($total);
        $iyzRequest->setCurrency('TRY');
        $iyzRequest->setBasketId($order->merchant_oid);
        $iyzRequest->setPaymentGroup('PRODUCT');
        $iyzRequest->setCallbackUrl(route('panel.payment.iyzico-callback'));

        // Alıcı
        $buyer = new Buyer();
        $buyer->setId((string) $user->id);
        $buyer->setName($user->name ?? 'Müşteri');
        $buyer->setSurname(' ');
        $buyer->setGsmNumber($this->sanitizePhone($user->phone ?? '5000000000'));
        $buyer->setEmail($user->email);
        $buyer->setIdentityNumber('11111111111');  // TC zorunlu – test değeri
        $buyer->setRegistrationAddress($user->address ?? 'Türkiye');
        $buyer->setIp($request->ip());
        $buyer->setCity('Istanbul');
        $buyer->setCountry('Turkey');
        $iyzRequest->setBuyer($buyer);

        // Fatura & Teslimat Adresi
        $addr = new Address();
        $addr->setContactName($user->name ?? 'Müşteri');
        $addr->setCity('Istanbul');
        $addr->setCountry('Turkey');
        $addr->setAddress($user->address ?? 'Türkiye');
        $iyzRequest->setShippingAddress($addr);
        $iyzRequest->setBillingAddress($addr);

        // Sepet kalemi
        $item = new BasketItem();
        $item->setId($order->merchant_oid);
        $item->setName($order->package_name);
        $item->setCategory1('SMS');
        $item->setItemType(BasketItemType::VIRTUAL);
        $item->setPrice($total);
        $iyzRequest->setBasketItems([$item]);

        $result = CheckoutFormInitialize::create($iyzRequest, $this->options);

        if ($result->getStatus() !== 'success') {
            throw new \RuntimeException(
                'iyzico form oluşturulamadı: ' . $result->getErrorMessage()
            );
        }

        return $result;
    }

    /**
     * iyzico callback'ten gelen token ile ödemeyi doğrula.
     * Başarılı ise 'paid', değilse 'failed' döner.
     */
    public function retrieveCheckoutForm(string $token): array
    {
        $this->refreshFromSettings();

        $req = new RetrieveCheckoutFormRequest();
        $req->setLocale('tr');
        $req->setToken($token);

        $result = CheckoutForm::retrieve($req, $this->options);

        return [
            'status'         => $result->getStatus(),                         // 'success' | 'failure'
            'conversation_id'=> $result->getConversationId(),                 // merchant_oid
            'error_message'  => $result->getErrorMessage(),
            'payment_status' => $result->getPaymentStatus(),
            'fraud_status'   => $result->getFraudStatus(),
        ];
    }

    /**
     * Benzersiz sipariş ID üret: IYZ{userId}_{timestamp}
     */
    public function generateOrderId(int $userId): string
    {
        return 'IYZ' . $userId . '_' . time();
    }

    // ─── Private Helpers ───────────────────────────────────────────────────────

    private function buildOptions(): IyzicoOptions
    {
        $opts = new IyzicoOptions();
        $opts->setApiKey(config('iyzico.api_key', ''));
        $opts->setSecretKey(config('iyzico.secret_key', ''));
        $opts->setBaseUrl(config('iyzico.base_url', 'https://sandbox.iyzipay.com'));
        return $opts;
    }

    private function refreshFromSettings(): void
    {
        $apiKey    = \App\Models\SystemSetting::get('iyzico_api_key', '');
        $secretKey = \App\Models\SystemSetting::get('iyzico_secret_key', '');
        $baseUrl   = \App\Models\SystemSetting::get('iyzico_base_url', '');

        if ($apiKey)    $this->options->setApiKey($apiKey);
        if ($secretKey) $this->options->setSecretKey($secretKey);
        if ($baseUrl)   $this->options->setBaseUrl($baseUrl);
    }

    private function sanitizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (strlen($phone) === 10) {
            $phone = '+90' . $phone;
        } elseif (strlen($phone) === 11 && str_starts_with($phone, '0')) {
            $phone = '+9' . $phone;
        } elseif (! str_starts_with($phone, '+')) {
            $phone = '+90' . $phone;
        }
        return $phone ?: '+905000000000';
    }
}
