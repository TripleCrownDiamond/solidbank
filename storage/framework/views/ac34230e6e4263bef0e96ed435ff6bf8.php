<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(__('common.transaction_receipt')); ?></title>
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
            <img src="<?php echo e(public_path('img/logo_blue.svg')); ?>" alt="<?php echo e($bank_name); ?>" class="logo">
            <div class="bank-name"><?php echo e($bank_name); ?></div>
            <div class="bank-details">
                <?php echo e($bank_address); ?><br>
                Email: <?php echo e($bank_email); ?>

            </div>
        </div>
    </div>
 
     <div class="document-title">
        <?php echo e(strtoupper(__('common.transaction_receipt'))); ?>

    </div>

    <div class="section-title">
        <?php echo e(__('common.transaction_details')); ?>

    </div>

    <div class="transaction-info">
        <div class="info-row">
            <span class="info-label"><?php echo e(__('common.transaction_reference')); ?>:</span>
            <span class="info-value"><?php echo e($reference); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label"><?php echo e(__('common.transaction_date')); ?>:</span>
            <span class="info-value"><?php echo e($date); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label"><?php echo e(__('common.transaction_type')); ?>:</span>
            <span class="info-value">
                <span class="type-badge 
                    <?php if($transaction->type === 'DEPOSIT'): ?> type-deposit
                    <?php elseif($transaction->type === 'WITHDRAWAL'): ?> type-withdrawal
                    <?php else: ?> type-transfer
                    <?php endif; ?>">
                    <?php if($transaction->type === 'DEPOSIT'): ?>
                        <?php echo e(__('common.transaction_type_deposit')); ?>

                    <?php elseif($transaction->type === 'WITHDRAWAL'): ?>
                        <?php echo e(__('common.transaction_type_withdrawal')); ?>

                    <?php elseif($transaction->type === 'TRANSFER_BANK'): ?>
                        <?php echo e(__('common.transaction_type_transfer_bank')); ?>

                    <?php elseif($transaction->type === 'TRANSFER_CRYPTO'): ?>
                        <?php echo e(__('common.transaction_type_transfer_crypto')); ?>

                    <?php elseif($transaction->type === 'TRANSFER_EXTERNAL'): ?>
                        <?php echo e(__('common.transaction_type_transfer_external')); ?>

                    <?php else: ?>
                        <?php echo e($transaction->type); ?>

                    <?php endif; ?>
                </span>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label"><?php echo e(__('common.transaction_status')); ?>:</span>
            <span class="info-value">
                <span class="status-badge 
                    <?php if($transaction->status === 'COMPLETED'): ?> status-completed
                    <?php elseif($transaction->status === 'PENDING'): ?> status-pending
                    <?php else: ?> status-cancelled
                    <?php endif; ?>">
                    <?php if($transaction->status === 'COMPLETED'): ?>
                        <?php echo e(__('common.transaction_status_completed')); ?>

                    <?php elseif($transaction->status === 'PENDING'): ?>
                        <?php echo e(__('common.transaction_status_pending')); ?>

                    <?php elseif($transaction->status === 'CANCELLED'): ?>
                        <?php echo e(__('common.transaction_status_cancelled')); ?>

                    <?php else: ?>
                        <?php echo e($transaction->status); ?>

                    <?php endif; ?>
                </span>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label"><?php echo e(__('common.transaction_description')); ?>:</span>
            <span class="info-value"><?php echo e($transaction->description ?: 'N/A'); ?></span>
        </div>
        
        <?php if($transaction->account): ?>
        <div class="info-row">
            <span class="info-label"><?php echo e(__('common.account')); ?>:</span>
            <span class="info-value"><?php echo e($transaction->account->account_number); ?> (<?php echo e($transaction->account->currency); ?>)</span>
        </div>
        <?php endif; ?>
        
        <?php if($transaction->wallet): ?>
        <div class="info-row">
            <span class="info-label"><?php echo e(__('common.wallet')); ?>:</span>
            <span class="info-value"><?php echo e(substr($transaction->wallet->address, 0, 10)); ?>...<?php echo e(substr($transaction->wallet->address, -8)); ?></span>
        </div>
        <?php endif; ?>
    </div>

    <div class="amount-section">
        <div style="margin-bottom: 5px; font-size: 11px; color: #555;">
            <?php if($transaction->type === 'DEPOSIT'): ?>
                <?php echo e(__('common.amount_credited')); ?>

            <?php elseif($transaction->type === 'WITHDRAWAL'): ?>
                <?php echo e(__('common.amount_debited')); ?>

            <?php else: ?>
                <?php echo e(__('common.amount_transferred')); ?>

            <?php endif; ?>
        </div>
        <div class="amount">
            <?php if($transaction->type === 'DEPOSIT'): ?>+<?php elseif($transaction->type === 'WITHDRAWAL'): ?>-<?php endif; ?><?php echo e($amount); ?> <?php echo e($currency); ?>

        </div>
    </div>

    <div class="user-info">
        <h3 style="margin-top: 0; color: #333;"><?php echo e(__('common.customer_information')); ?></h3>
        <div class="info-row">
            <span class="info-label"><?php echo e(__('common.full_name')); ?>:</span>
            <span class="info-value"><?php echo e($user->name); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label"><?php echo e(__('common.email')); ?>:</span>
            <span class="info-value"><?php echo e($user->email); ?></span>
        </div>
        <?php if($user->phone): ?>
        <div class="info-row">
            <span class="info-label"><?php echo e(__('common.phone_number')); ?>:</span>
            <span class="info-value"><?php echo e($user->phone); ?></span>
        </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p><?php echo e(__('common.document_generated_automatically')); ?> <?php echo e(now()->format('d/m/Y à H:i')); ?></p>
        <p><?php echo e($bank_name); ?> - <?php echo e(__('common.all_rights_reserved')); ?></p>
        <p style="font-size: 10px; margin-top: 15px;">
            <?php echo e(__('common.receipt_certification')); ?><br>
            <?php echo e(__('common.customer_service_contact')); ?> <?php echo e($bank_email); ?>

        </p>
    </div>
</body>
</html><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/pdf/transaction-receipt.blade.php ENDPATH**/ ?>