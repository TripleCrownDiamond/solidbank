@if(isCryptoEnabled())
<div>
    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-lg rounded-2xl p-6 border border-blue-200/50 dark:border-blue-700/50 shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('crypto.realtime_crypto_refunds') }}</h3>
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('common.live') }}</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-blue-200/50 dark:border-blue-700/50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">{{ __('crypto.wallet_address') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">{{ __('common.amount') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">{{ __('crypto.crypto') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">{{ __('common.status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-200/30 dark:divide-blue-700/30" id="crypto-table-body">
                    @foreach($currentTransactions as $index => $transaction)
                        <tr class="crypto-row opacity-0 transform translate-y-4 transition-all duration-500 ease-out" 
                            style="animation-delay: {{ $index * 0.1 }}s;" 
                            data-transaction-id="{{ $transaction['id'] }}">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-white">
                                {{ substr($transaction['wallet_address'], 0, 20) }}...
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-green-600 dark:text-green-400">
                                {{ $transaction['amount'] }} {{ $transaction['crypto'] }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $transaction['crypto'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $transaction['status']['class'] }}">
                                    {{ $transaction['status']['text'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('crypto.auto_update_message') }}
            </p>
        </div>
    </div>

    <script>
    document.addEventListener('livewire:init', () => {
        // Animation d'entrée pour les nouvelles lignes
        function animateRows() {
            const rows = document.querySelectorAll('.crypto-row');
            rows.forEach((row, index) => {
                setTimeout(() => {
                    row.classList.remove('opacity-0', 'translate-y-4');
                    row.classList.add('opacity-100', 'translate-y-0');
                }, index * 100);
            });
        }
        
        // Animation de sortie
        function animateRowsOut() {
            const rows = document.querySelectorAll('.crypto-row');
            rows.forEach((row, index) => {
                setTimeout(() => {
                    row.classList.add('opacity-0', '-translate-y-4');
                }, index * 50);
            });
        }
        
        // Programmer le changement automatique
        function scheduleNextBatch() {
            setTimeout(() => {
                animateRowsOut();
                setTimeout(() => {
                    @this.loadNextBatch();
                }, 500);
            }, 30000); // 30 secondes
        }
        
        // Écouter l'événement de programmation
        Livewire.on('schedule-next-batch', () => {
            scheduleNextBatch();
        });
        
        // Animation initiale
        setTimeout(() => {
            animateRows();
            scheduleNextBatch();
        }, 100);
        
        // Animation après mise à jour Livewire
        Livewire.hook('morph.updated', () => {
            setTimeout(() => {
                animateRows();
            }, 100);
        });
    });
</script>

<style>
    .crypto-row {
        animation: slideInUp 0.5s ease-out forwards;
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .crypto-row.fade-out {
        animation: slideOutUp 0.3s ease-in forwards;
    }
    
    @keyframes slideOutUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
</style>
</div>
@endif