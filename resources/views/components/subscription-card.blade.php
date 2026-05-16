@props([
    'subscription',
    'displayCurrency' => 'IDR',
])

@php
    use App\Utilities\CurrencyUtility;
    use App\Utilities\DateUtility;

    $dueLabel = DateUtility::dueLabel($subscription->next_due_date);
    $urgency = DateUtility::dueUrgency($subscription->next_due_date);
    $urgencyClasses = match ($urgency) {
        'overdue' => 'text-red-400 bg-red-500/10 border-red-500/30',
        'urgent' => 'text-amber-400 bg-amber-500/10 border-amber-500/30',
        'soon' => 'text-yellow-300 bg-yellow-500/10 border-yellow-500/20',
        default => 'text-cyan-300 bg-cyan-500/10 border-cyan-500/20',
    };

    $nativeCurrency = $subscription->currency ?? 'IDR';
    $showConverted = $displayCurrency !== $nativeCurrency;
    $priceDisplay = CurrencyUtility::convertPrecise($subscription->price, $nativeCurrency, $displayCurrency);
    $monthlyDisplay = CurrencyUtility::convertPrecise(
        CurrencyUtility::monthlyEquivalentPrecise($subscription->price, $subscription->billing_cycle),
        $nativeCurrency,
        $displayCurrency
    );
@endphp

<article
    data-subscription-card
    data-price="{{ $subscription->price }}"
    data-currency="{{ $nativeCurrency }}"
    data-category="{{ $subscription->category ?? 'Other' }}"
    data-billing-cycle="{{ $subscription->billing_cycle }}"
    class="flex flex-col rounded-xl border border-slate-700/80 bg-slate-800 p-5 transition hover:border-cyan-500/30 hover:shadow-[0_0_20px_rgba(34,211,238,0.08)] {{ ! $subscription->is_active ? 'opacity-60' : '' }}"
>
    <div class="flex items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <h3 class="font-semibold text-white">{{ $subscription->name }}</h3>
                <span class="rounded border border-slate-600 bg-slate-900/80 px-1.5 py-0.5 text-[10px] font-medium text-slate-400">
                    {{ $subscription->category ?? 'Other' }}
                </span>
                <span data-card-currency-badge class="rounded border border-cyan-500/30 bg-cyan-500/10 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-cyan-300">
                    {{ $displayCurrency }}
                </span>
            </div>
            <p data-card-price class="mt-1 text-2xl font-bold neon-text-green">
                {{ CurrencyUtility::formatPrecise($priceDisplay, $displayCurrency) }}
            </p>
            <p class="mt-0.5 text-xs text-slate-500">
                {{ $subscription->billing_cycle === 'Yearly' ? 'per year' : 'per month' }}
                · <span data-card-monthly>~{{ CurrencyUtility::formatPrecise($monthlyDisplay, $displayCurrency) }}/mo</span>
            </p>
            <p data-card-original @class(['mt-1 text-[11px] text-slate-600', 'hidden' => ! $showConverted])>
                Originally {{ CurrencyUtility::formatPrecise((float) $subscription->price, $nativeCurrency) }}
                @if ($subscription->billing_cycle === 'Yearly')
                    /yr
                @endif
            </p>
        </div>
        <div class="flex flex-col items-end gap-1.5">
            @if (! $subscription->is_active)
                <span class="rounded-full border border-slate-600 bg-slate-700/50 px-2 py-0.5 text-xs text-slate-400">Paused</span>
            @endif
            <span class="rounded-full border px-2 py-0.5 text-xs {{ $subscription->billing_cycle === 'Monthly' ? 'border-fuchsia-500/30 bg-fuchsia-500/10 text-fuchsia-300' : 'border-violet-500/30 bg-violet-500/10 text-violet-300' }}">
                {{ $subscription->billing_cycle }}
            </span>
        </div>
    </div>

    <div class="mt-4 flex items-center justify-between gap-2">
        <span class="rounded-lg border px-2.5 py-1 text-xs font-medium {{ $urgencyClasses }}">
            {{ $dueLabel }} · {{ $subscription->next_due_date->format('M j, Y') }}
        </span>
    </div>

    <div class="mt-5 flex flex-col gap-2 border-t border-slate-700/80 pt-4">
        <x-neon-button :href="route('subscriptions.show', $subscription)" variant="secondary" class="w-full">
            Details
        </x-neon-button>
        <div class="flex gap-2">
            <x-neon-button :href="route('subscriptions.edit', $subscription)" variant="ghost" class="flex-1">
                Edit
            </x-neon-button>
            <x-neon-button
                type="button"
                variant="danger"
                class="flex-1"
                data-delete-trigger
                data-form-action="{{ route('subscriptions.destroy', $subscription) }}"
                data-subscription-name="{{ $subscription->name }}"
            >
                Delete
            </x-neon-button>
        </div>
    </div>
</article>
