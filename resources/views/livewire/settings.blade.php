<div class="max-w-2xl space-y-5">
    {{-- Profile --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800">Profil Bilgileri</h3>
            <p class="text-[11px] text-gray-400 mt-0.5">SMS hizmeti için gerekli tüm bilgilerinizi doldurun</p>
        </div>
        <div class="p-5">
            @if(session('profile_success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">{{ session('profile_success') }}</div>
            @endif
            <form wire:submit="updateProfile" class="space-y-5">

                {{-- ── Hesap Türü ── --}}
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Hesap Türü</label>
                    <div class="flex rounded-lg overflow-hidden border border-gray-300">
                        <button type="button" wire:click="$set('accountType','individual')"
                                class="flex-1 py-2 text-sm font-semibold transition-colors {{ $accountType === 'individual' ? 'bg-[#2563eb] text-white' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                            👤 Bireysel
                        </button>
                        <button type="button" wire:click="$set('accountType','corporate')"
                                class="flex-1 py-2 text-sm font-semibold transition-colors border-l border-gray-300 {{ $accountType === 'corporate' ? 'bg-[#2563eb] text-white' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                            🏢 Kurumsal
                        </button>
                    </div>
                </div>

                {{-- ── İletişim Bilgileri ── --}}
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 border-b border-gray-100 pb-1">İletişim Bilgileri</p>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Ad Soyad *</label>
                            <input wire:model="name" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">E-posta *</label>
                            <input wire:model="email" type="email" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                            @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Telefon</label>
                            <input wire:model="phone" type="text" placeholder="5xxxxxxxxx" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                            @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        @if($accountType === 'corporate')
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Yetkili Kişi</label>
                            <input wire:model="contactPerson" type="text" placeholder="Ad Soyad" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ── Kimlik / Vergi ── --}}
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 border-b border-gray-100 pb-1">
                        {{ $accountType === 'corporate' ? 'Vergi Bilgileri' : 'Kimlik Bilgileri' }}
                    </p>
                    <div class="space-y-3">
                        @if($accountType === 'corporate')
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Şirket / Kurum Adı *</label>
                            <input wire:model="companyName" type="text" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                            @error('companyName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Vergi No</label>
                                <input wire:model="taxNo" type="text" maxlength="11" placeholder="10 hane" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                                @error('taxNo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Vergi Dairesi</label>
                                <input wire:model="taxOffice" type="text" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">MERSİS No</label>
                            <input wire:model="mersisNo" type="text" placeholder="16 hane" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                        </div>
                        @else
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">TC Kimlik No</label>
                            <input wire:model="tcNo" type="text" maxlength="11" placeholder="11 hane" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                            @error('tcNo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ── Adres Bilgileri ── --}}
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 border-b border-gray-100 pb-1">Adres Bilgileri</p>
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">İl</label>
                                <input wire:model="city" type="text" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">İlçe</label>
                                <input wire:model="district" type="text" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Açık Adres</label>
                            <textarea wire:model="address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb] resize-y"></textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-[#2563eb] text-white text-sm font-bold rounded hover:bg-[#1d4ed8] transition-all duration-200 flex items-center justify-center gap-2 tracking-wider shadow-md hover:shadow-lg">
                    BİLGİLERİMİ GÜNCELLE
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                </button>
            </form>
        </div>
    </div>


    {{-- Password --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800">Şifre Değiştir</h3>
            <p class="text-[11px] text-gray-400 mt-0.5">Hesap güvenliğiniz için güçlü bir şifre kullanın</p>
        </div>
        <div class="p-5">
            @if(session('password_success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">{{ session('password_success') }}</div>
            @endif
            <form wire:submit="updatePassword" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Mevcut Şifre</label>
                    <input wire:model="currentPassword" type="password" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    @error('currentPassword') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Yeni Şifre</label>
                    <input wire:model="newPassword" type="password" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    @error('newPassword') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    <p class="mt-1 text-[10px] text-gray-400">En az 8 karakter</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Yeni Şifre (Tekrar)</label>
                    <input wire:model="newPasswordConfirmation" type="password" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    @error('newPasswordConfirmation') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="w-full py-3 bg-[#2563eb] text-white text-sm font-bold rounded hover:bg-[#1d4ed8] transition-all duration-200 flex items-center justify-center gap-2 tracking-wider shadow-md hover:shadow-lg">ŞİFREYİ GÜNCELLE</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Evrak İşlemleri --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800">Evrak İşlemleri</h3>
            <p class="text-[11px] text-gray-400 mt-0.5">Firma belgelerinizi yükleyin</p>
        </div>
        <div class="p-5">
            <div class="text-center py-4">
                <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p class="text-gray-400 text-sm">Henüz belge yüklenmedi</p>
                <button class="mt-3 w-full py-2.5 bg-[#2563eb] text-white text-xs font-bold rounded hover:bg-[#1d4ed8] transition-all tracking-wider shadow-md hover:shadow-lg">BELGE YÜKLE</button>
            </div>
        </div>
    </div>
</div>
