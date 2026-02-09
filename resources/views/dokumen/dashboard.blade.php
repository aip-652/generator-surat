<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-full mx-auto sm:px-12 lg:px-26">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">

          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">
              Semua Dokumen Terdaftar
            </h3>
            <div class="flex items-center space-x-4">

              @if(Auth::user()->role === 'admin')
              <a href="{{ route('logs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-gray-700">
                <i class="fas fa-history mr-2"></i>
                Log Aktivitas
              </a>

              <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-gray-700">
                <i class="fas fa-users-cog mr-2"></i>
                Manage Users
              </a>
              @endif

              <div x-data="{ open: false }" class="relative inline-block text-left">
                <button @click="open = !open" type="button" class="inline-flex items-center justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500 transition ease-in-out duration-150" id="menu-button">
                  Buat Dokumen Baru
                  <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>

                <div
                  x-show="open"
                  @click.away="open = false"
                  x-transition:enter="transition ease-out duration-100"
                  x-transition:enter-start="transform opacity-0 scale-95"
                  x-transition:enter-end="transform opacity-100 scale-100"
                  x-transition:leave="transition ease-in duration-75"
                  x-transition:leave-start="transform opacity-100 scale-100"
                  x-transition:leave-end="transform opacity-0 scale-95"
                  class="origin-top-right absolute right-0 mt-2 w-full rounded-md shadow-lg bg-indigo-50 ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                  role="menu"
                  style="display: none;">
                  <div class="py-1" role="none">
                    <a href="{{ route('dokumen.create.memo') }}" class="flex items-center px-4 py-2 text-sm text-indigo-800 hover:bg-indigo-100" role="menuitem">
                      <i class="fas fa-file-alt fa-fw mr-2 text-indigo-400"></i>
                      <span>Buat Memo Internal</span>
                    </a>
                    <a href="{{ route('dokumen.create.surat') }}" class="flex items-center px-4 py-2 text-sm text-indigo-800 hover:bg-indigo-100" role="menuitem">
                      <i class="fas fa-envelope fa-fw mr-2 text-indigo-400"></i>
                      <span>Buat Surat Keluar</span>
                    </a>
                    @if(in_array(Auth::user()->role, ['admin', 'special']))
                    <a href="{{ route('dokumen.create.backdate') }}" class="flex items-center px-4 py-2 text-sm text-indigo-800 hover:bg-indigo-100 border-t border-indigo-200" role="menuitem">
                      <i class="fas fa-calendar-day fa-fw mr-3 text-indigo-400"></i>
                      <span>Dokumen Backdate</span>
                    </a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>

          <form action="{{ route('dashboard') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
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
                <x-primary-button class="rounded-l-none px-4">
                  <i class="fas fa-search"></i>
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
                  $sortLink = fn($orderByField) => route('dashboard', array_merge(request()->query(), ['order_by' => $orderByField, 'sort' => request('sort') === 'asc' ? 'desc' : 'asc']));
                  @endphp
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><a href="{{ $sortLink('jenis_dokumen') }}" class="hover:underline">Jenis</a></th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><a href="{{ $sortLink('nomor_dokumen') }}" class="hover:underline">Nomor Surat</a></th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><a href="{{ $sortLink('created_at') }}" class="hover:underline">Dibuat pada</a></th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><a href="{{ $sortLink('perihal') }}" class="hover:underline">Perihal</a></th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><a href="{{ $sortLink('kepada') }}" class="hover:underline">Kepada</a></th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><a href="{{ $sortLink('pic') }}" class="hover:underline">Order</a></th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><a href="{{ $sortLink('email_requestor') }}" class="hover:underline">PIC</a></th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
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
                  <td class="px-6 py-4 whitespace-nowrap">{{ optional(\Carbon\Carbon::parse($dokumen->created_at))->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') ?? '-' }}</td>
                  <td class="px-6 py-4">{{ $dokumen->perihal }}</td>
                  <td class="px-6 py-4">{{ $dokumen->tujuan }}</td>
                  <td class="px-6 py-4">{{ $dokumen->order }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ $dokumen->pic }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-2">
                    
                    {{-- Download PDF --}}
                    <a href="{{ route('dokumen.pdf', $dokumen->id) }}?preview=1" target="_blank"
                      class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                      PDF
                    </a>
                    
                    @if(Auth::user()->role === 'admin')
                      <a href="{{ route('dokumen.edit', $dokumen) }}" class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Edit
                      </a>

                      <form action="{{ route('dokumen.destroy', $dokumen) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                          Delete
                        </button>
                      </form>
                    @endif
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="12" class="px-6 py-12 text-center text-gray-500">
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
