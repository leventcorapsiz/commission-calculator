<?php

namespace leventcorapsiz\CommissionCalculator\Tests\Commissions\Types;

use leventcorapsiz\CommissionCalculator\Commissions\Types\CashOutNaturalCommission;
use leventcorapsiz\CommissionCalculator\Transaction;
use PHPUnit\Framework\TestCase;

final class CashOutNaturalCommissionTest extends TestCase
{
    public function testFreeCharge()
    {
        $commission = new CashOutNaturalCommission(
            1,
            'EUR',
            null,
            []
        );
        $this->assertEquals(0, $commission->calculate());
    }

    public function testDefaultCommission()
    {
        $oldTransactions = [];
        for ($i = 0; $i < 4; $i++) {
            $oldTransactions[] = new Transaction(
                "2019-01-0{$i}",
                1,
                'natural',
                'cash_out',
                '350',
                'EUR'
            );
        }
        $commission = new CashOutNaturalCommission(
            100,
            'EUR',
            '2019-01-05',
            $oldTransactions
        );
        $this->assertEquals(
            CashOutNaturalCommission::COMMISSION_PERCENTAGE,
            $commission->calculate()
        );
    }

    public function testChargeForOnlyExceededAmount()
    {
        $oldTransactions = [];
        for ($i = 0; $i < 4; $i++) {
            $oldTransactions[] = new Transaction(
                "2019-02-2{$i}",
                1,
                'natural',
                'cash_out',
                '300',
                'EUR'
            );
        }
        $commission = new CashOutNaturalCommission(
            200,
            'EUR',
            '2019-02-24',
            $oldTransactions
        );
        $this->assertEquals(
            0.6,
            $commission->calculate()
        );
    }
}
