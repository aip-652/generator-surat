<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Manajemen Pengguna') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          @if (session('success'))
          <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
            {{ session('success') }}
          </div>
          @endif
          @if (session('error'))
          <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-800 rounded" role="alert">
            {{ session('error') }}
          </div>
          @endif

          <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
              <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full hover:bg-gray-300 transition duration-150">
                <i class="fas fa-arrow-left text-gray-700"></i>
              </a>
              <h3 class="text-2xl font-bold text-gray-800 ml-4">
                Daftar Pengguna Terdaftar
              </h3>
            </div>

            <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-indigo-700">
              <i class="fas fa-plus mr-2"></i> Tambah Pengguna
            </a>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
              <thead class="bg-gray-100">
                <tr>
                  @php
                  // Helper untuk membuat URL sorting yang mempertahankan filter lain
                  $sortLink = fn($orderByField) => route('users.index', array_merge(request()->query(), ['order_by' => $orderByField, 'sort' => request('sort') === 'asc' ? 'desc' : 'asc']));
                  @endphp

                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    <a href="{{ $sortLink('name') }}" class="hover:underline">Nama</a>
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    <a href="{{ $sortLink('email') }}" class="hover:underline">Email</a>
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    <a href="{{ $sortLink('role') }}" class="hover:underline">Role</a>
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                @foreach ($users as $user)
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    {{ $user->role == 'admin' ? 'bg-green-100 text-green-800' : ($user->role == 'special' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                      {{ ucfirst($user->role) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-2 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                      Edit
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="mt-6">
            {{ $users->links() }}
          </div>
        </div>

      </div>
    </div>
  </div>
</x-app-layout>