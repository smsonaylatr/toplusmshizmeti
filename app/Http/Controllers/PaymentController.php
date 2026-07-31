<?php

namespace App\Http\Controllers;

use App\Models\CreditLog;
use App\Models\Notification;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\VirtualPosOrder;
use App\Services\IyzicoService;
use App\Services\PayTRService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Sabit paket listesi (PricingList ile senkron)
    public const PACKAGES = [
        ['name' => '1.000 SMS',   'sms' => 1000,   'price' => 350.00],
        ['name' => '2.500 SMS',   'sms' => 2500,   'price' => 750.00],
        ['name' => '5.000 SMS',   'sms' => 5000,   'price' => 1250.00],
        ['name' => '10.000 SMS',  'sms' => 10000,  'price' => 2000.00],
        ['name' => '25.000 SMS',  'sms' => 25000,  'price' => 4375.00],
        ['name' => '50.000 SMS',  'sms' => 50000,  'price' => 7500.00],
        ['name' => '100.000 SMS', 'sms' => 100000, 'price' => 13000.00],
    ];

    /**
     * Ödeme başlat — aktif gateway'e göre yönlendir.
     */
    public function startPayment(Request $request)
    {
        $request->validate([
            'package_index' => 'required|integer|min:0',
            'package_type'  => 'nullable|string|in:sms,whatsapp'
        ]);

        $type = $request->input('package_type', 'sms');
        
        if ($type === 'whatsapp') {
            $packages = \App\Livewire\WhatsappPricing::PACKAGES;
            $pkg = $packages[$request->input('package_index')];
            // WhatsappPricing packages use 'credits' instead of 'sms', 'amount' instead of 'sms' etc?
            // Wait, let's normalize the package data format:
            $pkgData = [
                'name'  => $pkg['name'],
                'sms'   => $pkg['credits'] ?? $pkg['sms'],
                'price' => $pkg['price']
            ];
        } else {
            $packages = self::PACKAGES;
            $pkg = $packages[$request->input('package_index')];
            $pkgData = [
                'name'  => $pkg['name'],
                'sms'   => $pkg['sms'],
                'price' => $pkg['price']
            ];
        }

        $vatRate   = 20 / 100;
        $vatAmount = round($pkgData['price'] * $vatRate, 2);
        $total     = round($pkgData['price'] + $vatAmount, 2);

        $gateway = SystemSetting::get('active_payment_gateway', env('ACTIVE_PAYMENT_GATEWAY', 'paytr'));

        if ($gateway === 'iyzico') {
            return $this->startIyzicoPayment($request, $pkgData, $type, $vatAmount, $total);
        }

        return $this->startPaytrPayment($request, $pkgData, $type, $vatAmount, $total);
    }

    // ─── PayTR ────────────────────────────────────────────────────────────────

    private function startPaytrPayment(Request $request, array $pkg, string $type, float $vatAmount, float $total)
    {
        $paytr = new PayTRService();
        $merchantOid = $paytr->generateMerchantOid(auth()->id());

        $order = VirtualPosOrder::create([
            'user_id'              => auth()->id(),
            'package_type'         => $type,
            'package_name'         => $pkg['name'],
            'sms_amount'           => $pkg['sms'],
            'price'                => $pkg['price'],
            'vat_amount'           => $vatAmount,
            'total_amount'         => $total,
            'status'               => 'pending',
            'merchant_oid'         => $merchantOid,
            'paytr_payment_amount' => (int) ($total * 100),
        ]);

        try {
            $iframeToken = $paytr->getIframeToken($order, $request);
        } catch (\RuntimeException $e) {
            $order->update(['status' => 'failed', 'failure_message' => $e->getMessage()]);
            return back()->withErrors(['payment' => 'PayTR ödeme sayfası açılamadı: ' . $e->getMessage()]);
        }

        return view('payment.checkout', [
            'order'       => $order,
            'iframeToken' => $iframeToken,
            'gateway'     => 'paytr',
        ]);
    }

    // ─── iyzico ───────────────────────────────────────────────────────────────

    private function startIyzicoPayment(Request $request, array $pkg, string $type, float $vatAmount, float $total)
    {
        $iyzico  = new IyzicoService();
        $orderId = $iyzico->generateOrderId(auth()->id());

        $order = VirtualPosOrder::create([
            'user_id'              => auth()->id(),
            'package_type'         => $type,
            'package_name'         => $pkg['name'],
            'sms_amount'           => $pkg['sms'],
            'price'                => $pkg['price'],
            'vat_amount'           => $vatAmount,
            'total_amount'         => $total,
            'status'               => 'pending',
            'merchant_oid'         => $orderId,
            'paytr_payment_amount' => (int) ($total * 100),
        ]);

        try {
            $form = $iyzico->getCheckoutForm($order, $request);
        } catch (\RuntimeException $e) {
            $order->update(['status' => 'failed', 'failure_message' => $e->getMessage()]);
            return back()->withErrors(['payment' => 'iyzico ödeme sayfası açılamadı: ' . $e->getMessage()]);
        }

        return view('payment.iyzico-checkout', [
            'order'           => $order,
            'checkoutContent' => $form->getCheckoutFormContent(),
        ]);
    }

    // ─── PayTR Callback ───────────────────────────────────────────────────────

    /**
     * PayTR Bildirim URL — CSRF muaf.
     */
    public function callback(Request $request)
    {
        $post  = $request->all();
        $paytr = new PayTRService();

        if (! $paytr->validateCallback($post)) {
            return response('INVALID', 400);
        }

        $order = VirtualPosOrder::where('merchant_oid', $post['merchant_oid'])->first();
        if (! $order || $order->status === 'paid') {
            return response('OK');
        }

        if ($post['status'] === 'success') {
            $cardLast4 = $post['card_number'] ?? null;

            $order->update([
                'status'         => 'paid',
                'paid_at'        => now(),
                'card_last_four' => $cardLast4 ? substr(trim($cardLast4), -4) : null,
            ]);

            $this->creditUser($order, 'paytr_payment', 'PayTR');
        } else {
            $order->update([
                'status'          => 'failed',
                'failure_message' => $post['failed_reason_msg'] ?? 'Ödeme başarısız',
            ]);
        }

        return response('OK');
    }

    // ─── iyzico Callback ──────────────────────────────────────────────────────

    /**
     * iyzico Callback URL — CSRF muaf.
     */
    public function iyzicoCallback(Request $request)
    {
        $token = $request->input('token');

        if (! $token) {
            return redirect()->route('panel.payment.result');
        }

        $iyzico = new IyzicoService();

        try {
            $result = $iyzico->retrieveCheckoutForm($token);
        } catch (\Throwable $e) {
            return redirect()->route('panel.payment.result');
        }

        // conversationId = merchant_oid
        $merchantOid = $result['conversation_id'] ?? null;
        if (! $merchantOid) {
            return redirect()->route('panel.payment.result');
        }

        $order = VirtualPosOrder::where('merchant_oid', $merchantOid)->first();
        if (! $order || $order->status === 'paid') {
            return redirect()->route('panel.payment.result', ['merchant_oid' => $merchantOid]);
        }

        if ($result['status'] === 'success' && $result['payment_status'] === 'SUCCESS') {
            $order->update([
                'status'  => 'paid',
                'paid_at' => now(),
            ]);
            $this->creditUser($order, 'iyzico_payment', 'iyzico');
        } else {
            $order->update([
                'status'          => 'failed',
                'failure_message' => $result['error_message'] ?? 'iyzico ödeme başarısız',
            ]);
        }

        return redirect()->route('panel.payment.result', ['merchant_oid' => $merchantOid]);
    }

    // ─── Ortak: Kredi Yükleme ─────────────────────────────────────────────────

    private function creditUser(VirtualPosOrder $order, string $logType, string $gatewayLabel): void
    {
        $credits = (int) $order->sms_amount; // Use actual amount from order
        $user    = User::findOrFail($order->user_id);
        
        $creditField = $order->package_type === 'whatsapp' ? 'whatsapp_credits' : 'sms_credits';
        $creditName  = $order->package_type === 'whatsapp' ? 'WhatsApp' : 'SMS';

        $user->increment($creditField, $credits);

        CreditLog::record(
            $user->id, 'in', $logType, $credits,
            $user->fresh()->$creditField,
            "{$gatewayLabel} ödeme: {$order->package_name} ({$order->merchant_oid})",
            $order->merchant_oid
        );

        Notification::create([
            'user_id' => $user->id,
            'title'   => 'Ödeme Onaylandı',
            'message' => "{$order->package_name} satın alımınız tamamlandı. {$credits} {$creditName} kredisi hesabınıza yüklendi.",
            'type'    => 'success',
        ]);
    }

    // ─── Sonuç Sayfası ────────────────────────────────────────────────────────

    public function result(Request $request)
    {
        $merchantOid = $request->query('merchant_oid');
        $order       = null;

        if ($merchantOid) {
            $order = VirtualPosOrder::where('merchant_oid', $merchantOid)
                ->where('user_id', auth()->id())
                ->first();
        }

        if (! $order) {
            $order = VirtualPosOrder::where('user_id', auth()->id())
                ->latest()->first();
        }

        return view('payment.result', compact('order'));
    }
}
