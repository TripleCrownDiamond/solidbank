<x-mail::message>
# Two-Factor Authentication Challenge

Hello {{ $user->name }},

You are attempting to log in to your account. Please use the following code to complete your two-factor authentication:

**{{ $otp }}**

If you did not attempt to log in, please ignore this email.

Thanks,
{{ config('app.name') }}
</x-mail::message>