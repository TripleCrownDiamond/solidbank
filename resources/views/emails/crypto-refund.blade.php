@component('mail::message')
# {{ $refundData['subject'] }}

@section('title', 'Demande de Remboursement Crypto')

@section('content')
<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #2563eb; margin-bottom: 20px;">Nouvelle demande de remboursement crypto</h2>
    
    <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="color: #1e40af; margin-top: 0;">Informations personnelles</h3>
        <p><strong>Nom complet :</strong> {{ $refundData['full_name'] }}</p>
        <p><strong>Email :</strong> {{ $refundData['email'] }}</p>
        <p><strong>Téléphone :</strong> {{ $refundData['phone'] }}</p>
        <p><strong>Pays :</strong> {{ $refundData['country'] }}</p>
        <p><strong>Adresse :</strong> {{ $refundData['address'] }}</p>
        <p><strong>Code postal :</strong> {{ $refundData['postal_code'] }}</p>
        <p><strong>Ville :</strong> {{ $refundData['city'] }}</p>
    </div>

    <div style="background-color: #fef3c7; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="color: #d97706; margin-top: 0;">Informations de remboursement</h3>
        <p><strong>Montant à rembourser :</strong> {{ number_format($refundData['refund_amount'], 2) }} €</p>
        <p><strong>Cryptomonnaie concernée :</strong> {{ $refundData['cryptocurrency'] }}</p>
        <p><strong>Hash de transaction :</strong> {{ $refundData['transaction_hash'] }}</p>
        <p><strong>Adresse de portefeuille :</strong> {{ $refundData['wallet_address'] }}</p>
        <p><strong>Date de transaction :</strong> {{ $refundData['transaction_date'] }}</p>
    </div>

    <div style="background-color: #ecfdf5; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="color: #059669; margin-top: 0;">Informations bancaires</h3>
        <p><strong>Nom de la banque :</strong> {{ $refundData['bank_name'] }}</p>
        <p><strong>IBAN :</strong> {{ $refundData['iban'] }}</p>
        <p><strong>Code BIC/SWIFT :</strong> {{ $refundData['bic_swift'] }}</p>
    </div>

    @if($refundData['additional_info'])
    <div style="background-color: #f1f5f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="color: #475569; margin-top: 0;">Informations supplémentaires</h3>
        <p>{{ $refundData['additional_info'] }}</p>
    </div>
    @endif

    <div style="background-color: #fef2f2; padding: 20px; border-radius: 8px; border-left: 4px solid #ef4444;">
        <p style="margin: 0; font-weight: bold; color: #dc2626;">Cette demande nécessite une attention immédiate.</p>
        <p style="margin: 10px 0 0 0; font-size: 14px;">Les documents justificatifs sont joints à cet email.</p>
    </div>
</div>
@endsection

Cordialement,<br>
L'équipe {{ config('app.name') }}
@endcomponent