<?php

namespace App\Jobs;

use App\Services\VatanSmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendVatanSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 5;

    public function __construct(
        public readonly string  $message,
        public readonly array   $phones,
        public readonly ?string $sender = null,
        public readonly ?string $sendTime = null,
        public readonly bool    $nToN = false,          // true → NtoN, false → 1toN
        public readonly array   $phonesMessages = [],   // NtoN payload
    ) {}

    public function handle(VatanSmsService $service): void
    {
        try {
            if ($this->nToN) {
                $service->sendNtoN($this->phonesMessages, $this->sendTime, $this->sender);
            } else {
                $service->send1toN($this->message, $this->phones, $this->sendTime, $this->sender);
            }
        } catch (\Throwable $e) {
            Log::error('SendVatanSms job failed', [
                'phones'    => $this->phones,
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
