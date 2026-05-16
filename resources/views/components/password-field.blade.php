@props([
    'name',
    'id' => null,
    'label',
    'autocomplete' => 'current-password',
    'required' => true,
])

@php
    $inputId = $id ?? $name;
@endphp

<div>
    <label for="{{ $inputId }}" class="mb-1.5 block text-sm font-medium text-slate-300">{{ $label }}</label>
    <div class="relative">
        <input
            type="password"
            name="{{ $name }}"
            id="{{ $inputId }}"
            {{ $required ? 'required' : '' }}
            autocomplete="{{ $autocomplete }}"
            {{ $attributes->merge(['class' => 'input-touch w-full rounded-lg border border-slate-600 bg-slate-900 py-2.5 pl-4 pr-12 text-white focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/30']) }}
        >
        <button
            type="button"
            data-toggle-password="{{ $inputId }}"
            class="absolute inset-y-0 right-0 flex min-h-11 min-w-11 items-center justify-center rounded-r-lg text-slate-400 transition hover:text-cyan-300 focus:outline-none focus-visible:text-cyan-300 touch-manipulation"
            aria-label="Show password"
            aria-pressed="false"
        >
            <svg data-icon-show class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <svg data-icon-hide class="hidden h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 14.338 6.244 19.5 12 19.5c1.821 0 3.506-.426 4.974-1.134M9.722 9.722 12 12m0 0 3.278 3.278M12 12l3.278-3.278M12 12 9.722 9.722m12.02 2.438-4.35-4.35M3.98 8.223l4.35 4.35" />
            </svg>
        </button>
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
    @enderror
</div>
