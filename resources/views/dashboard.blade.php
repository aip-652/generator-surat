<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 md:p-12 text-center">

          <!-- <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Pilih salah satu menu di bawah ini untuk melanjutkan.
          </h1>
          <p class="text-lg text-gray-600 mb-8">
            Pilih salah satu menu di bawah ini untuk melanjutkan.
          </p> -->

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <a href="{{ route('dokumen.create.memo') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 transition duration-300">
              <h3 class="text-xl font-bold mb-2 flex items-center justify-center">
                <i class="fas fa-file-alt mr-2"></i> Memo Internal
              </h3>
              <p class="text-gray-700">
                Buat memo baru untuk keperluan internal.
              </p>
            </a>

            <a href="{{ route('dokumen.create.surat') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 transition duration-300">
              <h3 class="text-xl font-bold mb-2 flex items-center justify-center">
                <i class="fas fa-envelope mr-2"></i> Surat Keluar
              </h3>
              <p class="text-gray-700">
                Buat surat resmi untuk dikirim keluar.
              </p>
            </a>

            <a href="{{ route('admin.dashboard') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 transition duration-300">
              <h3 class="text-xl font-bold mb-2 flex items-center justify-center">
                <i class="fas fa-user-shield mr-2"></i> Admin Dashboard
              </h3>
              <p class="text-gray-700">
                Lihat dan kelola semua dokumen.
              </p>
            </a>

          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>