@extends('emails.layout')

@section('title', __('forgot-password.new_password_email.title'))

@section('content')
    <h1>{{ __('forgot-password.new_password_email.title') }}</h1>
    
    <p style="margin-bottom: 20px;">
        {{ __('forgot-password.new_password_email.intro') }}
    </p>

    <div class="code-box">
        {{ $newPassword }}
    </div>

    <p style="margin-top: 20px;">
        {{ __('forgot-password.new_password_email.security_note') }}
    </p>

    <p style="margin-top: 10px; font-style: italic;">
        {{ __('forgot-password.new_password_email.no_action_required') }}
    </p>
@endsection