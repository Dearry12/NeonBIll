@extends('layouts.guest')

@section('title', 'Log in')

@section('content')
    <div class="rounded-2xl border border-slate-700/80 bg-slate-800 p-6 sm:p-8 neon-border-cyan">
        <h1 class="text-xl font-bold text-white">Welcome back</h1>
        <p class="mt-1 text-sm text-slate-400">Sign in to manage your subscriptions.</p>

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-slate-300">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="input-touch w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 @error('email') border-red-500 @enderror"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <x-password-field
                name="password"
                label="Password"
                autocomplete="current-password"
                class="{{ $errors->has('password') ? 'border-red-500' : '' }}"
            />

            <p class="text-right text-sm">
                <a href="{{ route('password.request') }}" class="text-cyan-400 hover:text-cyan-300">Forgot password?</a>
            </p>

            <label class="flex items-center gap-2 text-sm text-slate-400">
                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                    class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-cyan-500 focus:ring-cyan-500/40"
                >
                Remember me
            </label>

            <x-neon-button type="submit" class="w-full">
                Log in
            </x-neon-button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            No account?
            <a href="{{ route('register') }}" class="font-medium text-cyan-400 hover:text-cyan-300">Create one</a>
        </p>
    </div>
@endsection
