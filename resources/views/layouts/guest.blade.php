<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0f172a">
    <title>@yield('title', 'NeonBill') — Subscription Manager</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-900 text-slate-100 antialiased">
    <div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden px-4 py-8 pb-[max(2rem,env(safe-area-inset-bottom))] sm:py-12">
        <div class="pointer-events-none absolute -top-32 left-1/2 h-64 w-96 -translate-x-1/2 rounded-full bg-cyan-500/10 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-0 right-0 h-72 w-72 rounded-full bg-fuchsia-500/10 blur-3xl"></div>

        <a href="{{ route('login') }}" class="relative mb-8 flex items-center gap-2">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-500/20 text-lg font-bold neon-text-cyan ring-1 ring-cyan-500/40">N</span>
            <span class="text-2xl font-semibold tracking-tight">
                Neon<span class="neon-text-cyan">Bill</span>
            </span>
        </a>

        <div class="relative w-full max-w-md">
            @yield('content')
        </div>
    </div>
</body>
</html>
