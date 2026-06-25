<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\SmsMessage;
use App\Models\WhatsappMessage;
use App\Models\SenderName;
use App\Models\Document;
use App\Models\PaymentNotification;
use App\Models\GuardLog;
use App\Models\UserRiskScore;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        $today = today();
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        // Temel İstatistikler
        $stats = [
            'totalUsers'      => User::count(),
            'activeUsers'     => User::where('is_suspended', false)->where('is_admin', false)->count(),
            'suspendedUsers'  => User::where('is_suspended', true)->count(),
            'newUsersToday'   => User::whereDate('created_at', $today)->count(),
            'newUsersWeek'    => User::where('created_at', '>=', now()->subDays(7))->count(),
            'newUsersMonth'   => User::where('created_at', '>=', $thisMonth)->count(),
        ];

        // Günlük Trafik
        $traffic = [
            'todaySms'        => SmsMessage::whereDate('created_at', $today)->count(),
            'todayWhatsapp'   => WhatsappMessage::whereDate('created_at', $today)->count(),
            'yesterdaySms'    => SmsMessage::whereDate('created_at', $today->copy()->subDay())->count(),
            'yesterdayWa'     => WhatsappMessage::whereDate('created_at', $today->copy()->subDay())->count(),
            'weekSms'         => SmsMessage::where('created_at', '>=', now()->subDays(7))->count(),
            'weekWhatsapp'    => WhatsappMessage::where('created_at', '>=', now()->subDays(7))->count(),
            'monthSms'        => SmsMessage::where('created_at', '>=', $thisMonth)->count(),
            'monthWhatsapp'   => WhatsappMessage::where('created_at', '>=', $thisMonth)->count(),
            'totalSms'        => SmsMessage::count(),
            'totalWhatsapp'   => WhatsappMessage::count(),
        ];

        // Mesaj başarı/başarısızlık oranları
        $deliveryStats = [
            'delivered' => SmsMessage::where('status', 'delivered')->count(),
            'sent'      => SmsMessage::where('status', 'sent')->count(),
            'failed'    => SmsMessage::where('status', 'failed')->count(),
            'pending'   => SmsMessage::where('status', 'pending')->count(),
        ];
        $totalMessages = max(array_sum($deliveryStats), 1);
        $deliveryRate = round(($deliveryStats['delivered'] / $totalMessages) * 100, 1);

        // Muhasebe
        $accounting = [
            'totalRevenue'     => PaymentNotification::where('status', 'confirmed')->sum('amount'),
            'monthRevenue'     => PaymentNotification::where('status', 'confirmed')->where('created_at', '>=', $thisMonth)->sum('amount'),
            'lastMonthRevenue' => PaymentNotification::where('status', 'confirmed')->whereBetween('created_at', [$lastMonth, $lastMonthEnd])->sum('amount'),
            'pendingPayments'  => PaymentNotification::where('status', 'pending')->count(),
            'pendingAmount'    => PaymentNotification::where('status', 'pending')->sum('amount'),
            'totalSmsCredits'  => User::sum('sms_credits'),
            'totalWaCredits'   => User::sum('whatsapp_credits'),
        ];

        // Onay Bekleyenler
        $pending = [
            'senders'   => SenderName::where('status', 'pending')->count(),
            'documents' => Document::where('status', 'pending')->count(),
            'payments'  => PaymentNotification::where('status', 'pending')->count(),
        ];

        // Guard İstatistikleri
        $guard = [
            'alerts'       => GuardLog::where('is_resolved', false)->count(),
            'todayFlags'   => GuardLog::whereDate('created_at', $today)->count(),
            'highRisk'     => UserRiskScore::where('risk_score', '>=', 60)->count(),
            'blocked'      => GuardLog::where('action', 'block_message')->count(),
        ];

        // Son 7 gün grafiği
        $chartData = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);
            return [
                'date'     => $date->format('d M'),
                'day'      => $date->translatedFormat('D'),
                'sms'      => SmsMessage::whereDate('created_at', $date)->count(),
                'whatsapp' => WhatsappMessage::whereDate('created_at', $date)->count(),
                'users'    => User::whereDate('created_at', $date)->count(),
                'revenue'  => PaymentNotification::where('status', 'confirmed')->whereDate('created_at', $date)->sum('amount'),
            ];
        });

        $maxTraffic = max($chartData->max('sms'), $chartData->max('whatsapp'), 1);

        // Son Kayıtlar
        $recentUsers = User::where('is_admin', false)->latest()->take(5)->get();
        $recentLogs  = GuardLog::with('user')->latest()->take(5)->get();
        $recentPayments = PaymentNotification::with('user')->where('status', 'confirmed')->latest()->take(5)->get();

        return view('livewire.admin.dashboard', compact(
            'stats', 'traffic', 'deliveryStats', 'deliveryRate', 'accounting',
            'pending', 'guard', 'chartData', 'maxTraffic',
            'recentUsers', 'recentLogs', 'recentPayments'
        ))->layout('components.layouts.admin', ['title' => 'Dashboard']);
    }
}
