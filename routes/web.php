<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PaymentController;
use App\Livewire\BankAccounts;
use App\Livewire\BirthdayTemplate;
use App\Livewire\Blacklist;
use App\Livewire\Contacts;
use App\Livewire\Dashboard;
use App\Livewire\Documents;
use App\Livewire\LcvCreate;
use App\Livewire\PaymentNotification;
use App\Livewire\PricingList;
use App\Livewire\Reports;
use App\Livewire\RejectedReports;
use App\Livewire\SenderNames;
use App\Livewire\Settings;
use App\Livewire\SmsBulk;
use App\Livewire\SmsCustomExcel;
use App\Livewire\SmsExcel;
use App\Livewire\SmsSelected;
use App\Livewire\SmsSend;
use App\Livewire\SmsSingle;
use App\Livewire\SubUsers;
use App\Livewire\TemplateCreate;
use App\Livewire\TemplateList;
use App\Livewire\VirtualPosOrders;
use App\Livewire\WhatsappBulk;
use App\Livewire\WhatsappExcel;
use App\Livewire\WhatsappPricing;
use App\Livewire\WhatsappReports;
use App\Livewire\WhatsappSend;
use App\Livewire\WhatsappSetup;
use App\Livewire\WhatsappSingle;
use Illuminate\Support\Facades\Route;

// Redirect root to panel
Route::get('/', function () {
    return redirect()->route('panel.dashboard');
});

Route::get('/test-queue', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('whatsapp:process');
        return '<pre>Görev çalıştı. Çıktı: ' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return '<pre>HATA: ' . $e->getMessage() . "\n" . $e->getTraceAsString() . '</pre>';
    }
});

Route::get('/test-logs', function () {
    $path = storage_path('logs/laravel.log');
    if (!file_exists($path)) {
        return 'Log dosyası bulunamadı.';
    }
    // Son 50 satırı oku
    $lines = file($path);
    $lastLines = array_slice($lines, -50);
    return '<pre style="background:#222;color:#0f0;padding:10px;overflow-x:auto;">' . implode("", $lastLines) . '</pre>';
});

Route::get('/start-node', function () {
    $dir = base_path('whatsapp-server');
    
    // Yüklü olabilecek olası node yolları
    $paths = ['node', '/opt/plesk/node/20/bin/node', '/opt/plesk/node/18/bin/node', '/opt/plesk/node/21/bin/node', '/usr/bin/node', '/usr/local/bin/node'];
    
    // Zaten çalışıyor mu?
    $isRunning = false;
    exec("pgrep -f 'index.js'", $pids);
    if (!empty($pids)) {
        return "Node.js zaten çalışıyor! PID: " . implode(', ', $pids);
    }

    $output = [];
    foreach ($paths as $nodePath) {
        // npm install ve nohup başlatma komutu
        $cmd = "cd $dir && $nodePath $(which npm) install; nohup $nodePath index.js > server.log 2>&1 & echo $!";
        $pid = exec($cmd, $out, $status);
        
        if ($pid > 0 && is_numeric($pid)) {
            return "<pre>HARİKA! Node.js sunucusu başarıyla başlatıldı.\nKullanılan Node: $nodePath\nPID: $pid\n\nArtık Plesk'te hiçbir ayar yapmanıza gerek yok. Gidip test mesajı atabilirsiniz!</pre>";
        }
    }

    return "Node.js başlatılamadı. Plesk'te Node yüklü olmayabilir.";
});

