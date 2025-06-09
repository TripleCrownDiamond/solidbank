<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'cryptocurrency_id', 'address', 'coin', 'network', 'balance'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($wallet) {
            if (empty($wallet->address)) {
                $wallet->address = $wallet->generateAddress();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class);
    }

    /**
     * Les groupes d'étapes de transfert associés à ce portefeuille
     */
    public function transferStepGroups()
    {
        return $this->belongsToMany(TransferStepGroup::class, 'wallet_transfer_step_group');
    }

    /**
     * Generate a wallet address based on the cryptocurrency type
     */
    public function generateAddress()
    {
        if (!$this->cryptocurrency_id) {
            return null;
        }

        $crypto = Cryptocurrency::find($this->cryptocurrency_id);
        if (!$crypto) {
            return null;
        }

        switch ($crypto->symbol) {
            case 'BTC':
                return $this->generateBitcoinAddress();
            case 'ETH':
            case 'USDT':
            case 'USDC':
            case 'BNB':
                return $this->generateEthereumAddress();
            case 'LTC':
                return $this->generateLitecoinAddress();
            case 'XRP':
                return $this->generateRippleAddress();
            case 'ADA':
                return $this->generateCardanoAddress();
            case 'DOT':
                return $this->generatePolkadotAddress();
            case 'MATIC':
                return $this->generatePolygonAddress();
            case 'TRX':
                return $this->generateTronAddress();
            default:
                return $this->generateGenericAddress();
        }
    }

    private function generateBitcoinAddress()
    {
        // Generate a realistic Bitcoin address (P2PKH format)
        return '1' . Str::random(33);
    }

    private function generateEthereumAddress()
    {
        // Generate a realistic Ethereum address
        return '0x' . Str::random(40);
    }

    private function generateLitecoinAddress()
    {
        // Generate a realistic Litecoin address
        return 'L' . Str::random(33);
    }

    private function generateRippleAddress()
    {
        // Generate a realistic Ripple address
        return 'r' . Str::random(33);
    }

    private function generateCardanoAddress()
    {
        // Generate a realistic Cardano address
        return 'addr1' . Str::random(98);
    }

    private function generatePolkadotAddress()
    {
        // Generate a realistic Polkadot address
        return '1' . Str::random(47);
    }

    private function generatePolygonAddress()
    {
        // Polygon uses Ethereum-compatible addresses
        return $this->generateEthereumAddress();
    }

    private function generateTronAddress()
    {
        // Generate a realistic Tron address
        return 'T' . Str::random(33);
    }

    private function generateGenericAddress()
    {
        // Generate a generic address
        return Str::random(34);
    }
}
