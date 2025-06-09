<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CryptocurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cryptocurrencies = [
            [
                'name' => 'Bitcoin',
                'symbol' => 'BTC',
                'network' => 'Bitcoin',
                'address_format' => '^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$|^bc1[a-z0-9]{39,59}$',
                'address_example' => '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
                'is_active' => true,
            ],
            [
                'name' => 'Ethereum',
                'symbol' => 'ETH',
                'network' => 'Ethereum',
                'address_format' => '^0x[a-fA-F0-9]{40}$',
                'address_example' => '0x742d35Cc6634C0532925a3b8D4C9db96C4b4Df8',
                'is_active' => true,
            ],
            [
                'name' => 'Tether USD',
                'symbol' => 'USDT',
                'network' => 'Ethereum',
                'address_format' => '^0x[a-fA-F0-9]{40}$',
                'address_example' => '0xdAC17F958D2ee523a2206206994597C13D831ec7',
                'is_active' => true,
            ],
            [
                'name' => 'Tether USD',
                'symbol' => 'USDT',
                'network' => 'Tron',
                'address_format' => '^T[A-Za-z1-9]{33}$',
                'address_example' => 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t',
                'is_active' => true,
            ],
            [
                'name' => 'BNB',
                'symbol' => 'BNB',
                'network' => 'Binance Smart Chain',
                'address_format' => '^0x[a-fA-F0-9]{40}$',
                'address_example' => '0xB8c77482e45F1F44dE1745F52C74426C631bDD52',
                'is_active' => true,
            ],
            [
                'name' => 'USD Coin',
                'symbol' => 'USDC',
                'network' => 'Ethereum',
                'address_format' => '^0x[a-fA-F0-9]{40}$',
                'address_example' => '0xA0b86a33E6441b8435b662f98137B4B9c7C8b8B8',
                'is_active' => true,
            ],
            [
                'name' => 'Cardano',
                'symbol' => 'ADA',
                'network' => 'Cardano',
                'address_format' => '^addr1[a-z0-9]{98}$',
                'address_example' => 'addr1qx2fxv2umyhttkxyxp8x0dlpdt3k6cwng5pxj3jhsydzer3n0d3vllmyqwsx5wktcd8cc3sq835lu7drv2xwl2wywfgse35a3x',
                'is_active' => true,
            ],
            [
                'name' => 'Solana',
                'symbol' => 'SOL',
                'network' => 'Solana',
                'address_format' => '^[1-9A-HJ-NP-Za-km-z]{32,44}$',
                'address_example' => '11111111111111111111111111111112',
                'is_active' => true,
            ],
            [
                'name' => 'Polygon',
                'symbol' => 'MATIC',
                'network' => 'Polygon',
                'address_format' => '^0x[a-fA-F0-9]{40}$',
                'address_example' => '0x0000000000000000000000000000000000001010',
                'is_active' => true,
            ],
            [
                'name' => 'Litecoin',
                'symbol' => 'LTC',
                'network' => 'Litecoin',
                'address_format' => '^[LM3][a-km-zA-HJ-NP-Z1-9]{26,33}$|^ltc1[a-z0-9]{39,59}$',
                'address_example' => 'LTC1qw508d6qejxtdg4y5r3zarvary0c5xw7kv8f3t4',
                'is_active' => true,
            ],
            [
                'name' => 'Dogecoin',
                'symbol' => 'DOGE',
                'network' => 'Dogecoin',
                'address_format' => '^D{1}[5-9A-HJ-NP-U]{1}[1-9A-HJ-NP-Za-km-z]{32}$',
                'address_example' => 'DH5yaieqoZN36fDVciNyRueRGvGLR3mr7L',
                'is_active' => true,
            ],
        ];

        DB::table('cryptocurrencies')->insert($cryptocurrencies);
    }
}
