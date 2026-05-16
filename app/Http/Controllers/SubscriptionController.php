<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\Subscription;
use App\Utilities\CurrencyUtility;
use App\Utilities\DateUtility;
use App\Utilities\SubscriptionChartUtility;
use App\Utilities\SubscriptionUtility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $displayCurrency = $this->resolveDisplayCurrency($request);
        $categoryFilter = $request->string('category')->toString();
        $validCategory = in_array($categoryFilter, Subscription::CATEGORIES, true) ? $categoryFilter : '';

        $subscriptions = $request->user()
            ->subscriptions()
            ->orderBy('next_due_date')
            ->get();

        $monthlyTotal = SubscriptionUtility::monthlyTotalPrecise($subscriptions, $displayCurrency);
        $chartData = SubscriptionChartUtility::spendingByCategory($subscriptions, $displayCurrency);

        return view('subscriptions.index', [
            'subscriptions' => $subscriptions,
            'monthlyTotalFormatted' => CurrencyUtility::formatPrecise($monthlyTotal, $displayCurrency),
            'displayCurrency' => $displayCurrency,
            'categoryFilter' => $validCategory,
            'currencies' => Subscription::CURRENCIES,
            'categories' => Subscription::CATEGORIES,
            'currencyConfig' => CurrencyUtility::frontendConfig(),
            'chartData' => $chartData,
            'chartColors' => SubscriptionChartUtility::COLORS,
            'subscriptionsPayload' => $subscriptions->map(fn (Subscription $sub) => [
                'id' => $sub->id,
                'price' => $sub->price,
                'currency' => $sub->currency,
                'category' => $sub->category,
                'billing_cycle' => $sub->billing_cycle,
                'is_active' => $sub->is_active,
            ])->values(),
        ]);
    }

    public function saveDisplayCurrency(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'currency' => ['required', Rule::in(Subscription::CURRENCIES)],
        ]);

        session(['display_currency' => $validated['currency']]);

        return response()->json(['currency' => $validated['currency']]);
    }

    public function show(Subscription $subscription): View
    {
        $monthly = CurrencyUtility::monthlyEquivalentPrecise($subscription->price, $subscription->billing_cycle);
        $yearly = $subscription->billing_cycle === 'Yearly'
            ? (float) $subscription->price
            : $subscription->price * 12;

        return view('subscriptions.show', [
            'subscription' => $subscription,
            'monthly' => $monthly,
            'yearly' => $yearly,
            'dueLabel' => DateUtility::dueLabel($subscription->next_due_date),
            'dueUrgency' => DateUtility::dueUrgency($subscription->next_due_date),
        ]);
    }

    public function create(): View
    {
        return view('subscriptions.create', [
            'billingCycles' => Subscription::BILLING_CYCLES,
            'currencies' => Subscription::CURRENCIES,
            'categories' => Subscription::CATEGORIES,
        ]);
    }

    public function store(StoreSubscriptionRequest $request): RedirectResponse
    {
        $request->user()->subscriptions()->create([
            ...$request->validated(),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'Subscription added successfully.');
    }

    public function edit(Subscription $subscription): View
    {
        return view('subscriptions.edit', [
            'subscription' => $subscription,
            'billingCycles' => Subscription::BILLING_CYCLES,
            'currencies' => Subscription::CURRENCIES,
            'categories' => Subscription::CATEGORIES,
        ]);
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription): RedirectResponse
    {
        $subscription->update([
            ...$request->validated(),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'Subscription updated successfully.');
    }

    public function destroy(Subscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'Subscription deleted successfully.');
    }

    private function resolveDisplayCurrency(Request $request): string
    {
        if ($request->filled('currency') && CurrencyUtility::isValid($request->string('currency')->toString())) {
            $currency = $request->string('currency')->toString();
            session(['display_currency' => $currency]);

            return $currency;
        }

        $preferred = session(
            'display_currency',
            $request->user()->default_currency ?? 'IDR'
        );

        return CurrencyUtility::isValid($preferred) ? $preferred : 'IDR';
    }
}
