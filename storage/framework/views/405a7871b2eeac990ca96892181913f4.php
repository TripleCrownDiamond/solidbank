<?php $__env->startSection('title'); ?>
    <?php if($transaction->type === 'DEPOSIT'): ?>
        <?php echo e(__('common.deposit_confirmed_email_subject')); ?>

    <?php elseif($transaction->type === 'WITHDRAWAL'): ?>
        <?php echo e(__('common.withdrawal_confirmed_email_subject')); ?>

    <?php elseif($transaction->type === 'TRANSFER_BANK'): ?>
        <?php echo e(__('transfers.bank_transfer_confirmed_subject')); ?>

    <?php elseif($transaction->type === 'TRANSFER_CRYPTO'): ?>
        <?php echo e(__('transfers.crypto_transfer_confirmed_subject')); ?>

    <?php elseif($transaction->type === 'TRANSFER_EXTERNAL'): ?>
        <?php echo e(__('transfers.external_transfer_confirmed_subject')); ?>

    <?php else: ?>
        <?php echo e(__('common.transaction_confirmed_subject')); ?>

    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div style="text-align: center; max-width: 100%; margin: 0 auto;">
        <h1>
            <?php if($transaction->type === 'DEPOSIT'): ?>
                <?php echo e(__('common.deposit_confirmed_email_subject')); ?>

            <?php elseif($transaction->type === 'WITHDRAWAL'): ?>
                <?php echo e(__('common.withdrawal_confirmed_email_subject')); ?>

            <?php elseif($transaction->type === 'TRANSFER_BANK'): ?>
                <?php echo e(__('transfers.bank_transfer_confirmed_subject')); ?>

            <?php elseif($transaction->type === 'TRANSFER_CRYPTO'): ?>
                <?php echo e(__('transfers.crypto_transfer_confirmed_subject')); ?>

            <?php elseif($transaction->type === 'TRANSFER_EXTERNAL'): ?>
                <?php echo e(__('transfers.external_transfer_confirmed_subject')); ?>

            <?php else: ?>
                <?php echo e(__('common.transaction_confirmed_subject')); ?>

            <?php endif; ?>
        </h1>
        
        <p class="message-success">
            <?php echo __('common.hello'); ?> <strong><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></strong> !
        </p>
        
        <p class="message-success">
            <?php echo $emailMessage; ?>

        </p>
    
        <div class="transaction-details">
            <h3><?php echo e(__('common.transaction_details')); ?></h3>
            
            <div class="detail-row">
                <span class="detail-label"><?php echo e(__('common.reference')); ?> :</span>
                <span class="detail-value"><?php echo e($transaction->reference); ?></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label"><?php echo e(__('common.type')); ?> :</span>
                <span class="detail-value">
                    <?php if($transaction->type === 'DEPOSIT'): ?>
                        <?php echo e(__('common.deposit')); ?>

                    <?php elseif($transaction->type === 'WITHDRAWAL'): ?>
                        <?php echo e(__('common.withdrawal')); ?>

                    <?php elseif($transaction->type === 'TRANSFER_BANK'): ?>
                        <?php echo e(__('transfers.bank_transfer')); ?>

                    <?php elseif($transaction->type === 'TRANSFER_CRYPTO'): ?>
                        <?php echo e(__('transfers.crypto_transfer')); ?>

                    <?php elseif($transaction->type === 'TRANSFER_EXTERNAL'): ?>
                        <?php echo e(__('transfers.external_transfer')); ?>

                    <?php else: ?>
                        <?php echo e(__('common.transaction')); ?>

                    <?php endif; ?>
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label"><?php echo e(__('common.amount')); ?> :</span>
                <span class="amount-value"><?php echo e($amount); ?> <?php echo e($currency); ?></span>
            </div>
        
            <?php if($transaction->account_id): ?>
                <div class="detail-row">
                    <span class="detail-label"><?php echo e(__('common.account')); ?> :</span>
                    <span class="detail-value"><?php echo e($transaction->account->account_number ?? 'N/A'); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if($transaction->wallet_id): ?>
                <div class="detail-row">
                    <span class="detail-label"><?php echo e(__('common.wallet')); ?> :</span>
                    <span class="detail-value"><?php echo e($transaction->wallet->coin ?? 'N/A'); ?> - <?php echo e(substr($transaction->wallet->address ?? '', 0, 10)); ?>...<?php echo e(substr($transaction->wallet->address ?? '', -6)); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if($transaction->description): ?>
                <div class="detail-row">
                    <span class="detail-label"><?php echo e(__('common.reason')); ?> :</span>
                    <span class="detail-value"><?php echo e($transaction->description); ?></span>
                </div>
            <?php endif; ?>
            
            <div class="detail-row">
                <span class="detail-label"><?php echo e(__('common.processing_date')); ?> :</span>
                <span class="detail-value"><?php echo e($transaction->processed_at ? $transaction->processed_at->format('d/m/Y à H:i') : 'N/A'); ?></span>
            </div>
        </div>
        <?php if($transaction->type === 'DEPOSIT'): ?>
            <p class="message-success"><?php echo __('common.balance_credited_message', ['amount' => $amount . ' ' . $currency]); ?></p>
        <?php elseif($transaction->type === 'WITHDRAWAL'): ?>
            <p class="message-success"><?php echo __('common.balance_debited_message', ['amount' => $amount . ' ' . $currency]); ?></p>
        <?php endif; ?>
        
        <p class="message-success"><?php echo e(__('common.transaction_questions_contact')); ?></p>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('emails.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Backup Desktop\projets\solidbank\resources\views/emails/transaction-confirmed.blade.php ENDPATH**/ ?>