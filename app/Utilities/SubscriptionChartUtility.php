<?php

namespace App\Utilities;

use App\Models\Subscription;
use Illuminate\Support\Collection;

class SubscriptionChartUtility
{
    public const COLORS = [
        '#22d3ee',
        '#a78bfa',
        '#f472b6',
        '#4ade80',
        '#fbbf24',
        '#fb7185',
    ];

    /**
     * @param  Collection<int, Subscription>  $subscriptions
     * @return array<int, array{category: string, total: float}>
     */
    public static function spendingByCategory(Collection $subscriptions, string $displayCurrency): array
    {
        return $subscriptions
            ->where('is_active', true)
            ->groupBy(fn (Subscription $sub) => $sub->category ?? 'Other')
            ->map(function (Collection $group, string $category) use ($displayCurrency) {
                $total = $group->sum(function (Subscription $sub) use ($displayCurrency) {
                    $monthly = CurrencyUtility::monthlyEquivalentPrecise($sub->price, $sub->billing_cycle);

                    return CurrencyUtility::convertPrecise($monthly, $sub->currency, $displayCurrency);
                });

                return [
                    'category' => $category,
                    'total' => round($total, 6),
                ];
            })
            ->sortByDesc('total')
            ->values()
            ->all();
    }
}
