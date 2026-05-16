<x-mail::message>
# Reset your password

Hello {{ $userName }},

You requested a password reset for your **NeonBill** account. Click the button below to choose a new password.

<x-mail::button :url="$resetUrl">
Reset password
</x-mail::button>

This link expires in **{{ $expireMinutes }} minutes**.

If you did not request a reset, you can ignore this email. Your password will stay the same.

Thanks,<br>
{{ config('app.name') }}

<x-mail::subcopy>
If the button does not work, copy and paste this URL into your browser:<br>
<span style="word-break: break-all;">{{ $resetUrl }}</span>
</x-mail::subcopy>
</x-mail::message>
