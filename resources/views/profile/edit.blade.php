<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Pengaturan Profil" subtitle="Kelola informasi akun, keamanan password, dan privasi SupermarketKu" />
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <x-card>
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </x-card>

        <x-card>
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </x-card>

        <x-card padding="p-4 sm:p-6" class="border-red-200 dark:border-red-950/40">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </x-card>

    </div>
</x-app-layout>
