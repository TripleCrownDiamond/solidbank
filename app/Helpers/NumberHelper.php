<?php

namespace App\Helpers;

class NumberHelper
{
    /**
     * Format a number to a human-readable format with suffixes
     * 
     * @param float $number The number to format
     * @param int $decimals Number of decimal places
     * @param bool $forceDecimals Force showing decimals even for whole numbers
     * @return string Formatted number with suffix
     */
    public static function formatCompact($number, $decimals = 1, $forceDecimals = false)
    {
        $abs = abs($number);
        $sign = $number < 0 ? '-' : '';
        
        if ($abs >= 1000000000) {
            $formatted = $abs / 1000000000;
            $suffix = 'B';
        } elseif ($abs >= 1000000) {
            $formatted = $abs / 1000000;
            $suffix = 'M';
        } elseif ($abs >= 1000) {
            $formatted = $abs / 1000;
            $suffix = 'K';
        } else {
            // For numbers less than 1000, show up to 2 decimal places if needed
            if ($abs < 1 && $abs > 0) {
                return $sign . number_format($abs, 8);
            }
            return $sign . number_format($abs, $forceDecimals ? $decimals : ($abs == floor($abs) ? 0 : min($decimals, 2)));
        }
        
        // Remove unnecessary decimal places
        if (!$forceDecimals && $formatted == floor($formatted)) {
            $formatted = floor($formatted);
            return $sign . $formatted . $suffix;
        }
        
        return $sign . number_format($formatted, $decimals) . $suffix;
    }
    
    /**
     * Format a cryptocurrency amount with appropriate precision
     * 
     * @param float $amount The amount to format
     * @param string $symbol The cryptocurrency symbol
     * @return string Formatted amount
     */
    public static function formatCrypto($amount, $symbol = '')
    {
        // For very small amounts, show more decimal places
        if (abs($amount) < 0.001) {
            $formatted = number_format($amount, 8);
        } elseif (abs($amount) < 1) {
            $formatted = number_format($amount, 6);
        } elseif (abs($amount) < 1000) {
            $formatted = number_format($amount, 4);
        } else {
            $formatted = self::formatCompact($amount, 2);
        }
        
        return $formatted . ($symbol ? ' ' . $symbol : '');
    }
    
    /**
     * Format a fiat currency amount
     * 
     * @param float $amount The amount to format
     * @param string $currency The currency code
     * @param bool $compact Whether to use compact format
     * @return string Formatted amount
     */
    public static function formatCurrency($amount, $currency = '', $compact = false)
    {
        if ($compact && abs($amount) >= 1000) {
            $formatted = self::formatCompact($amount, 1);
        } else {
            $formatted = number_format($amount, 2);
        }
        
        return $formatted . ($currency ? ' ' . $currency : '');
    }
}