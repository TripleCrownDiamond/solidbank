@extends('emails.layout')

@section('title', 'Two-Factor Authentication Challenge')

@section('content')
    <h1>Two-Factor Authentication Challenge</h1>
    
    <p style="margin-bottom: 20px;">
        Hello {{ $user->name }},
    </p>
    
    <p style="margin-bottom: 20px;">
        You are attempting to log in to your account. Please use the following code to complete your two-factor authentication:
    </p>

    <div class="code-box">
        {{ $otp }}
    </div>

    <p style="margin-top: 20px; font-style: italic;">
        If you did not attempt to log in, please ignore this email.
    </p>
@endsection