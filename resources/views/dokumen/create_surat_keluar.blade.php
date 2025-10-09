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

          <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mb-6">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
          </a>

          @if (session('success'))
          <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
            {{ session('success') }}
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

            <div>
              <x-input-label for="perihal" :value="__('Perihal')" />
              <x-text-input id="perihal" class="block mt-1 w-full" type="text" name="perihal" :value="old('perihal')" required placeholder="Contoh: Undangan Rapat" />
              <x-input-error :messages="$errors->get('perihal')" class="mt-2" />
            </div>

            <div>
              <x-input-label for="kepada" :value="__('Kepada')" />
              <x-text-input id="kepada" class="block mt-1 w-full" type="text" name="kepada" :value="old('kepada')" placeholder="Contoh: Bapak/Ibu [Nama Penerima]" />
              <x-input-error :messages="$errors->get('kepada')" class="mt-2" />
            </div>

            <div>
              <x-input-label for="alamat" :value="__('Alamat (optional)')" />
              <x-textarea-input id="alamat" name="alamat" class="block mt-1 w-full" rows="3" placeholder="Contoh: Jl. Merdeka No. 10, Jakarta">{{ old('alamat') }}</x-textarea-input>
              <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            <div>
              <x-input-label for="email_requestor" :value="__('Email Requestor')" />
              <x-text-input id="email_requestor" class="block mt-1 w-full" type="email" name="email_requestor" :value="old('email_requestor')" required placeholder="email.anda@perusahaan.com" />
              <x-input-error :messages="$errors->get('email_requestor')" class="mt-2" />
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
</x-app-layout>