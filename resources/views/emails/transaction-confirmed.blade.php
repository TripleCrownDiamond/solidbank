@extends('emails.layout')

@section('title')
    @if($transaction->type === 'DEPOSIT')
        {{ __('common.deposit_confirmed_email_subject') }}
    @elseif($transaction->type === 'WITHDRAWAL')
        {{ __('common.withdrawal_confirmed_email_subject') }}
    @else
        {{ __('common.transaction_confirmed_subject') }}
    @endif
@endsection

@section('content')
    <h1 style="color: var(--brand-primary); font-size: 28px; margin-bottom: 20px;">✅ 
        @if($transaction->type === 'DEPOSIT')
            {{ __('common.deposit_confirmed_email_subject') }}
        @elseif($transaction->type === 'WITHDRAWAL')
            {{ __('common.withdrawal_confirmed_email_subject') }}
        @else
            {{ __('common.transaction_confirmed_subject') }}
        @endif
    </h1>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">
        Bonjour <strong>{{ $user->first_name }} {{ $user->last_name }}</strong> !
    </p>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">
        {!! $emailMessage !!}
    </p>
    
    <div style="background: linear-gradient(135deg, var(--success-bg), var(--success-bg)); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid var(--success-border);">
        <h3 style="color: var(--success-text); margin-top: 0; margin-bottom: 20px;">Détails de la transaction</h3>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--success-bg);">
            <span style="font-weight: bold; color: var(--success-text);">Référence :</span>
            <span style="color: var(--success-text);">{{ $transaction->reference }}</span>
        </div>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--success-bg);">
            <span style="font-weight: bold; color: var(--success-text);">Type :</span>
            <span style="color: var(--success-text);">
                @if($transaction->type === 'DEPOSIT')
                    {{ __('common.deposit') }}
                @elseif($transaction->type === 'WITHDRAWAL')
                    {{ __('common.withdrawal') }}
                @else
                    {{ $transaction->type }}
                @endif
            </span>
        </div>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--success-bg);">
            <span style="font-weight: bold; color: var(--success-text);">Montant :</span>
            <span style="font-size: 1.2em; font-weight: bold; color: var(--success-text);">{{ $amount }} {{ $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR')) }}</span>
        </div>
        
        @if($transaction->account_id)
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--success-bg);">
                <span style="font-weight: bold; color: var(--success-text);">Compte :</span>
                <span style="color: var(--success-text);">{{ $transaction->account->account_number ?? 'N/A' }}</span>
            </div>
        @endif
        
        @if($transaction->wallet_id)
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--success-bg);">
                <span style="font-weight: bold; color: var(--success-text);">Wallet :</span>
                <span style="color: var(--success-text);">{{ $transaction->wallet->coin ?? 'N/A' }} - {{ substr($transaction->wallet->address ?? '', 0, 10) }}...{{ substr($transaction->wallet->address ?? '', -6) }}</span>
            </div>
        @endif
        
        @if($transaction->description)
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--success-bg);">
                <span style="font-weight: bold; color: var(--success-text);">Motif :</span>
                <span style="color: var(--success-text);">{{ $transaction->description }}</span>
            </div>
        @endif
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 0; padding-bottom: 0;">
            <span style="font-weight: bold; color: var(--success-text);">Date de traitement :</span>
            <span style="color: var(--success-text);">{{ $transaction->processed_at ? $transaction->processed_at->format('d/m/Y à H:i') : 'N/A' }}</span>
        </div>
    </div>
    
    @if($transaction->type === 'DEPOSIT')
        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">Votre solde a été crédité du montant de <strong>{{ $amount }} {{ $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR')) }}</strong>.</p>
    @elseif($transaction->type === 'WITHDRAWAL')
        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">Le montant de <strong>{{ $amount }} {{ $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR')) }}</strong> a été débité de votre solde.</p>
    @endif
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">Si vous avez des questions concernant cette transaction, n'hésitez pas à contacter notre service client.</p>
@endsection