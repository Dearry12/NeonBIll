@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('subscriptions.index') }}" class="mb-6 inline-flex text-sm text-slate-400 hover:text-cyan-300">
            ← Back to dashboard
        </a>

        <h1 class="text-2xl font-bold text-white">Profile settings</h1>
        <p class="mt-1 text-sm text-slate-400">Manage your account and preferences.</p>

        <form action="{{ route('profile.update') }}" method="POST" class="mt-8 space-y-6 rounded-xl border border-slate-700 bg-slate-800 p-6 sm:p-8">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="mb-1.5 block text-sm font-medium text-slate-300">Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 @error('name') border-red-500 @enderror"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-slate-300">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 @error('email') border-red-500 @enderror"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="default_currency" class="mb-1.5 block text-sm font-medium text-slate-300">Default display currency</label>
                <select
                    name="default_currency"
                    id="default_currency"
                    required
                    class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30"
                >
                    @foreach ($currencies as $code)
                        <option value="{{ $code }}" @selected(old('default_currency', $user->default_currency ?? 'IDR') === $code)>
                            {{ $code }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-slate-500">Used when you open the dashboard.</p>
            </div>

            <x-neon-button type="submit">Save profile</x-neon-button>
        </form>

        <form action="{{ route('profile.password') }}" method="POST" class="mt-6 space-y-6 rounded-xl border border-slate-700 bg-slate-800 p-6 sm:p-8">
            @csrf
            @method('PUT')

            <h2 class="text-lg font-semibold text-white">Change password</h2>

            <x-password-field name="current_password" label="Current password" autocomplete="current-password" />

            <x-password-field name="password" label="New password" autocomplete="new-password" class="{{ $errors->has('password') ? 'border-red-500' : '' }}" />

            <x-password-field name="password_confirmation" label="Confirm new password" autocomplete="new-password" />

            <x-neon-button type="submit" variant="secondary" class="w-full sm:w-auto">Update password</x-neon-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-6 sm:hidden">
            @csrf
            <x-neon-button type="submit" variant="ghost" class="w-full">
                Log out
            </x-neon-button>
        </form>
    </div>
@endsection
