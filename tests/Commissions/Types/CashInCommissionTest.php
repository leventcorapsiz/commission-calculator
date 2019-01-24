<?php

namespace leventcorapsiz\CommissionCalculator\Tests\Commissions\Types;

use leventcorapsiz\CommissionCalculator\Commissions\Types\CashInCommission;
use PHPUnit\Framework\TestCase;

final class CashInCommissionTest extends TestCase
{
    public function testMaximumCommissionFee()
    {
        $commission = new CashInCommission(100000000, 'EUR');
        $this->assertEquals($commission::MAX_COMMISSION['fee'], $commission->calculate());
    }

    public function testDefaultCommissionFee()
    {
        $commission = new CashInCommission(100, 'EUR');
        $this->assertEquals($commission::COMMISSION_PERCENTAGE, $commission->calculate());
    }
}
