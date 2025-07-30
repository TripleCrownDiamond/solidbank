<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('common.transfer_receipt') }} - {{ $reference }}</title>
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
        <h1>{{ strtoupper(__('common.transfer_receipt')) }}</h1>
        <h2>{{ $config->bank_name ?? bank_config('bank_name', 'DBQIC') }}</h2>
        <p>{{ $config->bank_address ?? '' }}</p>
        @if($config->bank_phone)
            <p>Tél: {{ $config->bank_phone }}</p>
        @endif
        @if($config->bank_email)
            <p>Email: {{ $config->bank_email }}</p>
        @endif
    </div>

    <div class="bank-info">
        <h3>{{ __('common.bank_information') }}</h3>
        @if($config->bank_swift_code)
            <p><strong>{{ __('common.swift_code') }}:</strong> {{ $config->bank_swift_code }}</p>
        @endif
        @if($config->bank_country)
            <p><strong>{{ __('common.country') }}:</strong> {{ $config->bank_country }}</p>
        @endif
        @if($config->bank_website)
            <p><strong>{{ __('common.website') }}:</strong> {{ $config->bank_website }}</p>
        @endif
    </div>

    <div class="transaction-details">
        <h3>{{ __('common.transaction_details') }}</h3>
        
        <div class="detail-row">
            <span class="detail-label">{{ __('common.reference') }}:</span>
            <span class="detail-value">{{ $reference }}</span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">{{ __('common.date') }}:</span>
            <span class="detail-value">{{ $date }}</span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">{{ __('common.transaction_type') }}:</span>
            <span class="detail-value">
                @switch($transaction->type)
                    @case('TRANSFER_BANK')
                        {{ __('common.bank_transfer') }}
                        @break
                    @case('TRANSFER_CRYPTO')
                        {{ __('common.crypto_transfer') }}
                        @break
                    @case('TRANSFER_EXTERNAL')
                        {{ __('common.external_transfer') }}
                        @break
                    @default
                        {{ __('common.transfer') }}
                @endswitch
            </span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">{{ __('common.client') }}:</span>
            <span class="detail-value">{{ $user->first_name }} {{ $user->last_name }}</span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">{{ __('common.email') }}:</span>
            <span class="detail-value">{{ $user->email }}</span>
        </div>
        
        @if($transaction->account)
            <div class="detail-row">
                <span class="detail-label">{{ __('common.source_account') }}:</span>
                <span class="detail-value">{{ $transaction->account->account_number }}</span>
            </div>
        @endif
        
        @if($transaction->wallet)
            <div class="detail-row">
                <span class="detail-label">{{ __('common.source_wallet') }}:</span>
                <span class="detail-value">{{ $transaction->wallet->cryptocurrency->name }} ({{ $transaction->wallet->cryptocurrency->symbol }})</span>
            </div>
        @endif
        
        @if($transaction->recipient_name)
            <div class="detail-row">
                <span class="detail-label">{{ __('common.beneficiary') }}:</span>
                <span class="detail-value">{{ $transaction->recipient_name }}</span>
            </div>
        @endif
        
        @if($transaction->recipient_account)
            <div class="detail-row">
                <span class="detail-label">{{ __('common.beneficiary_account') }}:</span>
                <span class="detail-value">{{ $transaction->recipient_account }}</span>
            </div>
        @endif
        
    </div>

    {{-- Section dédiée aux informations du bénéficiaire --}}
    @if($transaction->external_bank_info && (isset($transaction->external_bank_info['recipient_name']) || isset($transaction->external_bank_info['recipient_iban']) || isset($transaction->external_bank_info['recipient_bank']) || isset($transaction->external_bank_info['recipient_country'])))
    <div class="transaction-details">
        <h3>{{ __('common.beneficiary_information') }}</h3>
        
        @if(isset($transaction->external_bank_info['recipient_name']))
            <div class="detail-row">
                <span class="detail-label">{{ __('common.recipient_name') }}:</span>
                <span class="detail-value">{{ $transaction->external_bank_info['recipient_name'] }}</span>
            </div>
        @endif
        
        @if(isset($transaction->external_bank_info['recipient_iban']))
            <div class="detail-row">
                <span class="detail-label">{{ __('common.recipient_iban') }}:</span>
                <span class="detail-value">{{ $transaction->external_bank_info['recipient_iban'] }}</span>
            </div>
        @endif
        
        @if(isset($transaction->external_bank_info['recipient_bank']))
            <div class="detail-row">
                <span class="detail-label">{{ __('common.recipient_bank') }}:</span>
                <span class="detail-value">{{ $transaction->external_bank_info['recipient_bank'] }}</span>
            </div>
        @endif
        
        @if(isset($transaction->external_bank_info['recipient_country']))
            <div class="detail-row">
                <span class="detail-label">{{ __('common.recipient_country') }}:</span>
                <span class="detail-value">{{ $transaction->external_bank_info['recipient_country'] }}</span>
            </div>
        @endif
    </div>
    @endif

    <div class="transaction-details">
        <h3>{{ __('common.additional_information') }}</h3>
        
        @if($transaction->description)
            <div class="detail-row">
                <span class="detail-label">{{ __('common.description') }}:</span>
                <span class="detail-value">{{ $transaction->description }}</span>
            </div>
        @endif
        
        <div class="detail-row">
            <span class="detail-label">{{ __('common.status') }}:</span>
            <span class="detail-value">
                <span class="status status-completed">{{ __('common.confirmed') }}</span>
            </span>
        </div>
    </div>

    <div class="amount-highlight">
        {{ __('common.transferred_amount') }}: {{ $amount }} {{ $currency }}
    </div>

    <div class="footer">
        <p>{{ __('common.transfer_success_confirmation') }}</p>
        <p>{{ __('common.keep_for_records') }}</p>
        <p>{{ __('common.generated_on') }} {{ now()->format('d/m/Y à H:i') }}</p>
        <p>{{ $config->bank_name ?? bank_config('bank_name', 'DBQIC') }} - {{ __('common.all_rights_reserved') }}</p>
    </div>
</body>
</html>