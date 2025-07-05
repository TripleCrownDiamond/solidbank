@extends('emails.layout')

@section('title', 'Transaction Annulée')

@section('content')
    <h1 style="color: var(--error-border); font-size: 28px; margin-bottom: 20px;">❌ Transaction Annulée</h1>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">
        Bonjour <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>,
    </p>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">
        Nous vous informons que votre transaction a été annulée.
    </p>
    
    <div style="background: linear-gradient(135deg, var(--error-bg), var(--error-bg)); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid var(--error-border);">
        <h3 style="color: var(--error-text); margin-top: 0; margin-bottom: 20px;">Détails de la transaction annulée</h3>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--error-border);">
            <span style="font-weight: bold; color: var(--error-text);">Référence :</span>
            <span style="color: var(--error-text);">{{ $transaction->reference }}</span>
        </div>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--error-border);">
            <span style="font-weight: bold; color: var(--error-text);">Type :</span>
            <span style="color: var(--error-text);">
                @if($transaction->type === 'DEPOSIT')
                    Dépôt
                @elseif($transaction->type === 'WITHDRAWAL')
                    Retrait
                @else
                    {{ $transaction->type }}
                @endif
            </span>
        </div>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--error-border);">
            <span style="font-weight: bold; color: var(--error-text);">Montant :</span>
            <span style="font-size: 1.2em; font-weight: bold; color: var(--error-text);">{{ $amount }} {{ $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR')) }}</span>
        </div>
        
        @if($transaction->account_id)
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--error-border);">
                <span style="font-weight: bold; color: var(--error-text);">Compte :</span>
                <span style="color: var(--error-text);">{{ $transaction->account->account_number ?? 'N/A' }}</span>
            </div>
        @endif
        
        @if($transaction->wallet_id)
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--error-border);">
                <span style="font-weight: bold; color: var(--error-text);">Wallet :</span>
                <span style="color: var(--error-text);">{{ $transaction->wallet->coin ?? 'N/A' }} - {{ substr($transaction->wallet->address ?? '', 0, 10) }}...{{ substr($transaction->wallet->address ?? '', -6) }}</span>
            </div>
        @endif
        
        @if($transaction->description)
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--error-border);">
                <span style="font-weight: bold; color: var(--error-text);">Motif :</span>
                <span style="color: var(--error-text);">{{ $transaction->description }}</span>
            </div>
        @endif
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 0; padding-bottom: 0;">
            <span style="font-weight: bold; color: var(--error-text);">Date d'annulation :</span>
            <span style="color: var(--error-text);">{{ $transaction->cancelled_at ? $transaction->cancelled_at->format('d/m/Y à H:i') : now()->format('d/m/Y à H:i') }}</span>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, var(--warning-bg), var(--warning-bg)); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid var(--warning-border);">
        <p style="margin: 0; color: var(--warning-text); font-weight: 600;"><strong>⚠️ Important :</strong></p>
        <p style="margin: 5px 0 0 0; color: var(--warning-text); line-height: 1.5;">Cette transaction a été annulée et aucun montant n'a été traité. Si vous pensez qu'il s'agit d'une erreur, veuillez contacter notre service client.</p>
    </div>
    
    @if($transaction->type === 'DEPOSIT')
        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">Le dépôt de <strong>{{ $amount }} {{ $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR')) }}</strong> n'a pas été effectué.</p>
    @elseif($transaction->type === 'WITHDRAWAL')
        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">Le retrait de <strong>{{ $amount }} {{ $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR')) }}</strong> n'a pas été effectué.</p>
    @endif
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">Si vous souhaitez effectuer une nouvelle transaction, vous pouvez vous connecter à votre espace client ou contacter votre gestionnaire.</p>
@endsection