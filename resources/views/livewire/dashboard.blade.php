<div>
    {{-- Hoşgeldiniz --}}
    <div class="mb-5">
        <h1 class="text-xl font-bold text-gray-800">Hoşgeldiniz</h1>
        <p class="text-sm text-gray-500">{{ auth()->user()->name }}</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Müşteri Kodu --}}
        <div class="bg-[#8e44ad] rounded-lg p-4 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <p class="text-white/70 text-[11px] font-medium uppercase tracking-wider">Müşteri Kodu</p>
                <p class="text-white text-lg font-bold">N-{{ auth()->id() }}</p>
            </div>
        </div>

        {{-- Kredi --}}
        <div class="bg-[#2563eb] rounded-lg p-4 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </div>
            <div>
                <p class="text-white/70 text-[11px] font-medium uppercase tracking-wider">Kredi</p>
                <p class="text-white text-lg font-bold">{{ number_format($smsCredits) }}</p>
            </div>
        </div>

        {{-- WhatsApp Kredi --}}
        <div class="bg-[#2563eb] rounded-lg p-4 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
            </div>
            <div>
                <p class="text-white/70 text-[11px] font-medium uppercase tracking-wider">WhatsApp Kredi</p>
                <p class="text-white text-lg font-bold">{{ number_format($whatsappCredits) }}</p>
            </div>
        </div>

        {{-- Toplam Kişi --}}
        <div class="bg-[#10b981] rounded-lg p-4 flex items-center gap-4 shadow-sm hover:bg-[#059669] transition-colors">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-white/70 text-[11px] font-medium uppercase tracking-wider">Toplam Kişi</p>
                <p class="text-white text-lg font-bold">{{ number_format($totalContacts) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-5">
        {{-- Sol: Tekil SMS Gönder --}}
        <div class="xl:col-span-3">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-5 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800">Tekil Numaraya SMS Gönder</h3>
                </div>

                {{-- Hatırlatma --}}
                <div class="mx-5 mt-4 p-3 bg-[#2563eb] rounded text-[12px] text-white leading-relaxed">
                    <strong>HATIRLATMA!</strong><br>
                    Ticari SMS içeriklerinde; firmalar için Mersis No, şahıs işletmelerinde ise; Ad Soyad ve TC.Kimlik Numarası, telefon veya email gibi iletişim bilgisinin RET Hizmeti ile birlikte bulunması önem teşkil etmektedir.
                </div>

                <div class="p-5 space-y-4">
                    {{-- Tabs --}}
                    <div class="flex border-b border-gray-200">
                        <button class="px-4 py-2 text-xs font-medium text-[#2563eb] border-b-2 border-[#2563eb]">İPTAL LİNKİ</button>
                        <button class="px-4 py-2 text-xs font-medium text-gray-400 hover:text-gray-600">KISA KOD</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Sol form alanları --}}
                        <div class="space-y-3">
                            <div class="relative">
                                <input type="text" placeholder="Alıcı Telefon Numa..." class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                                <p class="text-[11px] text-gray-400 mt-1">1 SMS / 5 Karakter</p>
                            </div>
                            <div class="relative">
                                <select class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                                    <option>08507063457</option>
                                </select>
                                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">* Gönderici Adı</label>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="relative">
                                <select class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                                    <option>Normal</option>
                                    <option>İnteraktif</option>
                                </select>
                                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Tür</label>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="relative">
                                <select class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                                    <option value="">Şablon Seçiniz</option>
                                </select>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="relative">
                                <select class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                                    <option>Hemen Gönder</option>
                                    <option>Zamanla</option>
                                </select>
                                <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">* Gönderim Zamanı</label>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="relative">
                                <select class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm text-gray-600 focus:outline-none focus:ring-1 focus:ring-[#2563eb] appearance-none bg-white">
                                    <option value="">İleti Türü</option>
                                </select>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>

                            {{-- Son 5 Mesaj button --}}
                            <button class="px-4 py-2 bg-[#2563eb] text-white text-xs font-medium rounded hover:bg-[#1d4ed8] transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                SON 5 MESAJ
                            </button>
                        </div>

                        {{-- Sağ: Mesaj alanı --}}
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Mesajınızı bu alana giriniz</label>
                                <textarea rows="14" placeholder="Mesajınızı yazın..." class="w-full px-3 py-2 border border-gray-300 rounded text-sm resize-y focus:outline-none focus:ring-1 focus:ring-[#2563eb]"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- MESAJI GÖNDER button --}}
                    <div>
                        <button class="w-full py-3 bg-[#2563eb] text-white text-sm font-bold rounded hover:bg-[#1d4ed8] transition-all duration-200 flex items-center justify-center gap-2 tracking-wider shadow-md hover:shadow-lg">
                            MESAJI GÖNDER
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sağ: Duyurular --}}
        <div class="xl:col-span-2 space-y-4">
            {{-- Duyuru 1: LCV --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <h4 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    Yeni – LCV | ONLİNE DAVETİYE ve ONAY
                    <span class="text-blue-500">⚠️</span>
                </h4>
                <div class="mt-3 space-y-2 text-[12px] text-gray-600 leading-relaxed text-center">
                    <p class="font-medium text-gray-700">İşinize çok yarayacak yepyeni bir özellik!</p>
                    <p><strong>Online Davetiye ve Katılım Talebi</strong> Toplama Hizmeti.</p>
                    <p class="text-gray-500">Davetiyenizi online tasarlayıp kişiye özel SMS ile gönderin, veya Instagram, Facebook, Web sayfanızda paylaşın.</p>
                    <p class="text-gray-500">Katılımcı bilgilerini ad-soyad, telefon, kurum adıyla raporlayın.</p>
                    <p class="text-gray-500">Toplantı, konferans, düğün, tüm etkinlik ve lansman kampanyalarınızda kullanabilirsiniz.</p>
                    <button class="w-full mt-2 px-3 py-2 bg-[#2563eb] text-white text-xs font-medium rounded hover:bg-[#1d4ed8] transition-colors">
                        TALEP FORMU
                    </button>
                </div>
            </div>

            {{-- Duyuru 2: OKUNDU LİNKİ --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <h4 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    Yeni – OKUNDU LİNKİ
                    <span class="text-blue-500">⚠️</span>
                </h4>
                <div class="mt-3 text-[12px] text-gray-600 leading-relaxed text-center">
                    <p>Kullanıcılarımızın yoğun talebi üzerine geliştirdiğimiz <strong>"MESAJI OKUDUM"</strong> onay butonu; gönderdiğiniz mesajların alıcı tarafından okunduğuna dair teyit almak istediğinizde kullanabilirsiniz.</p>
                    <p class="mt-2">Yolladığınız SMS mesajlarının alıcı tarafından okunduğundan emin olmak istiyorsanız bu özellik tam size göre! <strong class="text-red-500">ONAY DURUMLARINI</strong> raporlar kısmından takip edin.</p>
                    <button class="w-full mt-3 px-3 py-2 bg-[#2563eb] text-white text-xs font-medium rounded hover:bg-[#1d4ed8] transition-colors">
                        TALEP FORMU
                    </button>
                </div>
            </div>
        </div>
    </div>



    {{-- Son SMS Mesajları --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800">Son SMS Mesajları({{ $recentMessages->count() }})</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentMessages->take(5) as $msg)
                <div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50/50 transition-colors cursor-pointer">
                    <p class="text-sm text-gray-600 truncate flex-1 pr-4">{{ $msg->message ?: 'Mesaj içeriği yok' }}</p>
                    <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            @empty
                <div class="px-5 py-6 text-center text-sm text-gray-400">Henüz mesaj yok</div>
            @endforelse
        </div>
    </div>

    {{-- Son Whatsapp Mesajları --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800">Son Whatsapp Mesajları(0)</h3>
        </div>
        <div class="px-5 py-6 text-center text-sm text-gray-400">Henüz WhatsApp mesajı yok</div>
    </div>
</div>
