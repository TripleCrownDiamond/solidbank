@extends('emails.layout')

@section('title')
    Nouvelle demande de prêt
@endsection

@section('content')
    <h1 style="color: var(--brand-primary); font-size: 28px; margin-bottom: 20px;">💰 Nouvelle demande de prêt</h1>
    
    <p style="font-size: 16px; line-height: 1.6; margin-bottom: 25px; color: #374151;">
        Vous avez reçu une nouvelle demande de prêt depuis votre site web.
    </p>
    
    <!-- Informations personnelles -->
    <div style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid #2563eb;">
        <h3 style="color: #1e40af; margin-top: 0; margin-bottom: 20px;">👤 Informations personnelles</h3>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #93c5fd;">
            <span style="font-weight: bold; color: #1e40af;">Nom complet :</span>
            <span style="color: #1e40af; margin-left: 10px;">{{ $loanData['first_name'] }} {{ $loanData['last_name'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #93c5fd;">
            <span style="font-weight: bold; color: #1e40af;">Email :</span>
            <span style="color: #1e40af; margin-left: 10px;">{{ $loanData['email'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #93c5fd;">
            <span style="font-weight: bold; color: #1e40af;">Téléphone :</span>
            <span style="color: #1e40af; margin-left: 10px;">{{ $loanData['phone'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #93c5fd;">
            <span style="font-weight: bold; color: #1e40af;">Date de naissance :</span>
            <span style="color: #1e40af; margin-left: 10px;">{{ $loanData['birth_date'] }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #93c5fd;">
            <span style="font-weight: bold; color: #1e40af;">Situation matrimoniale :</span>
            <span style="color: #1e40af; margin-left: 10px;">{{ ucfirst($loanData['marital_status']) }}</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #93c5fd;">
            <span style="font-weight: bold; color: #1e40af;">Pays :</span>
            <span style="color: #1e40af; margin-left: 10px;">{{ $loanData['country'] }}</span>
        </div>
        
        <div style="margin-bottom: 0; padding-bottom: 0;">
            <span style="font-weight: bold; color: #1e40af;">Adresse :</span>
            <span style="color: #1e40af; margin-left: 10px;">{{ $loanData['address'] }}, {{ $loanData['city'] }} {{ $loanData['postal_code'] }}</span>
        </div>
    </div>
    
    <!-- Informations de prêt -->
    <div style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid #10b981;">
        <h3 style="color: #047857; margin-top: 0; margin-bottom: 20px;">💳 Informations de prêt</h3>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #6ee7b7;">
            <span style="font-weight: bold; color: #047857;">Montant demandé :</span>
            <span style="color: #047857; margin-left: 10px;">{{ number_format($loanData['loan_amount'], 2) }} €</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #6ee7b7;">
            <span style="font-weight: bold; color: #047857;">Durée :</span>
            <span style="color: #047857; margin-left: 10px;">{{ $loanData['loan_duration'] }} mois</span>
        </div>
        
        @if(isset($loanData['loan_purpose']) && !empty($loanData['loan_purpose']))
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #6ee7b7;">
            <span style="font-weight: bold; color: #047857;">Objet du prêt :</span>
            <span style="color: #047857; margin-left: 10px;">{{ $loanData['loan_purpose'] }}</span>
        </div>
        @endif
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #6ee7b7;">
            <span style="font-weight: bold; color: #047857;">Revenus mensuels :</span>
            <span style="color: #047857; margin-left: 10px;">{{ number_format($loanData['monthly_income'], 2) }} €</span>
        </div>
        
        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #6ee7b7;">
            <span style="font-weight: bold; color: #047857;">Statut professionnel :</span>
            <span style="color: #047857; margin-left: 10px;">{{ ucfirst($loanData['employment_status']) }}</span>
        </div>
        
        @if(isset($loanData['employer_name']) && !empty($loanData['employer_name']))
        <div style="margin-bottom: 0; padding-bottom: 0;">
            <span style="font-weight: bold; color: #047857;">Employeur :</span>
            <span style="color: #047857; margin-left: 10px;">{{ $loanData['employer_name'] }}</span>
        </div>
        @endif
    </div>
    

    
    @if($loanData['additional_info'])
    <!-- Informations supplémentaires -->
    <div style="background: linear-gradient(135deg, #e0e7ff, #c7d2fe); padding: 25px; border-radius: 12px; margin: 25px 0; border: 2px solid #6366f1;">
        <h3 style="color: #4338ca; margin-top: 0; margin-bottom: 20px;">📝 Informations supplémentaires</h3>
        
        <div style="padding: 15px 0; color: #4338ca; white-space: pre-wrap;">
            {{ $loanData['additional_info'] }}
        </div>
    </div>
    @endif
    
    <p style="font-size: 16px; line-height: 1.6; margin-top: 25px; color: #374151;">
        Cette demande de prêt a été envoyée depuis le formulaire du site web. Vous pouvez répondre directement à l'adresse email du demandeur.
    </p>
@endsection