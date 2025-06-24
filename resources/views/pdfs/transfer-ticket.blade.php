<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('transfers.transfer_ticket') }} - {{ $transaction->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .ticket-title {
            font-size: 20px;
            margin: 10px 0;
            color: #333;
        }
        .ticket-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        .info-value {
            color: #212529;
        }
        .amount {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status.completed {
            background-color: #d4edda;
            color: #155724;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
        .qr-section {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">SolidBank</div>
        <div class="ticket-title">{{ __('transfers.transfer_ticket') }}</div>
        <div>{{ now()->format('d/m/Y H:i:s') }}</div>
    </div>

    <div class="ticket-info">
        <div class="info-row">
            <span class="info-label">{{ __('transfers.transaction_number') }} :</span>
            <span class="info-value">#{{ $transaction->id }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">{{ __('common.type') }} :</span>
            <span class="info-value">{{ __('transfers.transfer') }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">{{ __('common.amount') }} :</span>
            <span class="info-value amount">{{ number_format($transaction->amount, 2) }} {{ $transaction->currency ?: 'EUR' }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">{{ __('transfers.recipient') }} :</span>
            <span class="info-value">{{ $transaction->recipient_name ?: 'N/A' }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">{{ __('transfers.recipient_account') }} :</span>
            <span class="info-value">{{ $transaction->recipient_account ?: 'N/A' }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">{{ __('transfers.reason') }} :</span>
            <span class="info-value">{{ $transaction->description ?: __('transfers.transfer') }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">{{ __('common.created_at') }} :</span>
            <span class="info-value">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">{{ __('common.processed_at') }} :</span>
            <span class="info-value">{{ $transaction->processed_at ? $transaction->processed_at->format('d/m/Y H:i:s') : __('common.pending') }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">{{ __('common.status') }} :</span>
            <span class="info-value">
                <span class="status {{ $transaction->status === 'COMPLETED' ? 'completed' : '' }}">
                    {{ $transaction->status === 'COMPLETED' ? __('common.completed') : __('common.' . strtolower($transaction->status)) }}
                </span>
            </span>
        </div>
        
        @if($account)
        <div class="info-row">
            <span class="info-label">{{ __('common.source_account') }} :</span>
            <span class="info-value">{{ $account->account_number }} ({{ $account->currency }})</span>
        </div>
        @endif
        
        @if($wallet)
        <div class="info-row">
            <span class="info-label">{{ __('common.source_wallet') }} :</span>
            <span class="info-value">{{ $wallet->cryptocurrency->name }} ({{ $wallet->cryptocurrency->symbol }})</span>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>{{ __('transfers.ticket_proof_message') }}</p>
        <p>{{ __('transfers.keep_for_records') }}</p>
        <p><strong>SolidBank</strong> - {{ __('common.trusted_financial_partner') }}</p>
    </div>
</body>
</html>