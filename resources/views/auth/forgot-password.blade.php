@extends('layouts.guest')

@section('title', 'Forgot password')

@section('content')
    <div class="rounded-2xl border border-slate-700/80 bg-slate-800 p-6 sm:p-8 neon-border-cyan">
        <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-500/20 ring-1 ring-cyan-500/40">
            <svg class="h-6 w-6 text-cyan-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
            </svg>
        </div>

        <h1 class="text-xl font-bold text-white">Forgot your password?</h1>
        <p class="mt-2 text-sm text-slate-400">
            Enter the email you used to register. We will send a secure link to reset your password.
        </p>

        @if (session('status'))
            <div
                role="alert"
                class="mt-5 rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300"
            >
                {{ session('status') }}
            </div>
            <p class="mt-3 text-xs text-slate-500">
                Check your inbox and spam folder. The link expires in 60 minutes.
            </p>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-slate-300">Email address</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                    class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 @error('email') border-red-500 @enderror"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <x-neon-button type="submit" class="w-full">
                Email password reset link
            </x-neon-button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Remember your password?
            <a href="{{ route('login') }}" class="font-medium text-cyan-400 hover:text-cyan-300">Back to login</a>
        </p>
    </div>
@endsection
