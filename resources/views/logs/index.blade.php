<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Logs') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-full mx-auto sm:px-12 lg:px-26">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white">

          <div class="flex items-center mb-6">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full hover:bg-gray-300 transition duration-150">
              <i class="fas fa-arrow-left text-gray-700"></i>
            </a>
            <h3 class="text-2xl font-bold text-gray-800 ml-4">
              Log Aktivitas Admin
            </h3>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
              <thead class="bg-gray-100">
                <tr>
                  @php
                  $sortLink = fn($orderByField) => route('logs.index', array_merge(request()->query(), ['order_by' => $orderByField, 'sort' => request('sort') === 'asc' ? 'desc' : 'asc']));
                  @endphp

                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    <a href="{{ $sortLink('created_at') }}" class="hover:underline">Waktu</a>
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    <a href="{{ $sortLink('user_id') }}" class="hover:underline">Admin</a>
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    <a href="{{ $sortLink('action') }}" class="hover:underline">Aksi</a>
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Detail
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                @forelse ($logs as $log)
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}</td>
                  <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $log->user->name ?? 'User Dihapus' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    {{ $log->action == 'deleted' ? 'bg-red-100 text-red-800' : ($log->action == 'updated' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                      {{ ucfirst($log->action) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-600">{{ $log->details }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-center py-12 text-gray-500">
                    <i class="fas fa-history fa-3x mb-4"></i>
                    <p class="text-lg">Tidak ada aktivitas log yang tercatat.</p>
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="mt-6">
            {{ $logs->withQueryString()->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>