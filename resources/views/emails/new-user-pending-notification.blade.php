@extends('emails.layout')

@section('content')
    @php
        $config = getBrandingConfig();
        $brandPrimary = $config && $config->brand_color ? $config->brand_color : '#2563eb';
        $textPrimary = '#1f2937';
        $textSecondary = '#6b7280';
    @endphp
    <div style="text-align: center; max-width: 100%; margin: 0 auto;">
        <h1 style="color: {{ $textPrimary }}; font-size: 24px; font-weight: normal; margin: 0 0 32px 0;">
            {{ __('auth.new_user_pending_title') }}
        </h1>

    <p style="color: {{ $textPrimary }}; font-size: 16px; line-height: 1.6; margin: 0 0 24px;">
        {{ __('auth.admin_greeting') }}
    </p>

    <p style="color: {{ $textPrimary }}; font-size: 16px; line-height: 1.6; margin: 0 0 24px;">
        {{ __('auth.new_user_pending_message') }}
    </p>

    <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; margin: 24px 0; border: 1px solid #e5e7eb;">
        <p style="color: {{ $textPrimary }}; font-size: 16px; line-height: 1.6; margin: 0 0 16px; font-weight: 500;">
            {{ __('auth.user_details_title') }}
        </p>
        <ul style="color: {{ $textSecondary }}; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 20px;">
            <li><strong>{{ __('register.first_name') }} :</strong> {{ $user->first_name }}</li>
            <li><strong>{{ __('register.last_name') }} :</strong> {{ $user->last_name }}</li>
            <li><strong>{{ __('register.email') }} :</strong> {{ $user->email }}</li>
            <li><strong>{{ __('register.phone_number') }} :</strong> {{ $user->phone }}</li>
            <li><strong>{{ __('auth.registration_date') }} :</strong> {{ $user->created_at->format('d/m/Y H:i') }}</li>
        </ul>
    </div>

    <div style="text-align: center; margin: 32px 0;">
        <a href="{{ $adminUrl }}" 
           style="display: inline-block; background-color: {{ $brandPrimary }}; color: white !important; text-decoration: none; padding: 12px 24px; border-radius: 4px; font-weight: normal; font-size: 16px; border: none;">
            {{ __('auth.manage_users_button') }}
        </a>
    </div>

        <div style="background-color: #fef3c7; padding: 20px; border-radius: 8px; margin: 24px 0; border-left: 4px solid #f59e0b;">
            <p style="color: {{ $textPrimary }}; font-size: 14px; line-height: 1.6; margin: 0;">
                {{ __('auth.admin_action_required') }}
            </p>
        </div>
    </div>
@endsection