@extends('layouts.app')

@section('title', 'Dashboard')

@section('header-actions')
    <x-neon-button :href="route('subscriptions.create')" class="w-full sm:w-auto">
        + Add subscription
    </x-neon-button>
@endsection

@section('content')
    <div
        id="dashboard-currency-root"
        data-currency-config='@json($currencyConfig)'
        data-subscriptions='@json($subscriptionsPayload)'
        data-chart-colors='@json($chartColors)'
        data-save-currency-url="{{ route('subscriptions.display-currency') }}"
        data-initial-category="{{ $categoryFilter }}"
    >
        <section class="mb-8 rounded-2xl border border-slate-700/80 bg-slate-800/80 p-4 sm:mb-10 sm:p-8 neon-border-cyan">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400 sm:text-sm">Monthly spend (active)</p>
                    <p id="monthly-total-display" class="mt-2 break-words text-3xl font-bold tracking-tight neon-text-green sm:text-4xl lg:text-5xl">
                        {{ $monthlyTotalFormatted }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500">
                        {{ $subscriptions->where('is_active', true)->count() }} active
                        · {{ $subscriptions->count() }} total subscriptions
                    </p>
                </div>
                <div class="grid w-full gap-4 sm:grid-cols-2 lg:w-auto lg:flex lg:flex-wrap lg:gap-4">
                    <div class="min-w-0">
                        <label for="category-filter" class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-500">
                            Category
                        </label>
                        <select
                            id="category-filter"
                            class="w-full min-h-11 rounded-lg border border-slate-600 bg-slate-900 px-3 py-2.5 text-base text-white focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 sm:text-sm"
                        >
                            <option value="" @selected($categoryFilter === '')>All categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" @selected($categoryFilter === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-0">
                        <label for="display-currency" class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-slate-500">
                            Display currency
                        </label>
                        <select
                            id="display-currency"
                            class="w-full min-h-11 rounded-lg border border-slate-600 bg-slate-900 px-3 py-2.5 text-base text-white focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 sm:text-sm"
                        >
                            @foreach ($currencies as $code)
                                <option value="{{ $code }}" @selected($displayCurrency === $code)>{{ $code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <p id="currency-hint" class="mt-4 text-xs text-slate-600">
                Totals convert all active bills to {{ $displayCurrency }} using static rates (no rounding).
            </p>
        </section>

        @if ($subscriptions->isNotEmpty() && count($chartData) > 0)
            <section class="mb-8 rounded-2xl border border-slate-700/80 bg-slate-800/80 p-4 sm:mb-10 sm:p-8">
                <h2 class="text-base font-semibold text-white sm:text-lg">Spending by category</h2>
                <p class="mt-1 text-sm text-slate-500">Monthly equivalent in selected currency</p>
                <div class="relative mt-4 aspect-square w-full max-w-xs mx-auto sm:mt-6 sm:max-w-md">
                    <canvas id="category-chart" class="max-h-[min(70vw,18rem)] w-full"></canvas>
                </div>
            </section>
        @endif

        <div class="mb-4 flex flex-col gap-1 sm:mb-6 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-base font-semibold text-white sm:text-lg">Your subscriptions</h2>
            <span class="text-xs text-slate-500 sm:text-sm">Sorted by nearest due date</span>
        </div>

        @if ($subscriptions->isEmpty())
            <div class="rounded-xl border border-dashed border-slate-700 bg-slate-800/40 px-6 py-16 text-center">
                <p class="text-slate-400">No subscriptions yet.</p>
                <p class="mt-1 text-sm text-slate-500">Add your first bill to start tracking monthly spend.</p>
                <x-neon-button :href="route('subscriptions.create')" class="mt-6">
                    Add subscription
                </x-neon-button>
            </div>
        @else
            <div id="subscription-grid" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($subscriptions as $subscription)
                    <x-subscription-card :subscription="$subscription" :display-currency="$displayCurrency" />
                @endforeach
            </div>
            <p id="filter-empty" class="hidden rounded-xl border border-dashed border-slate-700 bg-slate-800/40 px-6 py-12 text-center text-slate-400">
                No subscriptions in this category.
            </p>
        @endif
    </div>

    <x-confirm-modal />
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-delete-trigger]').forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById('delete-confirm-modal');
                const form = document.getElementById('delete-confirm-form');
                const nameEl = document.getElementById('delete-subscription-name');

                form.action = button.dataset.formAction;
                nameEl.textContent = button.dataset.subscriptionName;
                modal.showModal();
            });
        });

        document.getElementById('delete-modal-cancel')?.addEventListener('click', () => {
            document.getElementById('delete-confirm-modal').close();
        });
    </script>
@endpush
