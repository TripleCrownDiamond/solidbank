@extends('emails.layout')

@section('title', __('login.otp_email_title'))

@section('content')
    <h1>{{ __('login.otp_email_title') }}</h1>
    
    <p style="margin-bottom: 20px;">
        {{ __('login.otp_email_greeting', ['name' => $user->first_name ?? $user->name]) }}
    </p>
    
    <p style="margin-bottom: 20px;">
        {{ __('login.otp_email_intro') }}
    </p>

    <div class="code-box">
        {{ $otp }}
    </div>

    <p style="margin-top: 20px;">
        {{ __('login.otp_email_expiry') }}
    </p>

    <p style="margin-top: 20px;">
        {{ __('login.otp_email_security_note') }}
    </p>

    <p style="margin-top: 10px; font-style: italic;">
        {{ __('login.otp_email_no_action') }}
    </p>
@endsection