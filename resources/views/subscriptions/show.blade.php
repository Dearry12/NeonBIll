@php
    use App\Utilities\CurrencyUtility;

    $currency = $subscription->currency ?? 'IDR';
    $urgencyClasses = match ($dueUrgency) {
        'overdue' => 'text-red-400 bg-red-500/10 border-red-500/30',
        'urgent' => 'text-amber-400 bg-amber-500/10 border-amber-500/30',
        'soon' => 'text-yellow-300 bg-yellow-500/10 border-yellow-500/20',
        default => 'text-cyan-300 bg-cyan-500/10 border-cyan-500/20',
    };
@endphp

@extends('layouts.app')

@section('title', $subscription->name)

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('subscriptions.index') }}" class="mb-6 inline-flex text-sm text-slate-400 hover:text-cyan-300">
            ← Back to dashboard
        </a>

        <div class="rounded-2xl border border-slate-700/80 bg-slate-800 p-6 sm:p-8 neon-border-cyan">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-2xl font-bold text-white">{{ $subscription->name }}</h1>
                        <span class="rounded-lg border border-slate-600 bg-slate-900/80 px-2 py-0.5 text-xs text-slate-400">
                            {{ $subscription->category ?? 'Other' }}
                        </span>
                        <span class="rounded-lg border border-cyan-500/30 bg-cyan-500/10 px-2 py-0.5 text-xs font-semibold text-cyan-300">
                            {{ $currency }}
                        </span>
                    </div>
                    @if (! $subscription->is_active)
                        <span class="mt-2 inline-block rounded-full border border-slate-600 bg-slate-700/50 px-2.5 py-0.5 text-xs text-slate-400">Paused</span>
                    @else
                        <span class="mt-2 inline-block rounded-full border border-green-500/30 bg-green-500/10 px-2.5 py-0.5 text-xs text-green-300">Active</span>
                    @endif
                </div>
                <span class="rounded-lg border px-3 py-1.5 text-sm font-medium {{ $urgencyClasses }}">
                    {{ $dueLabel }}
                </span>
            </div>

            <p class="mt-6 text-4xl font-bold neon-text-green">
                {{ CurrencyUtility::formatPrecise((float) $subscription->price, $currency) }}
            </p>
            <p class="mt-1 text-sm text-slate-500">
                Billed {{ strtolower($subscription->billing_cycle) }}
            </p>

            <dl class="mt-8 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-700 bg-slate-900/50 p-4">
                    <dt class="text-xs font-medium uppercase tracking-wider text-slate-500">Monthly equivalent</dt>
                    <dd class="mt-1 text-lg font-semibold text-white">{{ CurrencyUtility::formatPrecise($monthly, $currency) }}</dd>
                </div>
                <div class="rounded-xl border border-slate-700 bg-slate-900/50 p-4">
                    <dt class="text-xs font-medium uppercase tracking-wider text-slate-500">Yearly cost</dt>
                    <dd class="mt-1 text-lg font-semibold text-white">{{ CurrencyUtility::formatPrecise($yearly, $currency) }}</dd>
                </div>
                <div class="rounded-xl border border-slate-700 bg-slate-900/50 p-4">
                    <dt class="text-xs font-medium uppercase tracking-wider text-slate-500">Next due date</dt>
                    <dd class="mt-1 text-lg font-semibold text-white">{{ $subscription->next_due_date->format('l, M j, Y') }}</dd>
                </div>
                <div class="rounded-xl border border-slate-700 bg-slate-900/50 p-4">
                    <dt class="text-xs font-medium uppercase tracking-wider text-slate-500">Added</dt>
                    <dd class="mt-1 text-lg font-semibold text-white">{{ $subscription->created_at->format('M j, Y') }}</dd>
                </div>
            </dl>

            <div class="mt-8 rounded-xl border border-slate-700 bg-slate-900/30 p-4">
                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Converted estimates (monthly)</p>
                <ul class="mt-3 flex flex-wrap gap-2">
                    @foreach (CurrencyUtility::codes() as $code)
                        @if ($code !== $currency)
                            <li class="rounded-lg border border-slate-700 bg-slate-800 px-3 py-1.5 text-sm text-slate-300">
                                <span class="text-slate-500">{{ $code }}:</span>
                                {{ CurrencyUtility::formatPrecise(CurrencyUtility::convertPrecise($monthly, $currency, $code), $code) }}/mo
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <x-neon-button :href="route('subscriptions.edit', $subscription)">
                    Edit subscription
                </x-neon-button>
                <x-neon-button :href="route('subscriptions.index')" variant="secondary">
                    Back to dashboard
                </x-neon-button>
            </div>
        </div>
    </div>
@endsection
