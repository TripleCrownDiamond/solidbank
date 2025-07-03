@extends('emails.layout')

@section('title')
    {{ __('crypto.confirmation.title') }}
@endsection

@section('content')
    <p style="font-size: 16px; line-height: 1.5; color: #333;">{{ __('crypto.confirmation.greeting', ['name' => $refundData['first_name']]) }}</p>

    <p style="font-size: 16px; line-height: 1.5; color: #333;">
        {{ __('crypto.confirmation.message') }}
    </p>

    <div style="background-color: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="font-size: 18px; color: #0056b3; margin-top: 0;">{{ __('crypto.confirmation.summary') }}</h3>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 8px;"><strong>{{ __('crypto.cryptocurrency') }} :</strong> {{ $refundData['cryptocurrency'] }}</li>
            <li style="margin-bottom: 8px;"><strong>{{ __('crypto.amount') }} :</strong> {{ $refundData['amount'] }}</li>
            <li style="margin-bottom: 8px;"><strong>{{ __('crypto.country') }} :</strong> {{ $refundData['country'] }}</li>
            <li style="margin-bottom: 8px;"><strong>{{ __('crypto.city') }} :</strong> {{ $refundData['city'] }}</li>
        </ul>
    </div>

    <div style="background-color: #e8f4fd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="font-size: 18px; color: #0056b3; margin-top: 0;">{{ __('crypto.confirmation.next_steps') }}</h3>
        <ul style="margin: 0; padding-left: 20px;">
            <li style="margin-bottom: 8px;">{{ __('crypto.confirmation.step1') }}</li>
            <li style="margin-bottom: 8px;">{{ __('crypto.confirmation.step2') }}</li>
            <li style="margin-bottom: 8px;">{{ __('crypto.confirmation.step3') }}</li>
        </ul>
    </div>

    <p style="font-size: 16px; line-height: 1.5; color: #333;">
        {{ __('crypto.confirmation.contact_info') }}
    </p>
@endsection