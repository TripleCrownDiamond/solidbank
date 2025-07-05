<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('transfers.transfer_notification') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: var(--bg-light);
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: var(--color-white);
            padding: 30px;
            border: 1px solid var(--border-light);
        }
        .footer {
            background-color: var(--bg-light);
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 14px;
            color: var(--text-secondary);
        }
        .transaction-details {
            background-color: var(--bg-light);
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-light);
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .detail-label {
            font-weight: bold;
            color: var(--text-primary);
        }
        .detail-value {
            color: var(--text-primary);
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .alert-info {
            background-color: var(--info-bg);
                border: 1px solid var(--info-border);
            color: var(--info-text);
        }
        .alert-warning {
            background-color: var(--warning-bg);
            border: 1px solid var(--warning-border);
            color: var(--warning-text);
        }
        .alert-success {
            background-color: var(--success-bg);
            border: 1px solid var(--success-border);
            color: var(--success-text);
        }
        .alert-danger {
            background-color: var(--error-bg);
            border: 1px solid var(--error-border);
            color: var(--error-text);
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: var(--brand-primary);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
        }
        .step-info {
            background-color: var(--info-bg);
            border-left: 4px solid var(--brand-primary);
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h2>{{ __('transfers.transfer_notification') }}</h2>
    </div>

    <div class="content">
        <p>{{ __('common.hello') }} {{ $user->name }},</p>

        @if($notificationType === 'transfer_created')
            <div class="alert alert-info">
                <strong>{{ __('transfers.transfer_created') }}</strong><br>
                {{ __('transfers.transfer_created_message') }}
            </div>
        @elseif($notificationType === 'transfer_completed')
            <div class="alert alert-success">
                <strong>{{ __('transfers.transfer_completed') }}</strong><br>
                {{ __('transfers.transfer_completed_message') }}
            </div>
        @elseif($notificationType === 'transfer_blocked')
            <div class="alert alert-warning">
                <strong>{{ __('transfers.transfer_blocked_at_step', ['step' => $stepTitle]) }}</strong><br>
                {{ __('transfers.transfer_blocked_message') }}
            </div>
            
            @if($stepTitle && $stepDescription)
                <div class="step-info">
                    <h3>{{ $stepTitle }}</h3>
                    <p>{{ $stepDescription }}</p>
                </div>
            @endif
        @elseif($notificationType === 'transfer_received')
            <div class="alert alert-success">
                <strong>{{ __('transfers.transfer_received') }}</strong><br>
                {{ __('transfers.transfer_received_message') }}
            </div>
        @elseif($notificationType === 'transfer_cancelled')
            <div class="alert alert-danger">
                <strong>{{ __('transfers.transfer_cancelled') }}</strong><br>
                {{ __('transfers.transfer_cancelled_message') }}
            </div>
        @elseif($notificationType === 'step_validation_required')
            <div class="alert alert-info">
                <strong>{{ __('transfers.step_validation_required') }}</strong><br>
                {{ __('transfers.step_validation_required_message') }}
            </div>
        @endif

        <div class="transaction-details">
            <h3>{{ __('transfers.transaction_details') }}</h3>
            
            <div class="detail-row">
                <span class="detail-label">{{ __('transfers.reference') }}:</span>
                <span class="detail-value">{{ $transaction->reference }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">{{ __('transfers.amount') }}:</span>
                <span class="detail-value">{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">{{ __('transfers.type') }}:</span>
                <span class="detail-value">
                    @if($transaction->type === 'transfer')
                        @if($transaction->to_account_id || isset($transaction->external_crypto_info['recipient_wallet_id']))
                            {{ __('transfers.internal_transfer') }}
                        @else
                            {{ __('transfers.external_transfer') }}
                        @endif
                    @else
                        {{ ucfirst($transaction->type) }}
                    @endif
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">{{ __('transfers.status') }}:</span>
                <span class="detail-value">
                    @switch($transaction->status)
                        @case('PENDING')
                            {{ __('transfers.pending') }}
                            @break
                        @case('COMPLETED')
                            {{ __('transfers.completed') }}
                            @break
                        @case('BLOCKED')
                            {{ __('transfers.blocked') }}
                            @break
                        @case('CANCELLED')
                            {{ __('transfers.cancelled') }}
                            @break
                        @case('FAILED')
                            {{ __('transfers.failed') }}
                            @break
                        @default
                            {{ $transaction->status }}
                    @endswitch
                </span>
            </div>
            
            @if($transaction->description)
                <div class="detail-row">
                    <span class="detail-label">{{ __('transfers.reason') }}:</span>
                    <span class="detail-value">{{ $transaction->description }}</span>
                </div>
            @endif
            
            <div class="detail-row">
                <span class="detail-label">{{ __('transfers.date') }}:</span>
                <span class="detail-value">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        @if($notificationType === 'transfer_blocked' || $notificationType === 'step_validation_required')
            <p>{{ __('transfers.login_to_continue') }}</p>
            <a href="{{ url('/dashboard') }}" class="btn">{{ __('transfers.access_dashboard') }}</a>
        @endif

        <p>{{ __('transfers.email_footer_message') }}</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('common.all_rights_reserved') }}</p>
    </div>
</body>
</html>