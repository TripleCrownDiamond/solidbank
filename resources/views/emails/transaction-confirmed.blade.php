@extends('emails.layout')

@section('title')
    @if($transaction->type === 'DEPOSIT')
        {{ __('common.deposit_confirmed_email_subject') }}
    @elseif($transaction->type === 'WITHDRAWAL')
        {{ __('common.withdrawal_confirmed_email_subject') }}
    @elseif($transaction->type === 'TRANSFER_BANK')
        {{ __('transfers.bank_transfer_confirmed_subject') }}
    @elseif($transaction->type === 'TRANSFER_CRYPTO')
        {{ __('transfers.crypto_transfer_confirmed_subject') }}
    @elseif($transaction->type === 'TRANSFER_EXTERNAL')
        {{ __('transfers.external_transfer_confirmed_subject') }}
    @else
        {{ __('common.transaction_confirmed_subject') }}
    @endif
@endsection

@section('content')
    <div style="text-align: center; max-width: 100%; margin: 0 auto;">
        <h1>
            @if($transaction->type === 'DEPOSIT')
                {{ __('common.deposit_confirmed_email_subject') }}
            @elseif($transaction->type === 'WITHDRAWAL')
                {{ __('common.withdrawal_confirmed_email_subject') }}
            @elseif($transaction->type === 'TRANSFER_BANK')
                {{ __('transfers.bank_transfer_confirmed_subject') }}
            @elseif($transaction->type === 'TRANSFER_CRYPTO')
                {{ __('transfers.crypto_transfer_confirmed_subject') }}
            @elseif($transaction->type === 'TRANSFER_EXTERNAL')
                {{ __('transfers.external_transfer_confirmed_subject') }}
            @else
                {{ __('common.transaction_confirmed_subject') }}
            @endif
        </h1>
        
        <p class="message-success">
            {!! __('common.hello') !!} <strong>{{ $user->first_name }} {{ $user->last_name }}</strong> !
        </p>
        
        <p class="message-success">
            {!! $emailMessage !!}
        </p>
    
        <div class="transaction-details">
            <h3>{{ __('common.transaction_details') }}</h3>
            
            <div class="detail-row">
                <span class="detail-label">{{ __('common.reference') }} :</span>
                <span class="detail-value">{{ $transaction->reference }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">{{ __('common.type') }} :</span>
                <span class="detail-value">
                    @if($transaction->type === 'DEPOSIT')
                        {{ __('common.deposit') }}
                    @elseif($transaction->type === 'WITHDRAWAL')
                        {{ __('common.withdrawal') }}
                    @elseif($transaction->type === 'TRANSFER_BANK')
                        {{ __('transfers.bank_transfer') }}
                    @elseif($transaction->type === 'TRANSFER_CRYPTO')
                        {{ __('transfers.crypto_transfer') }}
                    @elseif($transaction->type === 'TRANSFER_EXTERNAL')
                        {{ __('transfers.external_transfer') }}
                    @else
                        {{ __('common.transaction') }}
                    @endif
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">{{ __('common.amount') }} :</span>
                <span class="amount-value">{{ $amount }} {{ $currency }}</span>
            </div>
        
            @if($transaction->account_id)
                <div class="detail-row">
                    <span class="detail-label">{{ __('common.account') }} :</span>
                    <span class="detail-value">{{ $transaction->account->account_number ?? 'N/A' }}</span>
                </div>
            @endif
            
            @if($transaction->wallet_id)
                <div class="detail-row">
                    <span class="detail-label">{{ __('common.wallet') }} :</span>
                    <span class="detail-value">{{ $transaction->wallet->coin ?? 'N/A' }} - {{ substr($transaction->wallet->address ?? '', 0, 10) }}...{{ substr($transaction->wallet->address ?? '', -6) }}</span>
                </div>
            @endif
            
            @if($transaction->description)
                <div class="detail-row">
                    <span class="detail-label">{{ __('common.reason') }} :</span>
                    <span class="detail-value">{{ $transaction->description }}</span>
                </div>
            @endif
            
            <div class="detail-row">
                <span class="detail-label">{{ __('common.processing_date') }} :</span>
                <span class="detail-value">{{ $transaction->processed_at ? $transaction->processed_at->format('d/m/Y à H:i') : 'N/A' }}</span>
            </div>
        </div>
        @if($transaction->type === 'DEPOSIT')
            <p class="message-success">{!! __('common.balance_credited_message', ['amount' => $amount . ' ' . $currency]) !!}</p>
        @elseif($transaction->type === 'WITHDRAWAL')
            <p class="message-success">{!! __('common.balance_debited_message', ['amount' => $amount . ' ' . $currency]) !!}</p>
        @endif
        
        <p class="message-success">{{ __('common.transaction_questions_contact') }}</p>
    </div>
@endsection