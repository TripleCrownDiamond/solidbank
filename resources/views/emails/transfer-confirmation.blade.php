<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('transfers.transfer_confirmed_email_subject') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #667eea;
        }
        .header h1 {
            color: #667eea;
            margin: 0;
            font-size: 24px;
        }
        .success-badge {
            background: #d4edda;
            color: #155724;
            padding: 10px 20px;
            border-radius: 20px;
            display: inline-block;
            font-weight: bold;
            margin: 20px 0;
        }
        .transaction-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #6c757d;
        }
        .detail-value {
            color: #495057;
        }
        .amount-highlight {
            background: #e8f5e8;
            color: #155724;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .ticket-section {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
        }
        .download-button {
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            font-weight: bold;
            margin-top: 10px;
        }
        .download-button:hover {
            background: #5a6fd8;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ __('transfers.transfer_confirmed_email_subject') }}</h1>
            <div class="success-badge">
                ✓ {{ __('transfers.transfer_confirmed') }}
            </div>
        </div>

        <p>{{ __('transfers.dear_user', ['name' => $user->name]) }},</p>
        
        <p>{{ __('transfers.transfer_confirmed_message') }}</p>

        <div class="transaction-details">
            <h3>{{ __('transfers.transaction_details') }}</h3>
            
            <div class="detail-row">
                <span class="detail-label">{{ __('transfers.reference') }}:</span>
                <span class="detail-value">{{ $transaction->reference }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">{{ __('transfers.type') }}:</span>
                <span class="detail-value">{{ __('transfers.types.' . strtolower(str_replace('TRANSFER_', '', $transaction->type))) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">{{ __('transfers.amount') }}:</span>
                <span class="detail-value amount-highlight">{{ $amount }} {{ $currency }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">{{ __('transfers.date') }}:</span>
                <span class="detail-value">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">{{ __('transfers.processed_date') }}:</span>
                <span class="detail-value">{{ $transaction->processed_at ? $transaction->processed_at->format('d/m/Y H:i') : __('transfers.just_now') }}</span>
            </div>

            @if($transaction->description)
            <div class="detail-row">
                <span class="detail-label">{{ __('transfers.description') }}:</span>
                <span class="detail-value">{{ $transaction->description }}</span>
            </div>
            @endif
        </div>

        <div class="ticket-section">
            <h3>{{ __('transfers.transfer_ticket') }}</h3>
            <p>{{ __('transfers.ticket_download_message') }}</p>
            <a href="{{ $ticketUrl }}" class="download-button">
                📄 {{ __('transfers.download_ticket') }}
            </a>
        </div>

        <p>{{ __('transfers.transfer_confirmation_footer') }}</p>

        <div class="footer">
            <p>{{ __('transfers.email_footer_note') }}</p>
            <p>{{ __('transfers.contact_support_if_needed') }}</p>
        </div>
    </div>
</body>
</html>