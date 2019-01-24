<?php

namespace leventcorapsiz\CommissionCalculator\Tests\Commissions\Types;

use leventcorapsiz\CommissionCalculator\Commissions\Types\CashOutLegalCommission;
use PHPUnit\Framework\TestCase;

final class CashOutLegalCommissionTest extends TestCase
{
    public function testMinimumCommissionFee()
    {
        $commission = new CashOutLegalCommission(1, 'EUR');
        $this->assertEquals($commission::MIN_COMMISSION['fee'], $commission->calculate());
    }

    public function testDefaultCommissionFee()
    {
        $commission = new CashOutLegalCommission(100, 'EUR');
        $this->assertIsFloat($commission::COMMISSION_PERCENTAGE, $commission->calculate());
    }
}
