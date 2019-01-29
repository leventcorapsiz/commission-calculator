<?php

namespace leventcorapsiz\CommissionCalculator\Tests\Commissions\Types;

use leventcorapsiz\CommissionCalculator\Commissions\Types\CashOutNaturalCommission;
use leventcorapsiz\CommissionCalculator\Models\Amount;
use leventcorapsiz\CommissionCalculator\Models\Transaction;
use leventcorapsiz\CommissionCalculator\Services\CurrencyService;
use leventcorapsiz\CommissionCalculator\TransactionCollection;
use PHPUnit\Framework\TestCase;

final class CashOutNaturalCommissionTest extends TestCase
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
     * @var TransactionCollection
     */
    private $transactionCollection;

    /**
     * @var Amount
     */
    private $amount;

    public function setUp()
    {
        $this->currencyService       = $this->createMock(CurrencyService::class);
        $this->transaction           = $this->createMock(Transaction::class);
        $this->transactionCollection = $this->createMock(TransactionCollection::class);
        $this->amount                = $this->createMock(Amount::class);
    }

    public function testWillReturnAmount()
    {
        $this->transaction
            ->expects($this->atLeastOnce())
            ->method('getAmount')
            ->willReturn($this->amount);

        $this->transactionCollection
            ->expects($this->atLeastOnce())
            ->method('getTransactions')
            ->willReturn([$this->transaction]);

        $this->currencyService
            ->expects($this->atLeastOnce())
            ->method('subAmount')
            ->willReturn($this->amount);

        $this->currencyService
            ->expects($this->atLeastOnce())
            ->method('getPercentageOfAmount')
            ->willReturn($this->amount);

        $commission = new CashOutNaturalCommission($this->transaction,
            $this->currencyService,
            $this->transactionCollection
        );

        $commission->calculate();

        $this->assertInstanceOf(
            Amount::class,
            $commission->calculate()
        );
    }
}
