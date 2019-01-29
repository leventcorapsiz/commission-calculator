<?php

namespace leventcorapsiz\CommissionCalculator\Tests;

use leventcorapsiz\CommissionCalculator\Commissions\Types\CashOutNaturalCommission;
use leventcorapsiz\CommissionCalculator\Services\CommissionService;
use leventcorapsiz\CommissionCalculator\Commissions\CommissionTypeInterface;
use leventcorapsiz\CommissionCalculator\Commissions\Types\CashInCommission;
use leventcorapsiz\CommissionCalculator\Commissions\Types\CashOutLegalCommission;
use leventcorapsiz\CommissionCalculator\Models\Amount;
use leventcorapsiz\CommissionCalculator\Models\Transaction;
use leventcorapsiz\CommissionCalculator\Services\CurrencyService;
use leventcorapsiz\CommissionCalculator\TransactionCollection;
use PHPUnit\Framework\TestCase;

final class CommissionServiceTest extends TestCase
{
    /**
     * @var CurrencyService
     */
    private $currencyService;

    /**
     * @var TransactionCollection
     */
    private $transactionCollection;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var CommissionTypeInterface
     */
    private $commissionType;

    /**
     * @var Amount
     */
    private $amount;

    public function setUp()
    {
        $this->currencyService       = $this->createMock(CurrencyService::class);
        $this->transactionCollection = $this->createMock(TransactionCollection::class);
        $this->transaction           = $this->createMock(Transaction::class);
        $this->commissionType        = $this->createMock(CashInCommission::class);
        $this->amount                = $this->createMock(Amount::class);
        $this->transaction           = $this->createMock(Transaction::class);
    }

    public function testCalculateFeeFromCollectionWillReturnCorrectSizeOfArray()
    {
        $transactions = [];
        for ($i = 0; $i < rand(1, 10); $i++) {
            $transactions[] = $this->transaction;
        }
        $transactionCount = count($transactions);

        $this->transactionCollection
            ->expects($this->once())
            ->method('getTransactions')
            ->willReturn($transactions);

        $this->currencyService
            ->expects($this->exactly($transactionCount))
            ->method('roundAndFormat')
            ->willReturn('1');

        $this->commissionType
            ->expects($this->exactly($transactionCount))
            ->method('calculate')
            ->willReturn($this->amount);

        $stub = $this->getMockBuilder(CommissionService::class)
            ->setConstructorArgs([$this->currencyService])
            ->setMethods(['generateCommission'])
            ->getMock();

        $stub->method('generateCommission')
            ->willReturn($this->commissionType);

        $this->assertCount($transactionCount, $stub->calculateFeesFromCollection($this->transactionCollection));
    }

    public function testGeneratesCashInCommission()
    {
        $this->transaction
            ->expects($this->once())
            ->method('getOperationType')
            ->willReturn('cash_in');

        $calculator = new CommissionService($this->currencyService);

        $this->assertInstanceOf(
            CashInCommission::class,
            $calculator->generateCommission($this->currencyService, $this->transaction, $this->transactionCollection)
        );
    }

    public function testGeneratesCashOutLegalCommission()
    {
        $this->transaction
            ->expects($this->once())
            ->method('getOperationType')
            ->willReturn('cash_out');

        $this->transaction
            ->expects($this->once())
            ->method('getUserType')
            ->willReturn('legal');

        $calculator = new CommissionService($this->currencyService);

        $this->assertInstanceOf(
            CashOutLegalCommission::class,
            $calculator->generateCommission($this->currencyService, $this->transaction, $this->transactionCollection)
        );
    }

    public function testGeneratesCashOutNaturalCommission()
    {
        $this->transaction
            ->expects($this->once())
            ->method('getOperationType')
            ->willReturn('cash_out');

        $this->transaction
            ->expects($this->once())
            ->method('getUserType')
            ->willReturn('natural');

        $calculator = new CommissionService($this->currencyService);

        $this->assertInstanceOf(
            CashOutNaturalCommission::class,
            $calculator->generateCommission($this->currencyService, $this->transaction, $this->transactionCollection)
        );
    }
}
