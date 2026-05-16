@php
    $subscription = $subscription ?? null;
    $priceRaw = old('price', $subscription?->price);
    $priceFormatted = filled($priceRaw) ? number_format((int) $priceRaw, 0, ',', '.') : '';
@endphp

<div class="space-y-5">
    <div>
        <label for="name" class="mb-1.5 block text-sm font-medium text-slate-300">Service name</label>
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name', $subscription?->name) }}"
            required
            placeholder="e.g. Spotify Premium"
            class="input-touch w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 @error('name') border-red-500 @enderror"
        >
        @error('name')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="category" class="mb-1.5 block text-sm font-medium text-slate-300">Category</label>
        <select
            name="category"
            id="category"
            required
            class="input-touch w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 @error('category') border-red-500 @enderror"
        >
            @foreach ($categories as $cat)
                <option value="{{ $cat }}" @selected(old('category', $subscription?->category ?? 'Other') === $cat)>
                    {{ $cat }}
                </option>
            @endforeach
        </select>
        @error('category')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="price_display" class="mb-1.5 block text-sm font-medium text-slate-300">Price</label>
            <input
                type="text"
                id="price_display"
                data-price-input
                data-price-target="price"
                value="{{ $priceFormatted }}"
                required
                inputmode="numeric"
                autocomplete="off"
                placeholder="54.900"
                class="input-touch w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 @error('price') border-red-500 @enderror"
            >
            <input type="hidden" name="price" id="price" value="{{ $priceRaw }}">
            @error('price')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="currency" class="mb-1.5 block text-sm font-medium text-slate-300">Currency</label>
            <select
                name="currency"
                id="currency"
                required
                class="input-touch w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 @error('currency') border-red-500 @enderror"
            >
                @foreach ($currencies as $code)
                    <option value="{{ $code }}" @selected(old('currency', $subscription?->currency ?? 'IDR') === $code)>
                        {{ $code }}
                    </option>
                @endforeach
            </select>
            @error('currency')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="billing_cycle" class="mb-1.5 block text-sm font-medium text-slate-300">Billing cycle</label>
        <select
            name="billing_cycle"
            id="billing_cycle"
            required
            class="input-touch w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 @error('billing_cycle') border-red-500 @enderror"
        >
            @foreach ($billingCycles as $cycle)
                <option value="{{ $cycle }}" @selected(old('billing_cycle', $subscription?->billing_cycle ?? 'Monthly') === $cycle)>
                    {{ $cycle }}
                </option>
            @endforeach
        </select>
        @error('billing_cycle')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="next_due_date" class="mb-1.5 block text-sm font-medium text-slate-300">Next due date</label>
        <input
            type="date"
            name="next_due_date"
            id="next_due_date"
            value="{{ old('next_due_date', $subscription?->next_due_date?->format('Y-m-d')) }}"
            required
            class="input-touch w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30 @error('next_due_date') border-red-500 @enderror"
        >
        @error('next_due_date')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3 rounded-lg border border-slate-700 bg-slate-900/50 px-4 py-3">
        <input
            type="hidden"
            name="is_active"
            value="0"
        >
        <input
            type="checkbox"
            name="is_active"
            id="is_active"
            value="1"
            @checked(old('is_active', $subscription?->is_active ?? true))
            class="h-5 w-5 shrink-0 rounded border-slate-600 bg-slate-800 text-cyan-500 focus:ring-cyan-500/40"
        >
        <label for="is_active" class="text-sm text-slate-300">Active subscription (uncheck to pause)</label>
    </div>
</div>
