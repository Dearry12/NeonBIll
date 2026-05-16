@extends('layouts.guest')

@section('title', 'Set new password')

@section('content')
    <div class="rounded-2xl border border-slate-700/80 bg-slate-800 p-6 sm:p-8 neon-border-cyan">
        <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-500/20 ring-1 ring-cyan-500/40">
            <svg class="h-6 w-6 text-cyan-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        </div>

        <h1 class="text-xl font-bold text-white">Create a new password</h1>
        <p class="mt-2 text-sm text-slate-400">
            Choose a strong password for
            <span class="font-medium text-slate-300">{{ $email }}</span>.
        </p>

        <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-slate-300">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email', $email) }}"
                    required
                    readonly
                    class="w-full cursor-not-allowed rounded-lg border border-slate-700 bg-slate-900/60 px-4 py-2.5 text-slate-400"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <x-password-field name="password" label="New password" autocomplete="new-password" />

            <x-password-field name="password_confirmation" label="Confirm new password" autocomplete="new-password" />

            <x-neon-button type="submit" class="w-full">
                Save new password
            </x-neon-button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            <a href="{{ route('login') }}" class="font-medium text-cyan-400 hover:text-cyan-300">Back to login</a>
        </p>
    </div>
@endsection
