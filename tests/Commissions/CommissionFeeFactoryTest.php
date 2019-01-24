<?php

namespace leventcorapsiz\CommissionCalculator\Tests\Commissions;

use leventcorapsiz\CommissionCalculator\Commissions\CommissionFeeFactory;
use leventcorapsiz\CommissionCalculator\Commissions\Types\CashInCommission;
use leventcorapsiz\CommissionCalculator\Commissions\Types\CashOutLegalCommission;
use leventcorapsiz\CommissionCalculator\Commissions\Types\CashOutNaturalCommission;
use leventcorapsiz\CommissionCalculator\Exceptions\InvalidOperationTypeException;
use leventcorapsiz\CommissionCalculator\Exceptions\InvalidUserTypeException;
use PHPUnit\Framework\TestCase;

final class CommissionFeeFactoryTest extends TestCase
{
    public function testOperationTypeCannotBeInvalid()
    {
        $this->expectException(InvalidOperationTypeException::class);
        CommissionFeeFactory::generate(
            [],
            'natural',
            'invalid',
            null,
            null,
            null
        );
    }

    public function testUserTypeCannotBeInvalid()
    {
        $this->expectException(InvalidUserTypeException::class);
        CommissionFeeFactory::generate(
            [],
            'invalid',
            'cash_out',
            null,
            null,
            null
        );
    }

    public function testMustReturnCorrectCommissionTypeInstance()
    {
        $this->assertInstanceOf(
            CashOutNaturalCommission::class,
            CommissionFeeFactory::generate(
                [],
                'natural',
                'cash_out',
                null,
                100,
                'EUR'
            )
        );
        $this->assertInstanceOf(
            CashOutLegalCommission::class,
            CommissionFeeFactory::generate(
                [],
                'legal',
                'cash_out',
                null,
                100,
                'EUR'
            )
        );
        $this->assertInstanceOf(
            CashInCommission::class,
            CommissionFeeFactory::generate(
                [],
                'legal',
                'cash_in',
                null,
                100,
                'EUR'
            )
        );
    }
}
