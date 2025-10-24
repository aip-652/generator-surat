<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Buat Dokumen Surat Keluar') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 md:p-8 bg-white border-b border-gray-200">
          <div class="flex items-center mb-8">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full hover:bg-gray-300 transition duration-150">
              <i class="fas fa-arrow-left text-gray-700"></i>
            </a>

            <h1 class="text-xl font-bold text-gray-800 ml-7">
              Surat Eksternal
            </h1>

            <div class="w-10 h-10"></div>
          </div>

          @if (session('success'))
          @php
          $message = session('success');
          preg_match('/nomor:\s*(.+)$/i', $message, $matches);
          $nomorSurat = $matches[1] ?? null;
          @endphp

          <div class="mb-6 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded flex items-center flex-wrap gap-2">
            <span>
              Memo Internal berhasil dibuat dengan nomor:
              <strong>{{ $nomorSurat }}</strong>
            </span>

            @if ($nomorSurat)
            <button
              type="button"
              onclick="copyNomorSurat('{{ addslashes($nomorSurat) }}')"
              class="ml-2 inline-flex items-center gap-1 bg-green-600 text-white px-2 py-1 text-sm rounded hover:bg-green-700 transition">
              <i class="fas fa-copy text-xs"></i>
              <span>Copy</span>
            </button>
            @endif
          </div>
          @endif


          @if ($errors->any())
          <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <strong class="font-bold">Oops! Terjadi kesalahan.</strong>
            <ul class="mt-2 list-disc list-inside">
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <form action="{{ route('dokumen.store.surat') }}" method="POST" class="space-y-6">
            @csrf

            <div>
              <x-input-label for="kode_surat" :value="__('Jenis Surat')" />
              <x-select-input id="kode_surat" name="kode_surat" class="block mt-1 w-full" required>
                <option value="" disabled selected>-- Pilih Jenis Surat --</option>
                @foreach($kodeSurat as $kode)
                <option value="{{ $kode }}" {{ old('kode_surat') == $kode ? 'selected' : '' }}>
                  {{ $kode }}
                </option>
                @endforeach
              </x-select-input>
              <x-input-error :messages="$errors->get('kode_surat')" class="mt-2" />
            </div>

	    <div id="tanggal-container" class="hidden">
	      <x-input-label for="tanggal_manual" :value="__('Tanggal Surat (opsional)')" />
	      <x-text-input id="tanggal_manual" class="block mt-1 w-full" type="date" name="tanggal_manual" :value="old('tanggal_manual')" />
	      <x-input-error :messages="$errors->get('tanggal_manual')" class="mt-2" />
	    </div>

            <div>
              <x-input-label for="perihal" :value="__('Perihal')" />
              <x-text-input id="perihal" class="block mt-1 w-full" type="text" name="perihal" :value="old('perihal')" required />
              <x-input-error :messages="$errors->get('perihal')" class="mt-2" />
            </div>

            <div>
              <x-input-label for="kepada" :value="__('Kepada')" />
              <x-text-input id="kepada" class="block mt-1 w-full" type="text" name="kepada" :value="old('kepada')" />
              <x-input-error :messages="$errors->get('kepada')" class="mt-2" />
            </div>

            <div>
              <x-input-label for="alamat" :value="__('Alamat (optional)')" />
              <x-textarea-input id="alamat" name="alamat" class="block mt-1 w-full" rows="3" >{{ old('alamat') }}</x-textarea-input>
              <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            <div>
              <x-input-label for="order" :value="__('Order')" />
              <x-text-input id="order" class="block mt-1 w-full" type="text" name="order" :value="old('order')"/>
              <x-input-error :messages="$errors->get('order')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end">
              <x-primary-button>
                {{ __('Buat Surat') }}
              </x-primary-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Script untuk copy nomor surat --}}
  <script>

  document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('kode_surat');
    const tanggalContainer = document.getElementById('tanggal-container');

    function toggleTanggal() {
      const selected = select.value;
      if (selected === 'Perjanjian' || selected === 'Surat Perintah Kerja') {
        tanggalContainer.classList.remove('hidden');
      } else {
        tanggalContainer.classList.add('hidden');
      }
    }

    select.addEventListener('change', toggleTanggal);
    toggleTanggal(); // cek saat load awal
  });

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


  {{-- Animasi kecil untuk toast --}}
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
