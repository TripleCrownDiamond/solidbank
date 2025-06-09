{{ str_repeat('=', 50) }}
{{ str_pad(__('login.otp_email_title'), 50, ' ', STR_PAD_BOTH) }}
{{ str_repeat('=', 50) }}

{{ __('login.otp_email_intro') }}

{{ str_repeat('-', 50) }}
{{ str_pad(__('login.otp_email_code_label'), 50, ' ', STR_PAD_BOTH) }}
{{ str_pad($otp, 50, ' ', STR_PAD_BOTH) }}
{{ str_repeat('-', 50) }}

{{ __('login.otp_email_security_note') }}

{{ __('login.otp_email_no_action') }}

{{ str_repeat('=', 50) }}
{{ str_pad(__('common.thanks'), 50, ' ', STR_PAD_RIGHT) }}{{ getAppName() }}
{{ str_repeat('=', 50) }}