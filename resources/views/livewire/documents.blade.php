<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Evrak İşlemleri</h1>
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-base font-semibold text-[#2563eb] border-b-2 border-[#2563eb] inline-block pb-1">Evrak İşlemleri</h2>
        <button class="px-4 py-2 bg-[#2563eb] text-white text-xs font-bold rounded hover:bg-[#1d4ed8] transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            ÖRNEK EVRAĞI İNDİR
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @php
            $docTypes = [
                ['type' => 'contract', 'label' => 'Sözleşme', 'desc' => 'Tüm sayfalar kaşelenmiş ve imzalı', 'color' => 'blue', 'property' => 'contractFile', 'method' => 'uploadContract'],
                ['type' => 'identity', 'label' => 'Kimlik', 'desc' => 'Sadece resmi kimlik, ehliyet geçersiz', 'color' => 'green', 'property' => 'identityFile', 'method' => 'uploadIdentity'],
                ['type' => 'residence', 'label' => 'İkametgah', 'desc' => 'E-devlet PDF formatında olmalı', 'color' => 'purple', 'property' => 'residenceFile', 'method' => 'uploadResidence'],
                ['type' => 'tax_plate', 'label' => 'Vergi Levhası', 'desc' => 'Güncel vergi levhası', 'color' => 'amber', 'property' => 'taxPlateFile', 'method' => 'uploadTaxPlate'],
                ['type' => 'activity_certificate', 'label' => 'Faaliyet Belgesi', 'desc' => 'Ticaret odası faaliyet belgesi', 'color' => 'red', 'property' => 'activityFile', 'method' => 'uploadActivity'],
                ['type' => 'signature_circular', 'label' => 'İmza Sirküsü', 'desc' => 'Noter onaylı imza sirküsü', 'color' => 'indigo', 'property' => 'signatureFile', 'method' => 'uploadSignature'],
            ];
        @endphp

        @foreach($docTypes as $doc)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-{{ $doc['color'] }}-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-{{ $doc['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800">{{ $doc['label'] }}</h3>
                    <p class="text-[11px] text-gray-400">{{ $doc['desc'] }}</p>
                </div>
            </div>

            @if(isset($documents[$doc['type']]))
                <div class="flex items-center gap-2 mb-2 p-2 bg-gray-50 rounded">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-xs text-gray-600 truncate">{{ $documents[$doc['type']]->original_name }}</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium ml-auto
                        {{ $documents[$doc['type']]->status === 'approved' ? 'bg-green-100 text-green-700' : ($documents[$doc['type']]->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                        {{ $documents[$doc['type']]->status_label }}
                    </span>
                </div>
            @endif

            <form wire:submit="{{ $doc['method'] }}">
                <input wire:model="{{ $doc['property'] }}" type="file" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-{{ $doc['color'] }}-50 file:text-{{ $doc['color'] }}-700 hover:file:bg-{{ $doc['color'] }}-100">
                @error($doc['property']) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                <div wire:loading wire:target="{{ $doc['property'] }}" class="mt-2 text-xs text-blue-600">Yükleniyor...</div>
                @if($this->{$doc['property']})
                    <button type="submit" class="mt-2 w-full px-3 py-1.5 bg-{{ $doc['color'] }}-500 text-white text-xs font-bold rounded hover:bg-{{ $doc['color'] }}-600">YÜKLE</button>
                @endif
            </form>
            <p class="text-[10px] text-gray-400 mt-2">PDF, JPG veya PNG formatında yükleyin</p>
        </div>
        @endforeach
    </div>
</div>
