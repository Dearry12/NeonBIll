<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilePasswordRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Utilities\CurrencyUtility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user();

        return view('profile.edit', [
            'user' => $user,
            'currencies' => CurrencyUtility::codes(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());
        $user->save();

        session(['display_currency' => $user->default_currency]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(ProfilePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->string('password')->toString()),
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Password updated successfully.');
    }
}
