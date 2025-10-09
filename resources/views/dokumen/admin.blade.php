<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Admin Dashboard: Daftar Dokumen') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">

          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">
              Semua Dokumen Terdaftar
            </h3>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
              <i class="fas fa-plus mr-2"></i> Buat Dokumen Baru
            </a>
          </div>

          <form action="{{ route('admin.dashboard') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
            <div class="sm:col-span-1">
              <x-input-label for="jenis" :value="__('Filter Jenis')" />
              <x-select-input id="jenis" name="jenis" class="block mt-1 w-full" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="memo_internal" {{ request('jenis') == 'memo_internal' ? 'selected' : '' }}>Memo Internal</option>
                <option value="surat_keluar" {{ request('jenis') == 'surat_keluar' ? 'selected' : '' }}>Surat Keluar</option>
              </x-select-input>
            </div>
            <div class="sm:col-span-3">
              <x-input-label for="search" :value="__('Cari Dokumen')" />
              <div class="flex mt-1">
                <x-text-input id="search" class="block w-full rounded-r-none" type="text" name="search" :value="request('search')" placeholder="Cari perihal atau nomor surat..." />
                <x-primary-button class="rounded-l-none">
                  {{ __('Cari') }}
                </x-primary-button>
              </div>
            </div>
          </form>

          <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
              <thead class="bg-gray-100">
                <tr>
                  @php
                  // Helper function to build sorting URL
                  $sortLink = fn($orderByField) => route('admin.dashboard', array_merge(request()->query(), ['order_by' => $orderByField, 'sort' => request('sort') === 'asc' ? 'desc' : 'asc']));
                  @endphp
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><a href="{{ $sortLink('jenis_dokumen') }}" class="hover:underline">Jenis</a></th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><a href="{{ $sortLink('nomor_dokumen') }}" class="hover:underline">Nomor Surat</a></th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><a href="{{ $sortLink('tanggal') }}" class="hover:underline">Tanggal</a></th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><a href="{{ $sortLink('perihal') }}" class="hover:underline">Perihal</a></th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><a href="{{ $sortLink('kepada') }}" class="hover:underline">Kepada</a></th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><a href="{{ $sortLink('email_requestor') }}" class="hover:underline">Requestor</a></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                @forelse ($dokumens as $dokumen)
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    @if($dokumen->jenis_dokumen == 'memo_internal')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                      Memo Internal
                    </span>
                    @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                      Surat Keluar
                    </span>
                    @endif
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $dokumen->nomor_dokumen }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($dokumen->tanggal)->format('d/m/Y') }}</td>
                  <td class="px-6 py-4">{{ $dokumen->perihal }}</td>
                  <td class="px-6 py-4">{{ $dokumen->kepada }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ $dokumen->email_requestor }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-folder-open fa-3x mb-4"></i>
                    <p class="text-lg">Tidak ada data dokumen yang ditemukan.</p>
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="mt-6">
            {{ $dokumens->withQueryString()->links() }}
          </div>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>