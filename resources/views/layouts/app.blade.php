<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>@yield('title', 'NeonBill') — Subscription Manager</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-900 text-slate-100 antialiased">
    <div class="relative flex min-h-screen flex-col overflow-hidden">
        <div class="pointer-events-none absolute -top-32 left-1/2 h-64 w-96 -translate-x-1/2 rounded-full bg-cyan-500/10 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-0 right-0 h-72 w-72 rounded-full bg-fuchsia-500/10 blur-3xl"></div>

        <header class="relative z-40 border-b border-slate-800/80 bg-slate-900/95 backdrop-blur-sm">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-3 sm:px-6 sm:py-4">
                <a href="{{ route('subscriptions.index') }}" class="flex min-h-11 min-w-11 items-center gap-2">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-cyan-500/20 text-lg font-bold neon-text-cyan ring-1 ring-cyan-500/40">N</span>
                    <span class="text-lg font-semibold tracking-tight sm:text-xl">
                        Neon<span class="neon-text-cyan">Bill</span>
                    </span>
                </a>

                <div class="hidden items-center gap-3 sm:flex">
                    @hasSection('header-actions')
                        @yield('header-actions')
                    @endif

                    <div class="flex items-center gap-2 border-l border-slate-700 pl-3">
                        <a href="{{ route('profile.edit') }}" class="max-w-[8rem] truncate text-sm text-slate-400 hover:text-cyan-300 md:max-w-xs">
                            {{ auth()->user()->name }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-neon-button type="submit" variant="ghost" class="!px-3 !py-2 text-sm">
                                Log out
                            </x-neon-button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="relative z-10 mx-auto w-full max-w-6xl flex-1 px-4 py-5 pb-28 sm:px-6 sm:py-8 sm:pb-8">
            @hasSection('header-actions')
                <div class="mb-5 sm:hidden">
                    @yield('header-actions')
                </div>
            @endif

            @if (session('success'))
                <div
                    role="alert"
                    class="mb-5 rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300 neon-text-green"
                >
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>

        <nav
            class="fixed inset-x-0 bottom-0 z-50 border-t border-slate-800 bg-slate-900/95 backdrop-blur-md pb-[env(safe-area-inset-bottom)] sm:hidden"
            aria-label="Main navigation"
        >
            <div class="mx-auto grid max-w-lg grid-cols-3 gap-1 px-2 py-2">
                <a
                    href="{{ route('subscriptions.index') }}"
                    @class([
                        'flex min-h-14 flex-col items-center justify-center gap-0.5 rounded-lg px-2 text-xs font-medium transition',
                        request()->routeIs('subscriptions.index', 'subscriptions.show') ? 'bg-cyan-500/15 text-cyan-300' : 'text-slate-400 active:bg-slate-800',
                    ])
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Home
                </a>
                <a
                    href="{{ route('subscriptions.create') }}"
                    @class([
                        'flex min-h-14 flex-col items-center justify-center gap-0.5 rounded-lg px-2 text-xs font-medium transition',
                        request()->routeIs('subscriptions.create') ? 'bg-cyan-500/15 text-cyan-300' : 'text-slate-400 active:bg-slate-800',
                    ])
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add
                </a>
                <a
                    href="{{ route('profile.edit') }}"
                    @class([
                        'flex min-h-14 flex-col items-center justify-center gap-0.5 rounded-lg px-2 text-xs font-medium transition',
                        request()->routeIs('profile.*') ? 'bg-cyan-500/15 text-cyan-300' : 'text-slate-400 active:bg-slate-800',
                    ])
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    Profile
                </a>
            </div>
        </nav>
    </div>

    @stack('scripts')
</body>
</html>