Route::get('/test-node', function () {
    $logFile = base_path('whatsapp-server/server.log');
    if (!file_exists($logFile)) {
        return "server.log henüz oluşmamış.";
    }
    return "<pre>" . file_get_contents($logFile) . "</pre>";
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Panel Routes (Livewire)
Route::middleware('auth')->prefix('panel')->name('panel.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    // SMS İşlemleri
    Route::get('/sms/create', SmsBulk::class)->name('sms.create');
    Route::get('/sms/excel', SmsExcel::class)->name('sms.excel');
    Route::get('/sms/custom-excel', SmsCustomExcel::class)->name('sms.customExcel');
    Route::get('/sms/bulk', SmsSend::class)->name('sms.bulk');
    Route::get('/sms/selected', SmsSelected::class)->name('sms.selected');
    Route::get('/sms/single', SmsSingle::class)->name('sms.single');

    // WhatsApp İşlemleri
    Route::get('/whatsapp/setup', WhatsappSetup::class)->name('whatsapp.setup');
    Route::get('/whatsapp/groups', WhatsappBulk::class)->name('whatsapp.groups');
    Route::get('/whatsapp/excel', WhatsappExcel::class)->name('whatsapp.excel');
    Route::get('/whatsapp/bulk', WhatsappSend::class)->name('whatsapp.bulk');
    Route::get('/whatsapp/single', WhatsappSingle::class)->name('whatsapp.single');
    Route::get('/whatsapp/reports', WhatsappReports::class)->name('whatsapp.reports');
    Route::get('/whatsapp/pricing', WhatsappPricing::class)->name('whatsapp.pricing');

    // LCV İşlemleri
    Route::get('/lcv/create', LcvCreate::class)->name('lcv.create');

    // Rehber
    Route::get('/contacts', Contacts::class)->name('contacts.index');

    // Raporlar
    Route::get('/reports', Reports::class)->name('reports.index');
    Route::get('/reports/rejected', RejectedReports::class)->name('reports.rejected');

    // Alt Kullanıcı İşlemleri
    Route::get('/sub-users', SubUsers::class)->name('subusers.index');

    // Kara Liste
    Route::get('/blacklist', Blacklist::class)->name('blacklist.index');

    // Şablonlar
    Route::get('/templates/create', TemplateCreate::class)->name('templates.create');
    Route::get('/templates', TemplateList::class)->name('templates.index');
    Route::get('/templates/birthday', BirthdayTemplate::class)->name('templates.birthday');

    // Ödeme İşlemleri
    Route::get('/pricing', PricingList::class)->name('pricing.index');
    Route::get('/payment-notification', PaymentNotification::class)->name('payment.notification');
    Route::get('/bank-accounts', BankAccounts::class)->name('bank.accounts');

    // Ayarlar
    Route::get('/settings', Settings::class)->name('settings.index');

    // Evrak İşlemleri
    Route::get('/documents', Documents::class)->name('documents.index');

    // Gönderici Adları
    Route::get('/sender-names', SenderNames::class)->name('sendernames.index');

    // Bildirimler
    Route::post('/notifications/read-all', function () {
        \App\Models\Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        return back();
    })->name('notifications.readAll');

    // Sanal POS
    Route::post('/payment/start', [PaymentController::class, 'startPayment'])->name('payment.start');
    Route::get('/payment/result', [PaymentController::class, 'result'])->name('payment.result');
    Route::get('/payment/orders', VirtualPosOrders::class)->name('payment.orders');
});

// PayTR Callback - CSRF muâf
Route::post('/panel/payment/callback', [PaymentController::class, 'callback'])
    ->name('panel.payment.callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// iyzico Callback - CSRF muâf
Route::post('/panel/payment/iyzico-callback', [PaymentController::class, 'iyzicoCallback'])
    ->name('panel.payment.iyzico-callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
// ===== ADMIN PANEL =====
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Users as AdminUsers;
use App\Livewire\Admin\UserDetail as AdminUserDetail;
use App\Livewire\Admin\SmsMessages as AdminSmsMessages;
use App\Livewire\Admin\SmsCampaigns as AdminSmsCampaigns;
use App\Livewire\Admin\WhatsappMessages as AdminWhatsappMessages;
use App\Livewire\Admin\WhatsappSessions as AdminWhatsappSessions;
use App\Livewire\Admin\SenderApprovals;
use App\Livewire\Admin\DocumentApprovals;
use App\Livewire\Admin\PaymentApprovals;
use App\Livewire\Admin\Notifications as AdminNotifications;
use App\Livewire\Admin\SystemBlacklist;
use App\Livewire\Admin\SystemReports;
use App\Livewire\Admin\GuardDashboard;
use App\Livewire\Admin\GuardLogs;
use App\Livewire\Admin\UserRisks;
use App\Livewire\Admin\MessageFilters as AdminMessageFilters;
use App\Livewire\Admin\SuspendedUsers;
use App\Livewire\Admin\MessageScan;
use App\Livewire\Admin\GuardSettings;
use App\Livewire\Admin\ApiSettings as AdminApiSettings;
use App\Livewire\Admin\VirtualPosOrders as AdminVirtualPosOrders;
use App\Livewire\Admin\SmsApprovalQueueComponent as AdminSmsApprovalQueue;

Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/', AdminDashboard::class)->name('admin.dashboard');

    // Kullanıcı Yönetimi
    Route::get('/users', AdminUsers::class)->name('admin.users');
    Route::get('/users/{userId}', AdminUserDetail::class)->name('admin.users.detail');

    // SMS Yönetimi
    Route::get('/sms', AdminSmsMessages::class)->name('admin.sms');
    Route::get('/sms/campaigns', AdminSmsCampaigns::class)->name('admin.sms.campaigns');
    Route::get('/sms/queue', AdminSmsApprovalQueue::class)->name('admin.sms.queue');

    // WhatsApp Yönetimi
    Route::get('/whatsapp', AdminWhatsappMessages::class)->name('admin.whatsapp');
    Route::get('/whatsapp/sessions', AdminWhatsappSessions::class)->name('admin.whatsapp.sessions');

    // Onay İşlemleri
    Route::get('/approvals/senders', SenderApprovals::class)->name('admin.approvals.senders');
    Route::get('/approvals/documents', DocumentApprovals::class)->name('admin.approvals.documents');
    Route::get('/approvals/payments', PaymentApprovals::class)->name('admin.approvals.payments');

    // Bildirimler & Kara Liste & Raporlar
    Route::get('/notifications', AdminNotifications::class)->name('admin.notifications');
    Route::get('/blacklist', SystemBlacklist::class)->name('admin.blacklist');
    Route::get('/reports', SystemReports::class)->name('admin.reports');

    // AI GuardSystem
    Route::get('/guard', GuardDashboard::class)->name('admin.guard');
    Route::get('/guard/logs', GuardLogs::class)->name('admin.guard.logs');
    Route::get('/guard/risks', UserRisks::class)->name('admin.guard.risks');
    Route::get('/guard/filters', AdminMessageFilters::class)->name('admin.guard.filters');
    Route::get('/guard/suspended', SuspendedUsers::class)->name('admin.guard.suspended');
    Route::get('/guard/scan', MessageScan::class)->name('admin.guard.scan');
    Route::get('/guard/settings', GuardSettings::class)->name('admin.guard.settings');

    // API Ayarları
    Route::get('/settings/api', AdminApiSettings::class)->name('admin.settings.api');

    // Banka Hesapları
    Route::get('/bank-accounts', \App\Livewire\Admin\BankAccounts::class)->name('admin.bank.accounts');

    // Sanal POS
    Route::get('/virtual-pos/orders', AdminVirtualPosOrders::class)->name('admin.virtualpos.orders');
});

