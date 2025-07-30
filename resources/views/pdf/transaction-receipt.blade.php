<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('common.transaction_receipt') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            color: #333;
            line-height: 1.4;
            font-size: 12px;
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 15px;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        .bank-info {
            text-align: center;
            margin-bottom: 15px;
        }
        .bank-name {
            font-size: 18px;
            font-weight: bold;
            color: #3B82F6;
            margin-bottom: 8px;
        }
        .bank-details {
            font-size: 10px;
            color: #666;
        }
        .document-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0 15px 0;
            color: #1E40AF;
        }
        .transaction-info {
            background-color: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: left;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            padding: 3px 0;
            border-bottom: 1px dotted #ddd;
            font-size: 11px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .amount-section {
            background-color: #e3f2fd;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin: 15px 0;
        }
        .amount {
            font-size: 18px;
            font-weight: bold;
            color: #1565c0;
        }
        .user-info {
            margin-top: 15px;
            padding: 8px;
            background-color: #f5f5f5;
            border-radius: 4px;
            text-align: left;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 15px;
        }
        .user-info h3 {
            font-size: 12px;
            margin: 0 0 8px 0;
            color: #333;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .type-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .type-deposit {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .type-withdrawal {
            background-color: #f8d7da;
            color: #721c24;
        }
        .type-transfer {
            background-color: #e2e3e5;
            color: #383d41;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 15px 0 10px 0;
            color: #1E40AF;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="bank-info">
            <img src="{{ public_path('img/logo_blue.svg') }}" alt="{{ $bank_name }}" class="logo">
            <div class="bank-name">{{ $bank_name }}</div>
            <div class="bank-details">
                {{ $bank_address }}<br>
                Email: {{ $bank_email }}
            </div>
        </div>
    </div>
 
     <div class="document-title">
        {{ strtoupper(__('common.transaction_receipt')) }}
    </div>

    <div class="section-title">
        {{ __('common.transaction_details') }}
    </div>

    <div class="transaction-info">
        <div class="info-row">
            <span class="info-label">{{ __('common.transaction_reference') }}:</span>
            <span class="info-value">{{ $reference }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('common.transaction_date') }}:</span>
            <span class="info-value">{{ $date }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('common.transaction_type') }}:</span>
            <span class="info-value">
                <span class="type-badge 
                    @if($transaction->type === 'DEPOSIT') type-deposit
                    @elseif($transaction->type === 'WITHDRAWAL') type-withdrawal
                    @else type-transfer
                    @endif">
                    @if($transaction->type === 'DEPOSIT')
                        {{ __('common.transaction_type_deposit') }}
                    @elseif($transaction->type === 'WITHDRAWAL')
                        {{ __('common.transaction_type_withdrawal') }}
                    @elseif($transaction->type === 'TRANSFER_BANK')
                        {{ __('common.transaction_type_transfer_bank') }}
                    @elseif($transaction->type === 'TRANSFER_CRYPTO')
                        {{ __('common.transaction_type_transfer_crypto') }}
                    @elseif($transaction->type === 'TRANSFER_EXTERNAL')
                        {{ __('common.transaction_type_transfer_external') }}
                    @else
                        {{ $transaction->type }}
                    @endif
                </span>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('common.transaction_status') }}:</span>
            <span class="info-value">
                <span class="status-badge 
                    @if($transaction->status === 'COMPLETED') status-completed
                    @elseif($transaction->status === 'PENDING') status-pending
                    @else status-cancelled
                    @endif">
                    @if($transaction->status === 'COMPLETED')
                        {{ __('common.transaction_status_completed') }}
                    @elseif($transaction->status === 'PENDING')
                        {{ __('common.transaction_status_pending') }}
                    @elseif($transaction->status === 'CANCELLED')
                        {{ __('common.transaction_status_cancelled') }}
                    @else
                        {{ $transaction->status }}
                    @endif
                </span>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('common.transaction_description') }}:</span>
            <span class="info-value">{{ $transaction->description ?: 'N/A' }}</span>
        </div>
        
        @if($transaction->account)
        <div class="info-row">
            <span class="info-label">{{ __('common.account') }}:</span>
            <span class="info-value">{{ $transaction->account->account_number }} ({{ $transaction->account->currency }})</span>
        </div>
        @endif
        
        @if($transaction->wallet)
        <div class="info-row">
            <span class="info-label">{{ __('common.wallet') }}:</span>
            <span class="info-value">{{ substr($transaction->wallet->address, 0, 10) }}...{{ substr($transaction->wallet->address, -8) }}</span>
        </div>
        @endif
    </div>

    <div class="amount-section">
        <div style="margin-bottom: 5px; font-size: 11px; color: #555;">
            @if($transaction->type === 'DEPOSIT')
                {{ __('common.amount_credited') }}
            @elseif($transaction->type === 'WITHDRAWAL')
                {{ __('common.amount_debited') }}
            @else
                {{ __('common.amount_transferred') }}
            @endif
        </div>
        <div class="amount">
            @if($transaction->type === 'DEPOSIT')+@elseif($transaction->type === 'WITHDRAWAL')-@endif{{ $amount }} {{ $currency }}
        </div>
    </div>

    <div class="user-info">
        <h3 style="margin-top: 0; color: #333;">{{ __('common.customer_information') }}</h3>
        <div class="info-row">
            <span class="info-label">{{ __('common.full_name') }}:</span>
            <span class="info-value">{{ $user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('common.email') }}:</span>
            <span class="info-value">{{ $user->email }}</span>
        </div>
        @if($user->phone)
        <div class="info-row">
            <span class="info-label">{{ __('common.phone_number') }}:</span>
            <span class="info-value">{{ $user->phone }}</span>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>{{ __('common.document_generated_automatically') }} {{ now()->format('d/m/Y à H:i') }}</p>
        <p>{{ $bank_name }} - {{ __('common.all_rights_reserved') }}</p>
        <p style="font-size: 10px; margin-top: 15px;">
            {{ __('common.receipt_certification') }}<br>
            {{ __('common.customer_service_contact') }} {{ $bank_email }}
        </p>
    </div>
</body>
</html>