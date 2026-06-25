<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'city', 'district', 'address',
        'customer_code', 'account_type', 'company_name', 'tc_no', 'tax_no',
        'tax_office', 'mersis_no', 'contact_person',
        'sms_credits', 'sms_short_code', 'sms_cancel_number', 'whatsapp_credits',
        'whatsapp_api_key', 'whatsapp_phone_id', 'whatsapp_business_id',
        'whatsapp_session_active', 'whatsapp_phone_number', 'whatsapp_display_name', 'whatsapp_connected_at',
        // VatanSMS reseller alanları
        'vatansms_api_key', 'vatansms_api_secret', 'vatansms_sender', 'vatansms_account_id',
        'document_approved',
        'is_admin', 'is_suspended', 'suspended_at', 'suspension_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'       => 'datetime',
            'password'                => 'hashed',
            'is_admin'                => 'boolean',
            'is_suspended'            => 'boolean',
            'suspended_at'            => 'datetime',
            'document_approved'       => 'boolean',
            'vatansms_api_key'        => 'encrypted',
            'vatansms_api_secret'     => 'encrypted',
        ];
    }

    /** Evrak onayı var mı? */
    public function hasApprovedDocuments(): bool
    {
        return (bool) $this->document_approved;
    }

    /** VatanSMS API key atanmış mı? */
    public function hasVatanSmsAccount(): bool
    {
        return ! empty($this->vatansms_api_key) && ! empty($this->vatansms_api_secret);
    }

    /** Direkt SMS gönderebilir mi (onay gerektirmeden)? */
    public function canSendSmsDirectly(): bool
    {
        return $this->hasApprovedDocuments() && $this->hasVatanSmsAccount();
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function contactGroups(): HasMany
    {
        return $this->hasMany(ContactGroup::class);
    }

    public function smsMessages(): HasMany
    {
        return $this->hasMany(SmsMessage::class);
    }

    public function smsCampaigns(): HasMany
    {
        return $this->hasMany(SmsCampaign::class);
    }

    public function smsTemplates(): HasMany
    {
        return $this->hasMany(SmsTemplate::class);
    }

    public function subUsers(): HasMany
    {
        return $this->hasMany(SubUser::class, 'parent_user_id');
    }

    public function senderNames(): HasMany
    {
        return $this->hasMany(SenderName::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function paymentNotifications(): HasMany
    {
        return $this->hasMany(PaymentNotification::class);
    }

    public function blacklistedNumbers(): HasMany
    {
        return $this->hasMany(BlacklistedNumber::class);
    }

    public function whatsappMessages(): HasMany
    {
        return $this->hasMany(WhatsappMessage::class);
    }

    public function riskScore(): HasOne
    {
        return $this->hasOne(UserRiskScore::class);
    }

    public function guardLogs(): HasMany
    {
        return $this->hasMany(GuardLog::class);
    }

    public function loginLogs(): HasMany
    {
        return $this->hasMany(LoginLog::class);
    }

    public function creditLogs(): HasMany
    {
        return $this->hasMany(CreditLog::class);
    }

    public function virtualPosOrders(): HasMany
    {
        return $this->hasMany(VirtualPosOrder::class);
    }
}
