@extends('emails.layout')

@section('title')
    Nouveau message de contact
@endsection

@section('content')
    <h1 style="color: var(--brand-primary); font-size: 28px; margin-bottom: 20px;">📧 Nouveau message de contact</h1>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">
        Vous avez reçu un nouveau message depuis le formulaire de contact de votre site web.
    </p>
    
    <div style="background: linear-gradient(135deg, var(--info-bg), var(--info-bg)); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid var(--brand-primary);">
        <h3 style="color: var(--brand-primary-dark); margin-top: 0; margin-bottom: 20px;">Détails du message</h3>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">Nom :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ $contactData['name'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">Email :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ $contactData['email'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">Sujet :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ $contactData['subject'] }}</span>
        </div>
        
        <div style="margin-bottom: 0; padding-bottom: 0;">
            <span style="font-weight: bold; color: var(--brand-primary-dark); display: block; margin-bottom: 10px;">Message :</span>
            <div style="padding: 15px 0; color: var(--brand-primary-dark); white-space: pre-wrap;">
                {{ $contactData['message'] }}
            </div>
        </div>
    </div>
    
    <p style="font-size: 16px; line-height: 1.6; margin-top: 25px; color: var(--text-primary);">
        Ce message a été envoyé depuis le formulaire de contact du site web. Vous pouvez répondre directement à l'adresse email de l'expéditeur.
    </p>
@endsection