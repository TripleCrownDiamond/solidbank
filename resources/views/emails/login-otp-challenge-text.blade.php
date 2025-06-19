{{ str_pad(__('login.otp_email_title'), 50, ' ', STR_PAD_BOTH) }}

{{ __('login.otp_email_greeting', ['name' => $user->first_name ?? $user->name]) }}

{{ __('login.otp_email_intro') }}

{{ str_pad(__('login.otp_email_code_label'), 50, ' ', STR_PAD_BOTH) }}
{{ str_pad($otp, 50, ' ', STR_PAD_BOTH) }}

{{ __('login.otp_email_expiry') }}

{{ __('login.otp_email_security_note') }}

{{ __('login.otp_email_no_action') }}