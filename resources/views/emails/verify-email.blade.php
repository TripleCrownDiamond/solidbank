@extends('emails.layout')

@section('content')
    <h1>{{ __('Verify Email Address') }}</h1>

    <p>{{ __('Please click the button below to verify your email address.') }}</p>

    <a href="{{ $verificationUrl }}" class="button">
        {{ __('Verify Email Address') }}
    </a>

    <p>{{ __('If you did not create an account, no further action is required.') }}</p>
@endsection
