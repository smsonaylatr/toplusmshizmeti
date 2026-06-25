<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Alt Kullanıcı İşlemleri</h1>
    <div class="mb-4"><h2 class="text-base font-semibold text-[#2563eb] border-b-2 border-[#2563eb] inline-block pb-1">Alt Kullanıcı Yönetimi</h2></div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Sol: Hızlı Oluştur --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-5 py-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800">Hızlı Oluştur</h3>
            </div>
            @if(session('success'))
                <div class="mx-5 mt-3 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">{{ session('success') }}</div>
            @endif
            <form wire:submit="create" class="p-5 space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div class="relative">
                        <input wire:model="name" type="text" placeholder="Adı" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Adı</label>
                    </div>
                    <div class="relative">
                        <input wire:model="surname" type="text" placeholder="Soyadı" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Soyadı</label>
                    </div>
                </div>
                <div class="relative">
                    <input wire:model="username" type="text" placeholder="Kullanıcı Adı" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Kullanıcı Adı</label>
                </div>
                <div class="relative">
                    <input wire:model="password" type="password" placeholder="Şifre" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Şifre</label>
                </div>
                <div class="relative">
                    <input wire:model="phone" type="text" placeholder="5xxxxxxxxx" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Telefon Numarası</label>
                </div>

                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-[#2563eb] text-white text-xs font-medium rounded hover:bg-[#1d4ed8] transition-colors">SMS KODU AL</button>
                    <div class="relative flex-1">
                        <input wire:model="verificationCode" type="text" placeholder="Doğrulama Kodu" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="relative">
                        <input wire:model.number="smsLimit" type="number" placeholder="0" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Kredi Limiti</label>
                    </div>
                    <div class="relative">
                        <input wire:model.number="whatsappLimit" type="number" placeholder="0" class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                        <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] text-gray-400">Whatsapp Kredi Limiti</label>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded p-3 text-[11px] text-blue-600">
                    <strong>Not:</strong> Alt kullanıcı kredileri gönderim esnasında ana hesap bakiyesi üzerinden kontrol edilir.
                </div>

                <button type="submit" class="w-full py-3 bg-[#2563eb] text-white text-sm font-bold rounded hover:bg-[#1d4ed8] transition-all duration-200 flex items-center justify-center gap-2 tracking-wider shadow-md hover:shadow-lg">
                    OLUŞTUR
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                </button>
            </form>
        </div>

        {{-- Sağ: Alt Kullanıcı Listesi --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-5 py-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800">Alt Kullanıcılar</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Ad Soyad</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Kullanıcı Adı</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Kredi</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Durum</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subUsers as $subUser)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700">{{ $subUser->name }} {{ $subUser->surname }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $subUser->username }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $subUser->sms_limit }} SMS / {{ $subUser->whatsapp_limit }} WA</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $subUser->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $subUser->is_active ? 'Aktif' : 'Pasif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 flex gap-1">
                                <button wire:click="toggleStatus({{ $subUser->id }})" class="px-2 py-1 text-xs rounded {{ $subUser->is_active ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                                    {{ $subUser->is_active ? 'Pasif Yap' : 'Aktif Yap' }}
                                </button>
                                <button wire:click="deleteSubUser({{ $subUser->id }})" wire:confirm="Bu alt kullanıcıyı silmek istediğinize emin misiniz?" class="px-2 py-1 text-xs rounded bg-red-50 text-red-600 hover:bg-red-100">Sil</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-400">Bu görünümde veri yok</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
