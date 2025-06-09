@extends('emails.layout')

@section('title')
    @if($transaction->type === 'DEPOSIT')
        Dépôt Confirmé
    @elseif($transaction->type === 'WITHDRAWAL')
        Retrait Confirmé
    @else
        Transaction Confirmée
    @endif
@endsection

@section('content')
    <h1 style="color: var(--brand-primary); font-size: 28px; margin-bottom: 20px;">✅ 
        @if($transaction->type === 'DEPOSIT')
            Dépôt Confirmé
        @elseif($transaction->type === 'WITHDRAWAL')
            Retrait Confirmé
        @else
            Transaction Confirmée
        @endif
    </h1>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: #374151;">
        Bonjour <strong>{{ $user->first_name }} {{ $user->last_name }}</strong> !
    </p>
    
    @if($transaction->type === 'DEPOSIT')
        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: #374151;">{{ __('common.deposit_confirmed_email_message', ['amount' => $amount . ' ' . $transaction->currency]) }}</p>
    @elseif($transaction->type === 'WITHDRAWAL')
        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: #374151;">{{ __('common.withdrawal_confirmed_email_message', ['amount' => $amount . ' ' . $transaction->currency]) }}</p>
    @else
        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: #374151;">Nous vous confirmons que votre transaction a été traitée avec succès.</p>
    @endif
    
    <div style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid #22c55e;">
        <h3 style="color: #15803d; margin-top: 0; margin-bottom: 20px;">Détails de la transaction</h3>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #a7f3d0;">
            <span style="font-weight: bold; color: #15803d;">Référence :</span>
            <span style="color: #15803d;">{{ $transaction->reference }}</span>
        </div>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #a7f3d0;">
            <span style="font-weight: bold; color: #15803d;">Type :</span>
            <span style="color: #15803d;">
                @if($transaction->type === 'DEPOSIT')
                    Dépôt
                @elseif($transaction->type === 'WITHDRAWAL')
                    Retrait
                @else
                    {{ $transaction->type }}
                @endif
            </span>
        </div>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #a7f3d0;">
            <span style="font-weight: bold; color: #15803d;">Montant :</span>
            <span style="font-size: 1.2em; font-weight: bold; color: #15803d;">{{ $amount }} {{ $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR')) }}</span>
        </div>
        
        @if($transaction->account_id)
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #a7f3d0;">
                <span style="font-weight: bold; color: #15803d;">Compte :</span>
                <span style="color: #15803d;">{{ $transaction->account->account_number ?? 'N/A' }}</span>
            </div>
        @endif
        
        @if($transaction->wallet_id)
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #a7f3d0;">
                <span style="font-weight: bold; color: #15803d;">Wallet :</span>
                <span style="color: #15803d;">{{ $transaction->wallet->coin ?? 'N/A' }} - {{ substr($transaction->wallet->address ?? '', 0, 10) }}...{{ substr($transaction->wallet->address ?? '', -6) }}</span>
            </div>
        @endif
        
        @if($transaction->description)
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #a7f3d0;">
                <span style="font-weight: bold; color: #15803d;">Motif :</span>
                <span style="color: #15803d;">{{ $transaction->description }}</span>
            </div>
        @endif
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 0; padding-bottom: 0;">
            <span style="font-weight: bold; color: #15803d;">Date de traitement :</span>
            <span style="color: #15803d;">{{ $transaction->processed_at ? $transaction->processed_at->format('d/m/Y à H:i') : 'N/A' }}</span>
        </div>
    </div>
    
    @if($transaction->type === 'DEPOSIT')
        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: #374151;">Votre solde a été crédité du montant de <strong>{{ $amount }} {{ $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR')) }}</strong>.</p>
    @elseif($transaction->type === 'WITHDRAWAL')
        <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: #374151;">Le montant de <strong>{{ $amount }} {{ $transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR')) }}</strong> a été débité de votre solde.</p>
    @endif
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: #374151;">Si vous avez des questions concernant cette transaction, n'hésitez pas à contacter notre service client.</p>
@endsection