<?php

namespace leventcorapsiz\CommissionCalculator\Tests\Services;

use PHPUnit\Framework\TestCase;
use leventcorapsiz\CommissionCalculator\Services\CurrencyService;

final class CurrencyServiceTest extends TestCase
{
    public function testConvert()
    {
        $this->assertEquals(
            1.1497,
            CurrencyService::convert('EUR', 1, 'USD')
        );
        $this->assertEquals(
            0.008875936076584575,
            CurrencyService::convert('JPY', 1, 'USD')
        );
    }

    public function testRoundAndFormat()
    {
        $this->assertEquals(
            1005,
            CurrencyService::roundAndFormat('JPY', 1004.2)
        );
        $this->assertEquals(
            5.03,
            CurrencyService::roundAndFormat('USD', 5.023)
        );
    }
}
