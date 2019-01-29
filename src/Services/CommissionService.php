<?php

namespace leventcorapsiz\CommissionCalculator\Services;

use leventcorapsiz\CommissionCalculator\Commissions\Types\CashInCommission;
use leventcorapsiz\CommissionCalculator\Commissions\Types\CashOutLegalCommission;
use leventcorapsiz\CommissionCalculator\Commissions\Types\CashOutNaturalCommission;
use leventcorapsiz\CommissionCalculator\Commissions\CommissionTypeInterface;
use leventcorapsiz\CommissionCalculator\Exceptions\InvalidOperationTypeException;
use leventcorapsiz\CommissionCalculator\Exceptions\InvalidUserTypeException;
use leventcorapsiz\CommissionCalculator\Models\Transaction;
use leventcorapsiz\CommissionCalculator\TransactionCollection;

class CommissionService
{
    /**
     * @var TransactionCollection
     */
    protected $transactionCollection;

    /**
     * @var CurrencyService
     */
    protected $currencyService;

    /**
     * CommissionCalculator constructor.
     *
     * @param CurrencyService $currencyService
     */
    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * @param TransactionCollection $collection
     *
     * @return array
     */
    public function calculateFeesFromCollection(TransactionCollection $collection)
    {
        $fees = [];
        foreach ($collection->getTransactions() as $transaction) {
            $commission = $this->generateCommission(
                $this->currencyService,
                $transaction,
                $collection
            );
            $fees[]     = $this->currencyService->roundAndFormat($commission->calculate());
        }

        return $fees;
    }

    /**
     * @param CurrencyService $currencyService
     * @param Transaction $transaction
     * @param TransactionCollection $transactionCollection
     *
     * @return CommissionTypeInterface
     * @throws InvalidOperationTypeException
     * @throws InvalidUserTypeException
     */
    public function generateCommission(
        CurrencyService $currencyService,
        Transaction $transaction,
        TransactionCollection $transactionCollection
    ) {
        switch ($transaction->getOperationType()) {
            case 'cash_in':
                $commission = new CashInCommission($transaction, $currencyService);
                break;
            case 'cash_out':
                switch ($transaction->getUserType()) {
                    case 'natural':
                        $commission = new CashOutNaturalCommission(
                            $transaction,
                            $currencyService,
                            $transactionCollection
                        );
                        break;
                    case 'legal':
                        $commission = new CashOutLegalCommission($transaction, $currencyService);
                        break;
                    default:
                        throw new InvalidUserTypeException;
                }
                break;
            default:
                throw new InvalidOperationTypeException;
        }

        return $commission;
    }
}
