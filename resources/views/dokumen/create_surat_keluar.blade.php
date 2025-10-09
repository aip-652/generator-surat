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
              <x-input-label for="pic" :value="__('PIC')" />
              <x-text-input id="pic" class="block mt-1 w-full" type="text" name="pic" :value="old('pic')" required placeholder="Nama penanggung jawab" />
              <x-input-error :messages="$errors->get('pic')" class="mt-2" />
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