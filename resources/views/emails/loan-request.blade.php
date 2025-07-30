@extends('emails.layout')

@section('title')
    Nouvelle demande de prêt
@endsection

@section('content')
    <h1 style="color: var(--brand-primary); font-size: 28px; margin-bottom: 20px;">Nouvelle demande de prêt</h1>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: var(--text-primary);">
        Vous avez reçu une nouvelle demande de prêt depuis votre site web.
    </p>
    
    <!-- Informations personnelles -->
    <div style="background: linear-gradient(135deg, var(--info-bg), var(--info-bg)); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid var(--brand-primary);">
        <h3 style="color: var(--brand-primary-dark); margin-top: 0; margin-bottom: 20px;">Informations personnelles</h3>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">Nom complet :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ $loanData['full_name'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">Email :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ $loanData['email'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">Téléphone :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ $loanData['phone'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">Date de naissance :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ $loanData['birth_date'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">Situation matrimoniale :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ ucfirst($loanData['marital_status']) }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--info-border);">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">Pays :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ $loanData['country'] }}</span>
        </div>
        
        <div style="margin-bottom: 0; padding-bottom: 0;">
            <span style="font-weight: bold; color: var(--brand-primary-dark);">Adresse :</span>
            <span style="color: var(--brand-primary-dark); margin-left: 10px;">{{ $loanData['address'] }}, {{ $loanData['city'] }} {{ $loanData['postal_code'] }}</span>
        </div>
    </div>
    
    <!-- Informations de prêt -->
    <div style="background: linear-gradient(135deg, var(--success-bg), var(--success-bg)); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid var(--brand-accent);">
        <h3 style="color: var(--success-text); margin-top: 0; margin-bottom: 20px;">Informations de prêt</h3>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--success-border);">
            <span style="font-weight: bold; color: var(--success-text);">Montant demandé :</span>
            <span style="color: var(--success-text); margin-left: 10px;">{{ number_format($loanData['loan_amount'], 2) }} €</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--success-border);">
            <span style="font-weight: bold; color: var(--success-text);">Durée :</span>
            <span style="color: var(--success-text); margin-left: 10px;">{{ $loanData['loan_duration'] }} mois</span>
        </div>
        
        @if(isset($loanData['loan_purpose']) && !empty($loanData['loan_purpose']))
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--success-border);">
            <span style="font-weight: bold; color: var(--success-text);">Objet du prêt :</span>
            <span style="color: var(--success-text); margin-left: 10px;">{{ $loanData['loan_purpose'] }}</span>
        </div>
        @endif
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--success-border);">
            <span style="font-weight: bold; color: var(--success-text);">Revenus mensuels :</span>
            <span style="color: var(--success-text); margin-left: 10px;">{{ number_format($loanData['monthly_income'], 2) }} €</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid var(--success-border);">
            <span style="font-weight: bold; color: var(--success-text);">Statut professionnel :</span>
            <span style="color: var(--success-text); margin-left: 10px;">{{ ucfirst($loanData['employment_status']) }}</span>
        </div>
        
        @if(isset($loanData['employer_name']) && !empty($loanData['employer_name']))
        <div style="margin-bottom: 0; padding-bottom: 0;">
            <span style="font-weight: bold; color: var(--success-text);">Employeur :</span>
            <span style="color: var(--success-text); margin-left: 10px;">{{ $loanData['employer_name'] }}</span>
        </div>
        @endif
    </div>
    

    
    @if($loanData['additional_info'])
    <!-- Informations supplémentaires -->
    <div style="background: linear-gradient(135deg, var(--info-bg), var(--info-bg)); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid var(--brand-primary);">
        <h3 style="color: var(--brand-primary); margin-top: 0; margin-bottom: 20px;">Informations supplémentaires</h3>
        
        <div style="padding: 15px 0; color: var(--brand-primary); white-space: pre-wrap;">
            {{ $loanData['additional_info'] }}
        </div>
    </div>
    @endif
    
    <p style="font-size: 16px; line-height: 1.6; margin-top: 25px; color: var(--text-primary);">
        Cette demande de prêt a été envoyée depuis le formulaire du site web. Vous pouvez répondre directement à l'adresse email du demandeur.
    </p>
@endsection