<x-layouts.auth title="Kayıt">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-[#2563eb] shadow-lg shadow-[#2563eb]/30 mb-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-800">TOPLUSMSHİZMETİ</h1>
            <p class="text-gray-400 mt-1 text-sm">Yeni hesap oluşturun</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sm:p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Ad Soyad</label>
                    <input type="text" name="name" placeholder="Adınız Soyadınız" value="{{ old('name') }}" required autofocus
                           class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">E-posta Adresi</label>
                    <input type="email" name="email" placeholder="ornek@email.com" value="{{ old('email') }}" required
                           class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Şifre</label>
                    <input type="password" name="password" placeholder="••••••••" required
                           class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    <p class="mt-1 text-[10px] text-gray-400">En az 8 karakter</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Şifre Tekrar</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••" required
                           class="w-full px-3 py-2.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#2563eb]">
                </div>

                <button type="submit" class="w-full px-4 py-2.5 bg-[#2563eb] text-white text-sm font-medium rounded hover:bg-[#1d4ed8] transition-colors">
                    Kayıt Ol
                </button>
            </form>
        </div>

        <p class="text-center mt-5 text-sm text-gray-400">
            Zaten hesabınız var mı?
            <a href="{{ route('login') }}" class="text-[#2563eb] hover:text-[#1d4ed8] font-medium">Giriş yapın</a>
        </p>
    </div>
</x-layouts.auth>
