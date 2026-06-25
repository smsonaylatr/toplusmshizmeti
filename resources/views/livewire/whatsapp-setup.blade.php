<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">WhatsApp Kurulum</h1>
    <div class="mb-5"><h2 class="text-base font-semibold text-green-600 border-b-2 border-green-600 inline-block pb-1">WhatsApp Web Bağlantısı</h2></div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
        {{-- Sol: Bağlı Numaralar + Yeni Bağlantı --}}
        <div class="xl:col-span-2 space-y-4">

            {{-- Bağlı Numaralar Listesi --}}
            @if($sessions->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-800">Bağlı Numaralar ({{ $sessions->count() }})</h3>
                    <span class="text-[11px] text-gray-400">Mesaj gönderirken numara seçebilirsiniz</span>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($sessions as $session)
                    <div class="p-4 flex items-center gap-4 hover:bg-green-50/30 transition-colors">
                        {{-- Profil --}}
                        <div class="w-12 h-12 rounded-xl {{ $session->is_default ? 'bg-gradient-to-br from-green-400 to-green-600 shadow-lg shadow-green-500/30' : 'bg-gradient-to-br from-gray-200 to-gray-300' }} flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                        </div>
                        {{-- Bilgi --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-800">{{ $session->display_name }}</span>
                                @if($session->is_default)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-green-100 text-green-700">VARSAYILAN</span>
                                @endif
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-semibold {{ $session->is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1 {{ $session->is_active ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></span>
                                    {{ $session->is_active ? 'Aktif' : 'Pasif' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $session->phone_number }}</p>
                            <p class="text-[10px] text-gray-400">Bağlantı: {{ $session->connected_at?->format('d.m.Y H:i') }}</p>
                        </div>
                        {{-- Aksiyonlar --}}
                        <div class="flex items-center gap-2 shrink-0">
                            @if(!$session->is_default)
                            <button wire:click="setDefault({{ $session->id }})" class="px-3 py-1.5 text-[11px] font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition-colors border border-green-200">
                                Varsayılan Yap
                            </button>
                            @endif
                            <button wire:click="disconnect({{ $session->id }})" wire:confirm="Bu numarayı kaldırmak istediğinize emin misiniz?" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Yeni Numara Bağla --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 bg-gradient-to-r from-green-500 to-green-600 text-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                        <span class="font-bold text-sm">Yeni Numara Bağla</span>
                    </div>
                    @if($status === 'waiting')
                    <span class="text-xs font-medium flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-yellow-300 rounded-full animate-pulse"></span>
                        QR Bekleniyor
                    </span>
                    @endif
                </div>

                <div class="p-6">
                    @if($status === 'disconnected')
                    {{-- Bağlantı Yok → QR Oluştur --}}
                    <div class="text-center py-6">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-green-50 flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-800 mb-1">Yeni WhatsApp Numarası Ekle</h3>
                        <p class="text-xs text-gray-400 mb-5">QR kodu telefonunuzla taratarak yeni bir numara bağlayın</p>
                        <button wire:click="generateQr" class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-bold rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg shadow-green-500/25 hover:-translate-y-0.5 inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            QR KOD OLUŞTUR
                        </button>
                    </div>

                    @elseif($status === 'waiting')
                    {{-- QR Kod Taratma --}}
                    <div class="text-center py-2">
                        <div class="w-56 h-56 mx-auto bg-white border-2 border-gray-100 rounded-2xl p-3 mb-4 relative">
                            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-50 rounded-xl flex items-center justify-center relative overflow-hidden">
                                <div class="grid grid-cols-8 gap-0.5 w-40 h-40">
                                    @for($i = 0; $i < 64; $i++)
                                    <div class="rounded-sm {{ rand(0, 1) ? 'bg-gray-800' : 'bg-white' }}"></div>
                                    @endfor
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute left-3 right-3 h-0.5 bg-green-500 animate-bounce top-1/2 opacity-60 rounded-full"></div>
                        </div>
                        <p class="text-sm font-semibold text-gray-800 mb-0.5">QR Kodu Taratın</p>
                        <p class="text-[11px] text-gray-400 mb-4">WhatsApp > Ayarlar > Bağlı Cihazlar > Cihaz Bağla</p>
                        <div class="flex gap-3 justify-center">
                            <button wire:click="simulateConnect" class="px-6 py-2.5 bg-green-500 text-white text-xs font-bold rounded-xl hover:bg-green-600 transition-all">
                                BAĞLANTIYI KONTROL ET
                            </button>
                            <button wire:click="generateQr" class="px-4 py-2.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-xl hover:bg-gray-200 transition-all">
                                Yenile
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sağ: Bilgi Paneli --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-5 py-3 border-b border-green-100/50">
                    <h4 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Nasıl Bağlanılır?
                    </h4>
                </div>
                <div class="p-5 space-y-3 text-[12px] text-gray-600 leading-relaxed">
                    <div class="flex items-start gap-2"><span class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-[10px] shrink-0 font-bold mt-0.5">1</span><p>Telefonunuzda <strong>WhatsApp</strong>'ı açın</p></div>
                    <div class="flex items-start gap-2"><span class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-[10px] shrink-0 font-bold mt-0.5">2</span><p><strong>Ayarlar > Bağlı Cihazlar</strong>'a gidin</p></div>
                    <div class="flex items-start gap-2"><span class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-[10px] shrink-0 font-bold mt-0.5">3</span><p><strong>Cihaz Bağla</strong>'ya dokunun</p></div>
                    <div class="flex items-start gap-2"><span class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-[10px] shrink-0 font-bold mt-0.5">4</span><p>Ekrandaki <strong>QR kodu</strong> taratın</p></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-5 py-3 border-b border-blue-100/50">
                    <h4 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Çoklu Numara
                    </h4>
                </div>
                <div class="p-5 text-[12px] text-gray-600 leading-relaxed space-y-2">
                    <p>• Birden fazla WhatsApp numarası bağlayabilirsiniz</p>
                    <p>• Mesaj gönderirken hangi numaradan göndereceğinizi seçin</p>
                    <p>• <strong>Varsayılan</strong> numara otomatik seçilir</p>
                    <p>• İstediğiniz zaman numara ekleyip kaldırabilirsiniz</p>
                </div>
            </div>
        </div>
    </div>
</div>
