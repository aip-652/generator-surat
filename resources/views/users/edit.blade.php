<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Edit Pengguna: ') . $user->name }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 md:p-8 bg-white border-b border-gray-200">

          <div class="flex items-center mb-8">
            <a href="{{ route('users.index') }}" class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full hover:bg-gray-300 transition duration-150">
              <i class="fas fa-arrow-left text-gray-700"></i>
            </a>
            <h2 class="text-xl font-bold text-gray-800 ml-4">
              Formulir Edit Pengguna
            </h2>
          </div>

          <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT') {{-- PENTING UNTUK PROSES UPDATE --}}

            <div>
              <x-input-label for="name" :value="__('Nama')" />
              <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
              <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
              <x-input-label for="email" :value="__('Email')" />
              <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password Baru (Opsional) -->
            <div x-data="{ show: false }">
              <x-input-label for="password" :value="__('Password Baru (Opsional)')" />
              <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full" ::type="show ? 'text' : 'password'" name="password" placeholder="Kosongkan jika tidak diubah" />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                  <i class="fas cursor-pointer text-gray-500" :class="{'fa-eye': !show, 'fa-eye-slash': show}" @click="show = !show"></i>
                </div>
              </div>
              <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Konfirmasi Password Baru -->
            <div>
              <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
              <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" placeholder="Ulangi password baru" />
              <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div>
              <x-input-label for="role" :value="__('Role')" />
              <x-select-input id="role" name="role" class="block mt-1 w-full" required>
                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                <option value="special" {{ old('role', $user->role) == 'special' ? 'selected' : '' }}>Special</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
              </x-select-input>
              <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end">
              <x-primary-button>
                {{ __('Simpan Perubahan') }}
              </x-primary-button>
            </div>
          </form>
        </div>
      </div>

      <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mt-5">
        <div class="max-w-xl">
          @include('profile.partials.delete-user-form')
        </div>
      </div>

    </div>
  </div>
</x-app-layout>