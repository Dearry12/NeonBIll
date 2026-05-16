<?php

namespace App\Utilities;

use App\Models\Subscription;
use Illuminate\Support\Collection;

class SubscriptionUtility
{
    /**
     * @param  Collection<int, Subscription>  $subscriptions
     */
    public static function monthlyTotalPrecise(Collection $subscriptions, string $displayCurrency = 'IDR'): float
    {
        return $subscriptions
            ->where('is_active', true)
            ->sum(function (Subscription $sub) use ($displayCurrency) {
                $monthly = CurrencyUtility::monthlyEquivalentPrecise($sub->price, $sub->billing_cycle);

                return CurrencyUtility::convertPrecise($monthly, $sub->currency, $displayCurrency);
            });
    }

    /**
     * @param  Collection<int, Subscription>  $subscriptions
     */
    public static function monthlyTotal(Collection $subscriptions, string $displayCurrency = 'IDR'): int
    {
        return (int) round(self::monthlyTotalPrecise($subscriptions, $displayCurrency));
    }
}
