<?php

namespace App\Utilities;

class CurrencyUtility
{
    /** IDR value of 1 unit of each currency (static portfolio rates). */
    public const RATES_TO_IDR = [
        'IDR' => 1,
        'USD' => 16_000,
        'EUR' => 17_500,
        'GBP' => 20_000,
        'SGD' => 12_000,
        'JPY' => 110,
    ];

    public const META = [
        'IDR' => ['symbol' => 'Rp', 'decimals' => 0, 'thousandSep' => '.', 'decimalSep' => ','],
        'USD' => ['symbol' => '$', 'decimals' => 2, 'thousandSep' => ',', 'decimalSep' => '.'],
        'EUR' => ['symbol' => '€', 'decimals' => 2, 'thousandSep' => '.', 'decimalSep' => ','],
        'GBP' => ['symbol' => '£', 'decimals' => 2, 'thousandSep' => ',', 'decimalSep' => '.'],
        'SGD' => ['symbol' => 'S$', 'decimals' => 2, 'thousandSep' => ',', 'decimalSep' => '.'],
        'JPY' => ['symbol' => '¥', 'decimals' => 0, 'thousandSep' => ',', 'decimalSep' => '.'],
    ];

    public static function codes(): array
    {
        return array_keys(self::RATES_TO_IDR);
    }

    public static function isValid(string $currency): bool
    {
        return isset(self::RATES_TO_IDR[$currency]);
    }

    public static function convertPrecise(float|int $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return (float) $amount;
        }

        return ((float) $amount * self::RATES_TO_IDR[$from]) / self::RATES_TO_IDR[$to];
    }

    public static function monthlyEquivalentPrecise(int $price, string $billingCycle): float
    {
        return $billingCycle === 'Yearly' ? $price / 12 : (float) $price;
    }

    public static function formatPrecise(float $amount, string $currency): string
    {
        $meta = self::META[$currency];
        $negative = $amount < 0;
        $amount = abs($amount);

        $raw = rtrim(rtrim(sprintf('%.6F', $amount), '0'), '.');
        [$intPart, $decPart] = array_pad(explode('.', $raw, 2), 2, '');

        $intFormatted = number_format((int) $intPart, 0, '', $meta['thousandSep']);

        $body = $decPart !== ''
            ? $intFormatted.$meta['decimalSep'].$decPart
            : $intFormatted;

        return ($negative ? '-' : '').$meta['symbol'].' '.$body;
    }

    public static function frontendConfig(): array
    {
        return [
            'rates' => self::RATES_TO_IDR,
            'meta' => self::META,
            'codes' => self::codes(),
        ];
    }

    /** @deprecated Use convertPrecise() for display */
    public static function convert(int $amount, string $from, string $to): int
    {
        return (int) round(self::convertPrecise($amount, $from, $to));
    }

    public static function format(int $amount, string $currency): string
    {
        return self::formatPrecise((float) $amount, $currency);
    }

    /** @deprecated Use formatPrecise() */
    public static function formatRupiah(int $amount): string
    {
        return self::formatPrecise((float) $amount, 'IDR');
    }

    public static function monthlyEquivalent(int $price, string $billingCycle): int
    {
        return (int) round(self::monthlyEquivalentPrecise($price, $billingCycle));
    }
}
