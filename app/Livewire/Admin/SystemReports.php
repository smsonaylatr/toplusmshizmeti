<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use App\Models\SmsMessage;
use App\Models\WhatsappMessage;
use App\Models\PaymentNotification;
use App\Models\User;

class SystemReports extends Component
{
    public string $period = '7';

    public function render()
    {
        $days = (int) $this->period;

        $smsStats = SmsMessage::where('created_at', '>=', now()->subDays($days))->count();
        $whatsappStats = WhatsappMessage::where('created_at', '>=', now()->subDays($days))->count();
        $revenue = PaymentNotification::where('status', 'confirmed')
            ->where('created_at', '>=', now()->subDays($days))
            ->sum('amount');
        $newUsers = User::where('is_admin', false)
            ->where('created_at', '>=', now()->subDays($days))
            ->count();

        $dailyData = collect(range($days - 1, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);
            return [
                'date' => $date->format('d/m'),
                'sms' => SmsMessage::whereDate('created_at', $date)->count(),
                'whatsapp' => WhatsappMessage::whereDate('created_at', $date)->count(),
                'revenue' => PaymentNotification::where('status', 'confirmed')
                    ->whereDate('created_at', $date)->sum('amount'),
            ];
        });

        $topUsers = User::where('is_admin', false)
            ->withCount(['smsMessages', 'whatsappMessages'])
            ->orderByRaw('(sms_messages_count + whatsapp_messages_count) DESC')
            ->take(10)
            ->get();

        return view('livewire.admin.system-reports', compact('smsStats', 'whatsappStats', 'revenue', 'newUsers', 'dailyData', 'topUsers'))
            ->layout('components.layouts.admin', ['title' => 'Raporlar']);
    }
}
