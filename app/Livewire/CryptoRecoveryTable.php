<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use App\Models\Cryptocurrency;

class CryptoRecoveryTable extends Component
{
    public $currentTransactions = [];
    public $allTransactions = [];
    public $currentIndex = 0;
    public $isAnimating = false;

    public function mount()
    {
        $this->generateTransactions();
        $this->loadNextBatch();
    }

    public function generateTransactions()
    {
        // Récupérer les cryptomonnaies actives de la base de données
        $activeCryptos = Cryptocurrency::active()->pluck('symbol')->toArray();
        
        // Fallback vers une liste par défaut si aucune crypto n'est trouvée
        $cryptos = !empty($activeCryptos) ? $activeCryptos : ['BTC', 'ETH', 'USDT', 'BNB', 'ADA', 'XRP', 'SOL', 'DOT', 'DOGE', 'AVAX', 'MATIC', 'LTC', 'LINK', 'UNI', 'ATOM'];
        $statuses = [
            ['status' => 'completed', 'class' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400', 'text' => 'Remboursé'],
            ['status' => 'pending', 'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400', 'text' => 'En cours'],
            ['status' => 'processing', 'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400', 'text' => 'Traitement']
        ];

        $this->allTransactions = collect(range(1, 150))->map(function ($i) use ($cryptos, $statuses) {
            $crypto = $cryptos[array_rand($cryptos)];
            $status = $statuses[array_rand($statuses)];
            $amount = $this->generateCryptoAmount($crypto);
            
            return [
                'id' => $i,
                'wallet_address' => $this->generateWalletAddress($crypto),
                'amount' => $amount,
                'crypto' => $crypto,
                'status' => $status,
                'date' => now()->subDays(rand(1, 365))->format('d/m/Y'),
                'transaction_id' => strtoupper(bin2hex(random_bytes(16)))
            ];
        })->toArray();
    }

    private function generateWalletAddress($crypto)
    {
        // Essayer de récupérer l'exemple d'adresse de la base de données
        $cryptocurrency = Cryptocurrency::where('symbol', $crypto)->where('is_active', true)->first();
        
        if ($cryptocurrency && $cryptocurrency->address_example) {
            // Générer une adresse basée sur l'exemple
            return $this->generateAddressFromExample($cryptocurrency->address_example, $crypto);
        }
        
        // Fallback vers la génération manuelle
        switch ($crypto) {
            case 'BTC':
                return '1' . strtoupper(bin2hex(random_bytes(16)));
            case 'ETH':
            case 'USDT':
            case 'USDC':
            case 'BNB':
            case 'MATIC':
                return '0x' . strtoupper(bin2hex(random_bytes(20)));
            case 'ADA':
                return 'addr1' . strtolower(bin2hex(random_bytes(49)));
            case 'SOL':
                return base58_encode(random_bytes(32));
            case 'LTC':
                return 'LTC1' . strtolower(bin2hex(random_bytes(20)));
            case 'DOGE':
                return 'D' . strtoupper(bin2hex(random_bytes(16)));
            default:
                return strtoupper(bin2hex(random_bytes(20)));
        }
    }
    
    private function generateAddressFromExample($example, $crypto)
    {
        // Générer une adresse similaire à l'exemple mais différente
        switch ($crypto) {
            case 'BTC':
                if (str_starts_with($example, 'bc1')) {
                    return 'bc1' . strtolower(bin2hex(random_bytes(20)));
                } else {
                    return '1' . strtoupper(bin2hex(random_bytes(16)));
                }
            case 'ETH':
            case 'USDT':
            case 'USDC':
            case 'BNB':
            case 'MATIC':
                return '0x' . strtoupper(bin2hex(random_bytes(20)));
            case 'ADA':
                return 'addr1' . strtolower(bin2hex(random_bytes(49)));
            case 'SOL':
                return substr(str_shuffle('123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz'), 0, 44);
            case 'LTC':
                if (str_starts_with($example, 'ltc1')) {
                    return 'ltc1' . strtolower(bin2hex(random_bytes(20)));
                } else {
                    return 'L' . strtoupper(bin2hex(random_bytes(16)));
                }
            case 'DOGE':
                return 'D' . strtoupper(bin2hex(random_bytes(16)));
            default:
                return strtoupper(bin2hex(random_bytes(20)));
        }
    }

    private function generateCryptoAmount($crypto)
    {
        switch ($crypto) {
            case 'BTC':
                return number_format(rand(1, 500) / 100, 4); // 0.01 à 5 BTC
            case 'ETH':
                return number_format(rand(10, 2000) / 100, 3); // 0.1 à 20 ETH
            case 'USDT':
            case 'USDC':
                return number_format(rand(100, 10000), 2); // 100 à 10,000 USDT/USDC
            case 'BNB':
                return number_format(rand(50, 1000) / 10, 2); // 5 à 100 BNB
            case 'ADA':
            case 'XRP':
            case 'DOGE':
                return number_format(rand(100, 50000), 0); // 100 à 50,000 tokens
            case 'SOL':
            case 'DOT':
            case 'AVAX':
            case 'MATIC':
            case 'LTC':
            case 'LINK':
            case 'UNI':
            case 'ATOM':
                return number_format(rand(10, 1000) / 10, 2); // 1 à 100 tokens
            default:
                return number_format(rand(10, 1000) / 10, 2);
        }
    }

    public function loadNextBatch()
    {
        $this->isAnimating = true;
        
        // Prendre les 10 prochaines transactions
        $nextBatch = array_slice($this->allTransactions, $this->currentIndex, 10);
        
        if (empty($nextBatch)) {
            // Recommencer depuis le début
            $this->currentIndex = 0;
            $nextBatch = array_slice($this->allTransactions, 0, 10);
        }
        
        $this->currentTransactions = $nextBatch;
        $this->currentIndex += 10;
        
        // Programmer le prochain changement
        $this->dispatch('schedule-next-batch');
        
        $this->isAnimating = false;
    }

    public function render()
    {
        return view('livewire.crypto-recovery-table');
    }
}