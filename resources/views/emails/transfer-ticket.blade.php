<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('transfers.transfer_ticket_title') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .ticket-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .bank-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
        }
        .bank-info h3 {
            margin: 0 0 15px 0;
            color: #495057;
            font-size: 18px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-card {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 20px;
        }
        .info-card h4 {
            margin: 0 0 15px 0;
            color: #495057;
            font-size: 16px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 8px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #f1f3f4;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
        }
        .info-value {
            color: #495057;
            text-align: right;
        }
        .amount-highlight {
            background: #e8f5e8;
            color: #155724;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        .reference-code {
            font-family: 'Courier New', monospace;
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        @media print {
            body {
                background-color: white;
            }
            .ticket-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="header">
            <h1>{{ __('transfers.transfer_ticket_title') }}</h1>
            <p>{{ __('transfers.transfer_receipt_subtitle') }}</p>
        </div>

        <div class="content">
            <!-- Informations de la banque -->
            <div class="bank-info">
                <h3>{{ __('transfers.bank_information') }}</h3>
                <div class="info-grid">
                    <div>
                        <strong>{{ $bank_info['name'] }}</strong><br>
                        @if($bank_info['address'])
                            {{ $bank_info['address'] }}<br>
                        @endif
                        @if($bank_info['country'])
                            {{ $bank_info['country'] }}<br>
                        @endif
                    </div>
                    <div>
                        @if($bank_info['swift'])
                            <strong>{{ __('transfers.swift_code') }}:</strong> {{ $bank_info['swift'] }}<br>
                        @endif
                        @if($bank_info['phone'])
                            <strong>{{ __('transfers.phone') }}:</strong> {{ $bank_info['phone'] }}<br>
                        @endif
                        @if($bank_info['email'])
                            <strong>{{ __('transfers.email') }}:</strong> {{ $bank_info['email'] }}<br>
                        @endif
                        @if($bank_info['website'])
                            <strong>{{ __('transfers.website') }}:</strong> {{ $bank_info['website'] }}
                        @endif
                    </div>
                </div>
            </div>

            <!-- Détails de la transaction -->
            <div class="info-grid">
                <div class="info-card">
                    <h4>{{ __('transfers.transaction_details') }}</h4>
                    <div class="info-row">
                        <span class="info-label">{{ __('transfers.reference') }}:</span>
                        <span class="info-value reference-code">{{ $transaction->reference }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">{{ __('transfers.type') }}:</span>
                        <span class="info-value">{{ __('transfers.types.' . strtolower(str_replace('TRANSFER_', '', $transaction->type))) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">{{ __('transfers.amount') }}:</span>
                        <span class="info-value amount-highlight">{{ number_format($transaction->amount, 2) }} {{ $transaction->currency ?? 'EUR' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">{{ __('transfers.status') }}:</span>
                        <span class="info-value">
                            <span class="status-badge status-completed">{{ __('transfers.completed') }}</span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">{{ __('transfers.date') }}:</span>
                        <span class="info-value">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">{{ __('transfers.processed_date') }}:</span>
                        <span class="info-value">{{ $transaction->processed_at ? $transaction->processed_at->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                </div>

                <div class="info-card">
                    <h4>{{ __('transfers.sender_information') }}</h4>
                    <div class="info-row">
                        <span class="info-label">{{ __('transfers.client') }}:</span>
                        <span class="info-value">{{ $transaction->user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">{{ __('transfers.email') }}:</span>
                        <span class="info-value">{{ $transaction->user->email }}</span>
                    </div>
                    @if($transaction->account_id)
                        <div class="info-row">
                            <span class="info-label">{{ __('transfers.source_account') }}:</span>
                            <span class="info-value">{{ $transaction->account->account_number ?? __('transfers.account') . ' #' . $transaction->account_id }}</span>
                        </div>
                    @endif
                    @if($transaction->wallet_id)
                        <div class="info-row">
                            <span class="info-label">{{ __('transfers.source_wallet') }}:</span>
                            <span class="info-value">{{ $transaction->wallet->name ?? __('transfers.wallet') . ' #' . $transaction->wallet_id }}</span>
                        </div>
                    @endif
                </div>
            </div>

            @if($transaction->description)
            <div class="info-card">
                <h4>{{ __('transfers.description') }}</h4>
                <p style="margin: 0; color: #495057;">{{ $transaction->description }}</p>
            </div>
            @endif

            <!-- Informations du destinataire -->
            @if($transaction->to_bank_name || $transaction->to_account_number || $transaction->to_crypto_address)
            <div class="info-card" style="margin-top: 20px;">
                <h4>{{ __('transfers.recipient_information') }}</h4>
                @if($transaction->to_bank_name)
                    <div class="info-row">
                        <span class="info-label">{{ __('transfers.recipient_bank') }}:</span>
                        <span class="info-value">{{ $transaction->to_bank_name }}</span>
                    </div>
                @endif
                @if($transaction->to_account_number)
                    <div class="info-row">
                        <span class="info-label">{{ __('transfers.recipient_account') }}:</span>
                        <span class="info-value reference-code">{{ $transaction->to_account_number }}</span>
                    </div>
                @endif
                @if($transaction->to_crypto_address)
                    <div class="info-row">
                        <span class="info-label">{{ __('transfers.crypto_address') }}:</span>
                        <span class="info-value reference-code">{{ $transaction->to_crypto_address }}</span>
                    </div>
                @endif
                @if($transaction->to_swift_code)
                    <div class="info-row">
                        <span class="info-label">{{ __('transfers.swift_code') }}:</span>
                        <span class="info-value">{{ $transaction->to_swift_code }}</span>
                    </div>
                @endif
            </div>
            @endif
        </div>

        <div class="footer">
            <p>{{ __('transfers.ticket_generated_on') }} {{ $generated_at }}</p>
            <p>{{ __('transfers.ticket_footer_note') }}</p>
        </div>
    </div>
</body>
</html>