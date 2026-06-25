<?php

namespace App\Livewire;

use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\SmsCampaign;
use App\Models\SmsMessage;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        // SMS istatistikleri
        $totalSms = SmsMessage::where('user_id', $user->id)->count();
        $deliveredSms = SmsMessage::where('user_id', $user->id)->where('status', 'delivered')->count();
        $failedSms = SmsMessage::where('user_id', $user->id)->where('status', 'failed')->count();
        $pendingSms = SmsMessage::where('user_id', $user->id)->where('status', 'pending')->count();

        // Başarı oranı
        $successRate = $totalSms > 0 ? round(($deliveredSms / $totalSms) * 100, 1) : 0;

        // Kredi bilgileri (User modelindeki alanlar)
        $smsCredits = $user->sms_credits ?? 0;
        $whatsappCredits = $user->whatsapp_credits ?? 0;

        // Rehber istatistikleri
        $totalContacts = Contact::where('user_id', $user->id)->count();
        $totalGroups = ContactGroup::where('user_id', $user->id)->count();

        // Son mesajlar
        $recentMessages = SmsMessage::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Son kampanyalar
        $recentCampaigns = SmsCampaign::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.dashboard', [
            'smsCredits' => $smsCredits,
            'whatsappCredits' => $whatsappCredits,
            'totalSms' => $totalSms,
            'deliveredSms' => $deliveredSms,
            'failedSms' => $failedSms,
            'pendingSms' => $pendingSms,
            'totalContacts' => $totalContacts,
            'totalGroups' => $totalGroups,
            'successRate' => $successRate,
            'recentMessages' => $recentMessages,
            'recentCampaigns' => $recentCampaigns,
        ])->layout('components.layouts.panel', ['title' => 'Dashboard']);
    }
}
