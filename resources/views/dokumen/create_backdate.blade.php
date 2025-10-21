<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Buat Dokumen Backdate') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 md:p-8 bg-white border-b border-gray-200">

          {{-- Judul + tombol kembali --}}
          <div class="flex items-center mb-8">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full hover:bg-gray-300 transition duration-150">
              <i class="fas fa-arrow-left text-gray-700"></i>
            </a>

            <h1 class="text-xl font-bold text-gray-800 ml-4">
              Dokumen Backdate
            </h1>
          </div>

          {{-- Alert sukses jika ada --}}
          @if (session('success'))
          @php
          $message = session('success');
          preg_match('/nomor:\s*(.+)$/i', $message, $matches);
          $nomorSurat = $matches[1] ?? null;
          @endphp

          <div class="mb-6 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded flex items-center flex-wrap gap-2">
            <span>
              {{ $message }}
            </span>

            @if ($nomorSurat)
            <button
              type="button"
              onclick="copyNomorSurat('{{ addslashes($nomorSurat) }}')"
              class="ml-2 inline-flex items-center gap-1 bg-green-600 text-white px-2 py-1 text-sm rounded hover:bg-green-700 transition">
              <i class="fas fa-copy text-xs"></i>
              <span>Salin</span>
            </button>
            @endif
          </div>
          @endif

          {{-- Form utama --}}
          <form
            x-data="{ selectedType: '{{ old('jenis_dokumen', '') }}' }"
            action="{{ route('dokumen.store.backdate') }}"
            method="POST"
            class="space-y-6">
            @csrf

            <div>
              <x-input-label for="tanggal_backdate" :value="__('Tanggal Dokumen (Backdate)')" />
              <x-text-input id="tanggal_backdate" class="block mt-1 w-full" type="date" name="tanggal_backdate" :value="old('tanggal_backdate')" max="{{ \Carbon\Carbon::yesterday()->toDateString() }}" required />
              <x-input-error :messages="$errors->get('tanggal_backdate')" class="mt-2" />
            </div>

            <div>
              <x-input-label for="jenis_dokumen" :value="__('Jenis Dokumen')" />
              <x-select-input
                id="jenis_dokumen"
                name="jenis_dokumen"
                class="block mt-1 w-full"
                x-model="selectedType"
                required>
                <option value="" disabled selected>-- Pilih Jenis --</option>
                <option value="memo_internal">Memo Internal</option>
                <option value="surat_keluar">Surat Keluar</option>
              </x-select-input>
              <x-input-error :messages="$errors->get('jenis_dokumen')" class="mt-2" />
            </div>

            <div x-show="selectedType" style="display: none;">
              <x-input-label for="kode_spesifik">
                <span x-text="selectedType === 'memo_internal' ? 'Unit Kerja' : 'Jenis Surat'"></span>
              </x-input-label>
              <x-select-input id="kode_spesifik" name="kode_spesifik" class="block mt-1 w-full" required>
                <option value="" disabled selected>-- Pilih Opsi --</option>

                <template x-if="selectedType === 'memo_internal'">
                  <optgroup label="Unit Kerja">
                    @foreach($unitKerja as $unit)
                    <option value="{{ $unit }}" {{ old('kode_spesifik') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                    @endforeach
                  </optgroup>
                </template>

                <template x-if="selectedType === 'surat_keluar'">
                  <optgroup label="Jenis Surat">
                    @foreach($kodeSurat as $kode)
                    <option value="{{ $kode }}" {{ old('kode_spesifik') == $kode ? 'selected' : '' }}>{{ $kode }}</option>
                    @endforeach
                  </optgroup>
                </template>
              </x-select-input>
              <x-input-error :messages="$errors->get('kode_spesifik')" class="mt-2" />
            </div>

            <div x-show="selectedType" style="display: none;">
              <x-input-label for="perihal" :value="__('Perihal')" />
              <x-text-input id="perihal" class="block mt-1 w-full" type="text" name="perihal" :value="old('perihal')" required />
            </div>

            <div x-show="selectedType" style="display: none;">
              <x-input-label for="kepada" :value="__('Kepada')" />
              <x-text-input id="kepada" class="block mt-1 w-full" type="text" name="kepada" :value="old('kepada')" />
            </div>

            <div x-show="selectedType" style="display: none;">
              <x-input-label for="order" :value="__('Order')" />
              <x-text-input id="order" class="block mt-1 w-full" type="text" name="order" :value="old('order')" />
            </div>

            <div class="flex items-center justify-end" x-show="selectedType" style="display: none;">
              <x-primary-button>
                {{ __('Buat Dokumen Backdate') }}
              </x-primary-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Script untuk copy nomor surat --}}
  <script>
function copyNomorSurat(nomor) {
  // Buat elemen input sementara
  const tempInput = document.createElement('input');
  tempInput.value = nomor;
  document.body.appendChild(tempInput);
  tempInput.select();
  tempInput.setSelectionRange(0, 99999); // Untuk mobile

  try {
    const successful = document.execCommand('copy');
    if (successful) {
      const toast = document.createElement('div');
      toast.textContent = '✅ Nomor surat disalin!';
      toast.className = 'fixed bottom-5 right-5 bg-green-600 text-white px-4 py-2 rounded shadow';
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 2000);
    } else {
      alert('Gagal menyalin nomor surat.');
    }
  } catch (err) {
    console.error('Gagal menyalin:', err);
    alert('Gagal menyalin nomor surat.');
  }

  document.body.removeChild(tempInput);
}
</script>


  <style>
    @keyframes fade-in {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-fade-in {
      animation: fade-in 0.2s ease-out;
    }
  </style>
</x-app-layout>
