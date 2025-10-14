<section>
  <div class="flex items-center mb-6">
    <a href="{{ url()->previous() }}" class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full hover:bg-gray-300 transition duration-150 mr-6">
      <i class="fas fa-arrow-left text-gray-700"></i>
    </a>

    <header>
      <h2 class="text-lg font-medium text-gray-900">
        {{ __('Update Password') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
      </p>
    </header>
  </div>

  <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
    @csrf
    @method('put')

    <div x-data="{ show: false }">
      <x-input-label for="update_password_current_password" :value="__('Current Password')" />
      <div class="relative">
        <x-text-input id="update_password_current_password" name="current_password" ::type="show ? 'text' : 'password'" class="mt-1 block w-full" autocomplete="current-password" />
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
          <i class="fas cursor-pointer text-gray-500" :class="{'fa-eye': !show, 'fa-eye-slash': show}" @click="show = !show"></i>
        </div>
      </div>
      <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
    </div>

    <div>
      <x-input-label for="update_password_password" :value="__('New Password')" />
      <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
      <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
    </div>

    <div>
      <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
      <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
      <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4">
      <x-primary-button>{{ __('Save') }}</x-primary-button>

      @if (session('status') === 'password-updated')
      <p
        x-data="{ show: true }"
        x-show="show"
        x-transition
        x-init="setTimeout(() => show = false, 2000)"
        class="text-sm text-gray-600">{{ __('Saved.') }}</p>
      @endif
    </div>
  </form>
</section>