<x-app-layout>
  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 md:p-8 bg-white border-b border-gray-200">

          <div class="flex items-center mb-8">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full hover:bg-gray-300">
              <i class="fas fa-arrow-left text-gray-700"></i>
            </a>
            <h2 class="text-xl font-bold text-gray-800 ml-4">
              Edit Dokumen: {{ $dokumen->nomor_dokumen }}
            </h2>
          </div>

          <form action="{{ route('dokumen.update', $dokumen) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
              <x-input-label for="perihal" :value="__('Perihal')" />
              <x-text-input id="perihal" class="block mt-1 w-full" type="text" name="perihal" :value="old('perihal', $dokumen->perihal)" required />
            </div>

            <div>
              <x-input-label for="kepada" :value="__('Kepada')" />
              <x-text-input id="kepada" class="block mt-1 w-full" type="text" name="kepada" :value="old('kepada', $dokumen->kepada)" />
            </div>

            <div>
              <x-input-label for="order" :value="__('Order')" />
              <x-text-input id="order" class="block mt-1 w-full" type="text" name="order" :value="old('order', $dokumen->order)"/>
            </div>

            <div class="flex items-center justify-end">
              <x-primary-button>
                {{ __('Simpan Perubahan') }}
              </x-primary-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
