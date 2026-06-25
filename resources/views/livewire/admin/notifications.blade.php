<div>
    <div class="mb-6"><h1 class="text-2xl font-bold text-white tracking-tight">Bildirim Gönder</h1><p class="text-sm text-gray-500 mt-0.5">Kullanıcılara toplu veya tekil bildirim</p></div>
    @if(session('success'))<div class="flash-success mb-4">{{ session('success') }}</div>@endif
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="glass-card p-5">
            <h3 class="text-sm font-semibold text-white flex items-center gap-2 mb-4"><span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: rgba(234,179,8,.1);"><svg class="w-3.5 h-3.5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg></span>Yeni Bildirim</h3>
            <form wire:submit="send" class="space-y-3">
                <div><label class="text-[12px] text-gray-500 mb-1 block">Hedef</label><select wire:model="targetUser" class="admin-select w-full"><option value="">🌐 Tüm Kullanıcılar</option>@foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>@endforeach</select></div>
                <div><label class="text-[12px] text-gray-500 mb-1 block">Başlık</label><input wire:model="title" type="text" class="admin-input">@error('title')<span class="text-red-400 text-[11px]">{{ $message }}</span>@enderror</div>
                <div><label class="text-[12px] text-gray-500 mb-1 block">Mesaj</label><textarea wire:model="message" rows="4" class="admin-input"></textarea>@error('message')<span class="text-red-400 text-[11px]">{{ $message }}</span>@enderror</div>
                <div><label class="text-[12px] text-gray-500 mb-1 block">Tür</label><select wire:model="type" class="admin-select w-full"><option value="info">ℹ️ Bilgi</option><option value="success">✅ Başarılı</option><option value="warning">⚠️ Uyarı</option><option value="error">❌ Hata</option></select></div>
                <button type="submit" class="btn-primary w-full"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>Gönder</button>
            </form>
        </div>
        <div class="glass-card overflow-hidden">
            <div class="px-5 py-3.5" style="border-bottom: 1px solid var(--admin-border);"><h3 class="text-sm font-semibold text-white">Son Bildirimler</h3></div>
            <div class="max-h-[450px] overflow-y-auto divide-y" style="--tw-divide-color: rgba(99,102,241,.06);">
                @forelse($recentNotifications as $n)
                <div class="px-5 py-3"><div class="flex justify-between"><span class="text-[13px] font-medium text-white">{{ $n->title }}</span><span class="text-[10px] text-gray-600">{{ $n->created_at->diffForHumans() }}</span></div><p class="text-[12px] text-gray-500 mt-0.5">{{ Str::limit($n->message, 80) }}</p><p class="text-[10px] text-indigo-400/50 mt-0.5">→ {{ $n->user?->name }}</p></div>
                @empty<div class="text-center py-10"><p class="text-sm text-gray-600">Henüz bildirim yok</p></div>@endforelse
            </div>
        </div>
    </div>
</div>
