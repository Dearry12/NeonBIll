@extends('layouts.app')

@section('title', 'Edit '.$subscription->name)

@section('content')
    <div class="mx-auto max-w-lg">
        <a href="{{ route('subscriptions.index') }}" class="mb-6 inline-flex text-sm text-slate-400 hover:text-cyan-300">
            ← Back to dashboard
        </a>

        <h1 class="text-2xl font-bold text-white">Edit subscription</h1>
        <p class="mt-1 text-sm text-slate-400">Update price or billing schedule for {{ $subscription->name }}.</p>

        <form
            action="{{ route('subscriptions.update', $subscription) }}"
            method="POST"
            class="mt-8 rounded-xl border border-slate-700 bg-slate-800 p-6 sm:p-8"
        >
            @csrf
            @method('PUT')
            @include('subscriptions._form', ['subscription' => $subscription])

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <x-neon-button type="submit" class="w-full sm:flex-1">
                    Update subscription
                </x-neon-button>
                <x-neon-button :href="route('subscriptions.index')" variant="secondary" class="w-full sm:w-auto">
                    Cancel
                </x-neon-button>
            </div>
        </form>
    </div>
@endsection
