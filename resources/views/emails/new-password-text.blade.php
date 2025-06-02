{{ str_repeat('=', 50) }}
{{ str_pad(__('forgot-password.new_password_email.title'), 50, ' ', STR_PAD_BOTH) }}
{{ str_repeat('=', 50) }}

{{ __('forgot-password.new_password_email.intro') }}

{{ str_repeat('-', 50) }}
{{ str_pad(__('forgot-password.new_password_email.your_new_password'), 50, ' ', STR_PAD_BOTH) }}
{{ str_pad($newPassword, 50, ' ', STR_PAD_BOTH) }}
{{ str_repeat('-', 50) }}

{{ __('forgot-password.new_password_email.security_note') }}

{{ __('forgot-password.new_password_email.no_action_required') }}

{{ str_repeat('=', 50) }}
{{ str_pad(__('common.thanks'), 50, ' ', STR_PAD_RIGHT) }}{{ config('app.name') }}
{{ str_repeat('=', 50) }}