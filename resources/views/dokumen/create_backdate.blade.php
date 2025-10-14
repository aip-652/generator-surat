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

          <div class="flex items-center mb-8">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full hover:bg-gray-300 transition duration-150">
              <i class="fas fa-arrow-left text-gray-700"></i>
            </a>

            <h1 class="text-xl font-bold text-gray-800 ml-4">
              Dokumen Backdate
            </h1>
          </div>

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
              <x-input-label for="pic" :value="__('PIC')" />
              <x-text-input id="pic" class="block mt-1 w-full" type="text" name="pic" :value="old('pic')" required />
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
</x-app-layout>