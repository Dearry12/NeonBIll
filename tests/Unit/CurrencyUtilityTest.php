<?php

namespace Tests\Unit;

use App\Utilities\CurrencyUtility;
use PHPUnit\Framework\TestCase;

class CurrencyUtilityTest extends TestCase
{
    public function test_converts_idr_to_usd_without_rounding(): void
    {
        $this->assertSame(1.0, CurrencyUtility::convertPrecise(16_000, 'IDR', 'USD'));
        $this->assertSame(4.0625, CurrencyUtility::convertPrecise(65_000, 'IDR', 'USD'));
    }

    public function test_formats_currencies_with_precise_decimals(): void
    {
        $this->assertSame('Rp 54.900', CurrencyUtility::formatPrecise(54900, 'IDR'));
        $this->assertSame('$ 4.0625', CurrencyUtility::formatPrecise(4.0625, 'USD'));
    }
}
