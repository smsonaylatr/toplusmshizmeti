<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class AutoMigrate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Yalnızca local ortamda (geliştirme aşamasında) çalışsın
        if (app()->environment('local')) {
            $migrationsPath = database_path('migrations');
            
            if (File::exists($migrationsPath)) {
                $files = File::allFiles($migrationsPath);
                $hash = md5(collect($files)->map(fn($file) => $file->getMTime())->join(','));

                // Eğer migration dosyalarında değişiklik varsa veya yeni dosya eklendiyse
                if (Cache::get('migrations_hash') !== $hash) {
                    try {
                        Artisan::call('migrate', ['--force' => true]);
                        Cache::put('migrations_hash', $hash);
                    } catch (\Exception $e) {
                        // Veritabanı bağlantısı yoksa vs sessizce geç
                    }
                }
            }
        }

        return $next($request);
    }
}
