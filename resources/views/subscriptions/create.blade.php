@extends('layouts.app')

@section('title', 'Add subscription')

@section('content')
    <div class="mx-auto max-w-lg">
        <a href="{{ route('subscriptions.index') }}" class="mb-6 inline-flex text-sm text-slate-400 hover:text-cyan-300">
            ← Back to dashboard
        </a>

        <h1 class="text-2xl font-bold text-white">Add subscription</h1>
        <p class="mt-1 text-sm text-slate-400">Track a new recurring bill.</p>

        <form
            action="{{ route('subscriptions.store') }}"
            method="POST"
            class="mt-8 rounded-xl border border-slate-700 bg-slate-800 p-6 sm:p-8"
        >
            @csrf
            @include('subscriptions._form')

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <x-neon-button type="submit" class="w-full sm:flex-1">
                    Save subscription
                </x-neon-button>
                <x-neon-button :href="route('subscriptions.index')" variant="secondary" class="w-full sm:w-auto">
                    Cancel
                </x-neon-button>
            </div>
        </form>
    </div>
@endsection
