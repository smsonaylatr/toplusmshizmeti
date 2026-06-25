<?php

namespace App\Livewire\Admin;

use App\Models\SystemSetting;
use App\Services\VatanSmsService;
use Livewire\Component;

class ApiSettings extends Component
{
    public string $apiId      = '';
    public string $apiKey     = '';
    public string $sender     = '';
    public string $messageType = 'normal';

    // PayTR
    public string $paytrMerchantId   = '';
    public string $paytrMerchantKey  = '';
    public string $paytrMerchantSalt = '';
    public int    $paytrTestMode     = 1;

    // iyzico
    public string $iyzicoApiKey    = '';
    public string $iyzicoSecretKey = '';
    public string $iyzicoBaseUrl   = 'https://sandbox.iyzipay.com';

    // Aktif Gateway
    public string $activeGateway = 'paytr';

    public bool   $showApiKey   = false;
    public ?array $testResult   = null;
    public bool   $testLoading  = false;

    public function mount(): void
    {
        $this->apiId      = SystemSetting::get('vatansms_api_id', '');
        $this->apiKey     = SystemSetting::get('vatansms_api_key', '');
        $this->sender     = SystemSetting::get('vatansms_sender', '');
        $this->messageType = SystemSetting::get('vatansms_message_type', 'normal');

        $this->paytrMerchantId   = SystemSetting::get('paytr_merchant_id', '');
        $this->paytrMerchantKey  = SystemSetting::get('paytr_merchant_key', '');
        $this->paytrMerchantSalt = SystemSetting::get('paytr_merchant_salt', '');
        $this->paytrTestMode     = (int) SystemSetting::get('paytr_test_mode', 1);

        $this->iyzicoApiKey    = SystemSetting::get('iyzico_api_key', '');
        $this->iyzicoSecretKey = SystemSetting::get('iyzico_secret_key', '');
        $this->iyzicoBaseUrl   = SystemSetting::get('iyzico_base_url', 'https://sandbox.iyzipay.com');

        $this->activeGateway = SystemSetting::get('active_payment_gateway', env('ACTIVE_PAYMENT_GATEWAY', 'paytr'));
    }

    public function save(): void
    {
        $this->validate([
            'apiId'       => 'required|string',
            'apiKey'      => 'required|string',
            'sender'      => 'required|string|max:11',
            'messageType' => 'required|in:normal,turkce',
        ], [
            'apiId.required'       => 'API ID zorunludur.',
            'apiKey.required'      => 'API Key zorunludur.',
            'sender.required'      => 'Gönderici adı zorunludur.',
            'sender.max'           => 'Gönderici adı en fazla 11 karakter olabilir.',
            'messageType.in'       => 'Geçersiz mesaj tipi.',
        ]);

        SystemSetting::set('vatansms_api_id', trim($this->apiId));
        SystemSetting::set('vatansms_api_key', trim($this->apiKey));
        SystemSetting::set('vatansms_sender', trim($this->sender));
        SystemSetting::set('vatansms_message_type', $this->messageType);

        SystemSetting::set('paytr_merchant_id', trim($this->paytrMerchantId));
        SystemSetting::set('paytr_merchant_key', trim($this->paytrMerchantKey));
        SystemSetting::set('paytr_merchant_salt', trim($this->paytrMerchantSalt));
        SystemSetting::set('paytr_test_mode', (string) $this->paytrTestMode);

        SystemSetting::set('iyzico_api_key', trim($this->iyzicoApiKey));
        SystemSetting::set('iyzico_secret_key', trim($this->iyzicoSecretKey));
        SystemSetting::set('iyzico_base_url', trim($this->iyzicoBaseUrl));

        SystemSetting::set('active_payment_gateway', $this->activeGateway);

        $this->testResult = null;
        session()->flash('success', 'API ayarları başarıyla kaydedildi.');
    }

    public function testConnection(): void
    {
        $this->testLoading = true;
        $this->testResult  = null;

        $service = new VatanSmsService();
        $this->testResult = $service->testConnection();

        $this->testLoading = false;
    }

    public function toggleShowApiKey(): void
    {
        $this->showApiKey = !$this->showApiKey;
    }

    public function render()
    {
        return view('livewire.admin.api-settings')
            ->layout('components.layouts.admin', ['title' => 'API Ayarları']);
    }
}
