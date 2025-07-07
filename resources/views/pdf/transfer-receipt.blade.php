<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Transfert - {{ $reference }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: var(--text-primary);
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid var(--brand-primary);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .bank-info {
            background-color: var(--bg-light);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .transaction-details {
            background-color: var(--color-white);
            border: 1px solid var(--border-light);
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
            border-bottom: 1px dotted var(--border-medium);
        }
        .detail-label {
            font-weight: bold;
            color: var(--text-primary);
        }
        .detail-value {
            color: var(--text-primary);
        }
        .amount-highlight {
            background-color: var(--info-bg);
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-size: 1.2em;
            font-weight: bold;
            color: var(--brand-primary);
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--border-light);
            font-size: 0.9em;
            color: var(--text-secondary);
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-completed {
            background-color: var(--success-bg);
            color: var(--success-text);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REÇU DE TRANSFERT</h1>
        <h2>{{ $config->bank_name ?? 'Celesium-Fin' }}</h2>
        <p>{{ $config->bank_address ?? '' }}</p>
        @if($config->bank_phone)
            <p>Tél: {{ $config->bank_phone }}</p>
        @endif
        @if($config->bank_email)
            <p>Email: {{ $config->bank_email }}</p>
        @endif
    </div>

    <div class="bank-info">
        <h3>Informations Bancaires</h3>
        @if($config->bank_swift_code)
            <p><strong>Code SWIFT:</strong> {{ $config->bank_swift_code }}</p>
        @endif
        @if($config->bank_country)
            <p><strong>Pays:</strong> {{ $config->bank_country }}</p>
        @endif
        @if($config->bank_website)
            <p><strong>Site Web:</strong> {{ $config->bank_website }}</p>
        @endif
    </div>

    <div class="transaction-details">
        <h3>Détails de la Transaction</h3>
        
        <div class="detail-row">
            <span class="detail-label">Référence:</span>
            <span class="detail-value">{{ $reference }}</span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Date:</span>
            <span class="detail-value">{{ $date }}</span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Type de Transaction:</span>
            <span class="detail-value">
                @switch($transaction->type)
                    @case('TRANSFER_BANK')
                        Transfert Bancaire
                        @break
                    @case('TRANSFER_CRYPTO')
                        Transfert Crypto
                        @break
                    @case('TRANSFER_EXTERNAL')
                        Transfert Externe
                        @break
                    @default
                        Transfert
                @endswitch
            </span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Client:</span>
            <span class="detail-value">{{ $user->first_name }} {{ $user->last_name }}</span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Email:</span>
            <span class="detail-value">{{ $user->email }}</span>
        </div>
        
        @if($transaction->account)
            <div class="detail-row">
                <span class="detail-label">Compte Source:</span>
                <span class="detail-value">{{ $transaction->account->account_number }}</span>
            </div>
        @endif
        
        @if($transaction->wallet)
            <div class="detail-row">
                <span class="detail-label">Portefeuille Source:</span>
                <span class="detail-value">{{ $transaction->wallet->cryptocurrency->name }} ({{ $transaction->wallet->cryptocurrency->symbol }})</span>
            </div>
        @endif
        
        @if($transaction->recipient_name)
            <div class="detail-row">
                <span class="detail-label">Bénéficiaire:</span>
                <span class="detail-value">{{ $transaction->recipient_name }}</span>
            </div>
        @endif
        
        @if($transaction->recipient_account)
            <div class="detail-row">
                <span class="detail-label">Compte Bénéficiaire:</span>
                <span class="detail-value">{{ $transaction->recipient_account }}</span>
            </div>
        @endif
        
        @if($transaction->description)
            <div class="detail-row">
                <span class="detail-label">Description:</span>
                <span class="detail-value">{{ $transaction->description }}</span>
            </div>
        @endif
        
        <div class="detail-row">
            <span class="detail-label">Statut:</span>
            <span class="detail-value">
                <span class="status status-completed">Confirmé</span>
            </span>
        </div>
    </div>

    <div class="amount-highlight">
        Montant Transféré: {{ $amount }} {{ $currency }}
    </div>

    <div class="footer">
        <p>Ce reçu confirme que votre transfert a été traité avec succès.</p>
        <p>Conservez ce document pour vos archives.</p>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>{{ $config->bank_name ?? 'Celesium-Fin' }} - Tous droits réservés</p>
    </div>
</body>
</html>