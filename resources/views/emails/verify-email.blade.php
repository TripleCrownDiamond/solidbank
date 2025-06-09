<x-mail::message>
# {{ __('Verify Email Address') }}

{{ __('Please click the button below to verify your email address.') }}

<x-mail::button :url="$verificationUrl">
{{ __('Verify Email Address') }}
</x-mail::button>

{{ __('If you did not create an account, no further action is required.') }}

Thanks,<br>
{{ getAppName() }}
</x-mail::message>
