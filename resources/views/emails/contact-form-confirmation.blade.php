@extends('emails.layout')

@section('title')
    {{ __('common.contact_confirmation.title') }}
@endsection

@section('content')
    <p style="font-size: 16px; line-height: 1.5; color: #333;">{{ __('common.contact_confirmation.greeting', ['name' => $contactData['name']]) }}</p>

    <p style="font-size: 16px; line-height: 1.5; color: #333;">
        {{ __('common.contact_confirmation.message') }}
    </p>

    <div style="background-color: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="font-size: 18px; color: #0056b3; margin-top: 0;">{{ __('common.contact_confirmation.summary') }}</h3>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 8px;"><strong>{{ __('common.subject') }} :</strong> {{ $contactData['subject'] }}</li>
            <li style="margin-bottom: 8px;"><strong>{{ __('common.your_email') }} :</strong> {{ $contactData['email'] }}</li>
        </ul>
        
        <h4 style="font-size: 16px; color: #0056b3; margin-top: 15px; margin-bottom: 8px;">{{ __('common.your_message') }} :</h4>
        <div style="background-color: #fff; padding: 10px; border-radius: 4px; border-left: 4px solid #0056b3;">
            <p style="margin: 0; font-size: 14px; line-height: 1.4; color: #555;">{{ $contactData['message'] }}</p>
        </div>
    </div>

    <div style="background-color: #e8f4fd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="font-size: 18px; color: #0056b3; margin-top: 0;">{{ __('common.contact_confirmation.next_steps') }}</h3>
        <ul style="margin: 0; padding-left: 20px;">
            <li style="margin-bottom: 8px;">{{ __('common.contact_confirmation.step1') }}</li>
            <li style="margin-bottom: 8px;">{{ __('common.contact_confirmation.step2') }}</li>
            <li style="margin-bottom: 8px;">{{ __('common.contact_confirmation.step3') }}</li>
        </ul>
    </div>

    <p style="font-size: 16px; line-height: 1.5; color: #333;">
        {{ __('common.contact_confirmation.thank_you') }}
    </p>
@endsection