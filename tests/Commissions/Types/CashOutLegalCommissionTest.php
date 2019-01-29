<?php

namespace leventcorapsiz\CommissionCalculator\Tests\Commissions\Types;

use leventcorapsiz\CommissionCalculator\Commissions\Types\CashOutLegalCommission;
use leventcorapsiz\CommissionCalculator\Models\Amount;
use leventcorapsiz\CommissionCalculator\Models\Transaction;
use leventcorapsiz\CommissionCalculator\Services\CurrencyService;
use PHPUnit\Framework\TestCase;

final class CashOutLegalCommissionTest extends TestCase
{
    /**
     * @var CurrencyService
     */
    private $currencyService;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var Amount
     */
    private $amount;

    public function setUp()
    {
        $this->currencyService = $this->createMock(CurrencyService::class);
        $this->transaction     = $this->createMock(Transaction::class);
        $this->amount          = $this->createMock(Amount::class);
    }

    public function testWillReturnAmount()
    {
        $this->transaction
            ->expects($this->atLeastOnce())
            ->method('getAmount')
            ->willReturn($this->amount);

        $this->currencyService
            ->expects($this->atLeastOnce())
            ->method('isGreater')
            ->willReturn($this->amount);

        $this->currencyService
            ->expects($this->atLeastOnce())
            ->method('getPercentageOfAmount')
            ->willReturn($this->amount);

        $commission = new CashOutLegalCommission($this->transaction, $this->currencyService);
        $commission->calculate();

        $this->assertInstanceOf(
            Amount::class,
            $commission->calculate()
        );
    }
}
