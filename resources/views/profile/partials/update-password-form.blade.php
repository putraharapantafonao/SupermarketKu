<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Update Password') }}</h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <x-input-group
            label="Current Password"
            name="current_password"
            type="password"
            :error="$errors->updatePassword->get('current_password') ? $errors->updatePassword->first('current_password') : null"
        />

        <x-input-group
            label="New Password"
            name="password"
            type="password"
            :error="$errors->updatePassword->get('password') ? $errors->updatePassword->first('password') : null"
        />

        <x-input-group
            label="Confirm Password"
            name="password_confirmation"
            type="password"
            :error="$errors->updatePassword->get('password_confirmation') ? $errors->updatePassword->first('password_confirmation') : null"
        />

        <div class="flex items-center gap-4">
            <x-button variant="primary">{{ __('Save') }}</x-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
