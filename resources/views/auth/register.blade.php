@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <div class="rounded-2xl border border-slate-700/80 bg-slate-800 p-6 sm:p-8 neon-border-cyan">
        <h1 class="text-xl font-bold text-white">Create account</h1>
        <p class="mt-1 text-sm text-slate-400">Start tracking your subscriptions in seconds.</p>

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="name" class="mb-1.5 block text-sm font-medium text-slate-300">Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
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
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 @error('email') border-red-500 @enderror"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <x-password-field
                name="password"
                label="Password"
                autocomplete="new-password"
                class="{{ $errors->has('password') ? 'border-red-500' : '' }}"
            />

            <x-password-field
                name="password_confirmation"
                label="Confirm password"
                autocomplete="new-password"
            />

            <x-neon-button type="submit" class="w-full">
                Create account
            </x-neon-button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-cyan-400 hover:text-cyan-300">Log in</a>
        </p>
    </div>
@endsection
