@extends('emails.layout')

@section('title', $subject)

@section('content')
    <h1>{{ $subject }}</h1>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: #374151;">
        {{ $emailMessage }}
    </p>
        
    <div style="background: linear-gradient(135deg, #f3f4f6, #e5e7eb); padding: 30px; border-radius: 12px; margin: 30px 0; border-left: 4px solid var(--brand-primary);">
        <h3 style="color: var(--brand-dark); margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">{{ __('common.card_request_details') }}</h3>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e5e7eb;">
            <span style="font-weight: bold; color: #555;">{{ __('common.request_id') }}:</span>
            <span style="color: #333;">#{{ $cardRequest->id }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e5e7eb;">
            <span style="font-weight: bold; color: #555;">{{ __('common.card_type') }}:</span>
            <span style="color: #333;">{{ $cardRequest->card_type }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e5e7eb;">
            <span style="font-weight: bold; color: #555;">{{ __('common.status') }}:</span>
            <span style="color: #333;">
                @if(strtolower($cardRequest->status) === 'pending')
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; background: #fff3cd; color: #856404;">
                        {{ __('common.' . strtolower($cardRequest->status)) }}
                    </span>
                @elseif(strtolower($cardRequest->status) === 'cancelled')
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; background: #f8d7da; color: #721c24;">
                        {{ __('common.' . strtolower($cardRequest->status)) }}
                    </span>
                @else
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; background: #d1ecf1; color: #0c5460;">
                        {{ __('common.' . strtolower($cardRequest->status)) }}
                    </span>
                @endif
            </span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e5e7eb;">
            <span style="font-weight: bold; color: #555;">{{ __('common.phone_number') }}:</span>
            <span style="color: #333;">{{ $cardRequest->phone_number }}</span>
        </div>
        @if($cardRequest->message)
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e5e7eb;">
            <span style="font-weight: bold; color: #555;">{{ __('common.message') }}:</span>
            <span style="color: #333;">{{ $cardRequest->message }}</span>
        </div>
        @endif
        <div style="display: flex; justify-content: space-between; margin-bottom: 0; padding-bottom: 0;">
            <span style="font-weight: bold; color: #555;">{{ __('common.requested_on') }}:</span>
            <span style="color: #333;">{{ $cardRequest->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>
        
    <div style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid #3b82f6;">
        <h3 style="color: #1e40af; margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">{{ __('common.customer_information') }}</h3>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #bfdbfe;">
            <span style="font-weight: bold; color: #1e40af;">{{ __('common.full_name') }}:</span>
            <span style="color: #1e40af;">{{ $user->first_name }} {{ $user->last_name }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #bfdbfe;">
            <span style="font-weight: bold; color: #1e40af;">{{ __('common.email') }}:</span>
            <span style="color: #1e40af;">{{ $user->email }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #bfdbfe;">
            <span style="font-weight: bold; color: #1e40af;">{{ __('common.account_number') }}:</span>
            <span style="color: #1e40af;">{{ $cardRequest->account->account_number }}</span>
        </div>
        @if($user->date_of_birth)
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #bfdbfe;">
            <span style="font-weight: bold; color: #1e40af;">{{ __('common.date_of_birth') }}:</span>
            <span style="color: #1e40af;">{{ $user->date_of_birth->format('d/m/Y') }}</span>
        </div>
        @endif
        @if($user->address)
        <div style="display: flex; justify-content: space-between; margin-bottom: 0; padding-bottom: 0;">
            <span style="font-weight: bold; color: #1e40af;">{{ __('common.address') }}:</span>
            <span style="color: #1e40af;">{{ $user->address }}</span>
        </div>
        @endif
    </div>
    
    @if($actionType === 'new_request')
    <div style="background: linear-gradient(135deg, #fef3c7, #fde68a); padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #f59e0b;">
        <p style="margin: 0; color: #92400e; font-size: 16px; line-height: 1.5;">
            <strong>{{ __('common.action_required') }}:</strong> {{ __('common.please_review_card_request') }}
        </p>
    </div>
    @endif
    
@endsection