@extends('emails.layout')

@section('title', $subject)

@section('content')
    <h1>{{ $subject }}</h1>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: #374151;">
        @if($actionType === 'activated')
            {{ __('common.greeting_activation', ['name' => $user->name]) }}
        @elseif($actionType === 'suspended')
            {{ __('common.greeting_suspension', ['name' => $user->name]) }}
        @elseif($actionType === 'deleted')
            {{ __('common.greeting_deletion', ['name' => $user->name]) }}
        @elseif($actionType === 'email_updated')
            {{ __('common.greeting_email_updated', ['name' => $user->name]) }}
        @elseif($actionType === 'welcome_verification')
            {{ __('common.greeting_welcome', ['name' => $user->name]) }}
        @else
            {{ __('common.welcome_name', ['name' => $user->name]) }}
        @endif
    </p>
    
    <div style="background: linear-gradient(135deg, #f3f4f6, #e5e7eb); padding: 30px; border-radius: 12px; margin: 30px 0; border-left: 4px solid var(--brand-primary);">
        <p style="font-size: 18px; font-weight: 600; margin: 0; color: var(--brand-dark);">
            {{ $emailMessage }}
        </p>
    </div>
    

    
    @if($actionType === 'activated')
        <div style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid #22c55e;">
            <p style="margin: 0; color: #15803d; font-weight: 600;">
                <strong>{{ __('common.account_number') }}:</strong> {{ $account->account_number }}
            </p>
        </div>
    @endif
    
    @if($actionType === 'suspended' && ($account->suspension_reason || $account->suspension_instructions))
        <div style="background: linear-gradient(135deg, #fef3c7, #fde68a); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid #f59e0b;">
            @if($account->suspension_reason)
                <div style="margin-bottom: 15px;">
                    <p style="margin: 0; color: #92400e; font-weight: 600;">
                        <strong>{{ __('common.suspension_reason') }}:</strong>
                    </p>
                    <p style="margin: 5px 0 0 0; color: #92400e; line-height: 1.5;">
                        {{ $account->suspension_reason }}
                    </p>
                </div>
            @endif
            
            @if($account->suspension_instructions)
                <div>
                    <p style="margin: 0; color: #92400e; font-weight: 600;">
                        <strong>{{ __('common.suspension_instructions') }}:</strong>
                    </p>
                    <p style="margin: 5px 0 0 0; color: #92400e; line-height: 1.5;">
                        {{ $account->suspension_instructions }}
                    </p>
                </div>
            @endif
        </div>
    @endif
    
    @if($actionType === 'email_updated')
        <div style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid #3b82f6;">
            <p style="margin: 0 0 15px 0; color: #1e40af; font-weight: 600;">
                {{ __('common.email_verification_required') }}
            </p>
            <p style="margin: 0 0 20px 0; color: #1e40af; line-height: 1.5;">
                {{ __('common.email_verification_instructions') }}
            </p>
            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ route('verification.verify', ['locale' => app()->getLocale(), 'id' => $user->id, 'hash' => sha1($user->email)]) }}?expires={{ now()->addMinutes(60)->timestamp }}&signature={{ hash_hmac('sha256', route('verification.verify', ['locale' => app()->getLocale(), 'id' => $user->id, 'hash' => sha1($user->email)]) . '?expires=' . now()->addMinutes(60)->timestamp, config('app.key')) }}" 
                   style="display: inline-block; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);">
                    {{ __('common.verify_email_button') }}
                </a>
            </div>
        </div>
    @endif

    @if($actionType === 'email_verification')
        <div style="background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border-left: 4px solid #0ea5e9; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0 0 16px; color: #374151; line-height: 1.6;">
                {{ __('common.email_verification_instructions') }}
            </p>
            <div style="text-align: center; margin: 24px 0;">
                <a href="{{ route('verification.verify', ['locale' => app()->getLocale(), 'id' => $user->id, 'hash' => sha1($user->email)]) }}?expires={{ now()->addMinutes(60)->timestamp }}&signature={{ hash_hmac('sha256', route('verification.verify', ['locale' => app()->getLocale(), 'id' => $user->id, 'hash' => sha1($user->email)]) . '?expires=' . now()->addMinutes(60)->timestamp, config('app.key')) }}" 
                   style="display: inline-block; background-color: #3B82F6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px;">
                    {{ __('common.verify_email_button') }}
                </a>
            </div>
            <p style="margin: 16px 0 0; color: #6B7280; font-size: 14px; line-height: 1.5;">
                {{ __('common.email_verification_note') }}
            </p>
        </div>
    @endif

    @if($actionType === 'welcome_verification')
        <div style="background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border-left: 4px solid #0ea5e9; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0 0 16px; color: #374151; line-height: 1.6;">
                {{ __('common.welcome_verification_instructions') }}
            </p>
            <div style="text-align: center; margin: 24px 0;">
                <a href="{{ route('verification.verify', ['locale' => app()->getLocale(), 'id' => $user->id, 'hash' => sha1($user->email)]) }}?expires={{ now()->addMinutes(60)->timestamp }}&signature={{ hash_hmac('sha256', route('verification.verify', ['locale' => app()->getLocale(), 'id' => $user->id, 'hash' => sha1($user->email)]) . '?expires=' . now()->addMinutes(60)->timestamp, config('app.key')) }}" 
                   style="display: inline-block; background-color: #3B82F6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px;">
                    {{ __('common.verify_email_button') }}
                </a>
            </div>
            <p style="margin: 16px 0 0; color: #6B7280; font-size: 14px; line-height: 1.5;">
                {{ __('common.welcome_verification_note') }}
            </p>
            <p style="margin: 16px 0 0; color: #6B7280; font-size: 14px; line-height: 1.5;">
                {{ __('common.welcome_verification_resend_info') }}
            </p>
        </div>
    @endif
    
@endsection