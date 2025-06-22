<div class="bg-white dark:bg-gray-800 dark:border dark:border-gray-700 rounded-2xl shadow-xl p-8 mb-8">
    <x-transfer.circular-progress 
        :progress="$progress" 
        :statusMessage="$statusMessage" 
        :isTransferStarted="$isTransferStarted" 
    />
</div>