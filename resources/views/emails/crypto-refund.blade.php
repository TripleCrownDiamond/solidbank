@extends('emails.layout')

@section('title')
    Nouvelle demande de remboursement crypto
@endsection

@section('content')
    <div style="text-align: center; max-width: 100%; margin: 0 auto;">
        <p style="font-size: 16px; line-height: 1.5; color: var(--text-primary);">Bonjour,</p>

    <p style="font-size: 16px; line-height: 1.5; color: var(--text-primary);">
        Une nouvelle demande de remboursement de cryptomonnaie a été soumise via le formulaire de contact.
        Voici les détails de la demande :
    </p>

    <div style="background-color: var(--bg-secondary); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="font-size: 18px; color: var(--brand-primary); margin-top: 0;">Informations personnelles :</h3>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 8px;"><strong>Nom complet :</strong> {{ $refundData['full_name'] }}</li>
            <li style="margin-bottom: 8px;"><strong>Email :</strong> {{ $refundData['email'] }}</li>
            <li style="margin-bottom: 8px;"><strong>Téléphone :</strong> {{ $refundData['phone'] }}</li>
            <li style="margin-bottom: 8px;"><strong>Adresse :</strong> {{ $refundData['address'] }}, {{ $refundData['city'] }}, {{ $refundData['postal_code'] }}, {{ $refundData['country'] }}</li>
        </ul>

        <h3 style="font-size: 18px; color: var(--brand-primary); margin-top: 20px;">Détails du remboursement :</h3>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 8px;"><strong>Cryptomonnaie :</strong> {{ $refundData['cryptocurrency'] }}</li>
            <li style="margin-bottom: 8px;"><strong>Montant :</strong> {{ $refundData['amount'] }}</li>
        </ul>

        @if (!empty($refundData['additional_info']))
            <h3 style="font-size: 18px; color: var(--brand-primary); margin-top: 20px;">Informations supplémentaires :</h3>
            <p style="font-size: 16px; line-height: 1.5; color: var(--text-primary);">{{ $refundData['additional_info'] }}</p>
        @endif
    </div>

    <p style="font-size: 16px; line-height: 1.5; color: var(--text-primary);">
        Veuillez prendre les mesures nécessaires pour traiter cette demande.
    </p>

    <p style="font-size: 16px; line-height: 1.5; color: var(--text-primary);">
        Cordialement,
    </p>
        <p style="font-size: 16px; line-height: 1.5; color: var(--text-primary);">
            L'équipe {{ config('app.name') }}
        </p>
    </div>
@endsection