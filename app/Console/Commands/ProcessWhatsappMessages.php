<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WhatsappMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessWhatsappMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending WhatsApp messages in the queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messages = WhatsappMessage::where('status', 'pending')
            ->orderBy('id', 'asc')
            ->limit(50) // Process in chunks to prevent timeout
            ->get();

        if ($messages->isEmpty()) {
            return Command::SUCCESS;
        }

        $this->info("Processing {$messages->count()} WhatsApp messages.");

        // Kökten Çözüm: Node.js kapalıysa otomatik ayağa kaldır!
        try {
            Http::timeout(2)->get('http://localhost:3000');
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Connection refused')) {
                $this->info("Node.js sunucusu kapalı. Otomatik başlatılıyor...");
                $dir = base_path('whatsapp-server');
                $paths = ['node', '/opt/plesk/node/20/bin/node', '/opt/plesk/node/18/bin/node', '/opt/plesk/node/21/bin/node', '/usr/bin/node', '/usr/local/bin/node'];
                
                foreach ($paths as $nodePath) {
                    $cmd = "cd $dir && $nodePath $(which npm) install; nohup $nodePath index.js > server.log 2>&1 & echo $!";
                    $pid = exec($cmd);
                    if (is_numeric($pid) && $pid > 0) {
                        $this->info("Node.js başarıyla başlatıldı (PID: $pid). 5 saniye bekleniyor...");
                        sleep(5); // Sunucunun açılması için bekle
                        break;
                    }
                }
            }
        }

        foreach ($messages as $msg) {
            // Speed control (yavas = 10s, orta = 5s, hizli = 2s)
            $delay = 5;
            if ($msg->send_speed === 'yavas') $delay = 10;
            if ($msg->send_speed === 'hizli') $delay = 2;

            if (!$msg->whatsapp_session_id) {
                // Backward compatibility: If no session_id is saved, try to find default
                $defaultSession = \App\Models\WhatsappSession::where('user_id', $msg->user_id)
                    ->where('is_default', true)
                    ->first();
                
                if (!$defaultSession) {
                    $msg->update(['status' => 'failed']);
                    Log::error("WhatsApp message {$msg->id} failed: No session assigned.");
                    continue;
                }
                $sessionId = $defaultSession->id;
            } else {
                $sessionId = $msg->whatsapp_session_id;
            }

            try {
                $response = Http::post('http://localhost:3000/message/send', [
                    'sessionId' => (string) $sessionId,
                    'to' => $msg->recipient,
                    'message' => $msg->message,
                ]);

                if ($response->successful() && $response->json('success')) {
                    $msg->update([
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);
                    $this->info("Message {$msg->id} sent.");
                } else {
                    $errorMsg = $response->json('error') ?? 'Unknown error';
                    $msg->update(['status' => 'failed']);
                    Log::error("WhatsApp message {$msg->id} failed: {$errorMsg}");
                }
            } catch (\Exception $e) {
                $msg->update(['status' => 'failed']);
                Log::error("WhatsApp message {$msg->id} exception: " . $e->getMessage());
            }

            // Sleep to respect send speed
            sleep($delay);
        }

        return Command::SUCCESS;
    }
}
