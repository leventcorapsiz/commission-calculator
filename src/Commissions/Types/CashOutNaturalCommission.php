<?php

namespace leventcorapsiz\CommissionCalculator\Commissions\Types;

use leventcorapsiz\CommissionCalculator\Services\CurrencyService;
use leventcorapsiz\CommissionCalculator\Services\DateService;
use leventcorapsiz\CommissionCalculator\Transaction;

class CashOutNaturalCommission extends Commission implements CommissionTypeInterface
{
    /**
     * @var float
     */
    const COMMISSION_PERCENTAGE = 0.3;

    /**
     * @var array
     */
    const WEEKLY_FREE_CHARGE_LIMIT = [
        'currency'      => 'EUR',
        'limit'         => 1000,
        'maxOperations' => 3
    ];

    /**
     * @var string
     */
    const OPERATION_TYPE = 'cash_out';

    /**
     * @var string
     */
    protected $transactionDate;

    /**
     * @var Transaction[]
     */
    protected $oldTransactions;

    /**
     * NaturalCashOutCommission constructor.
     *
     * @param $amount
     * @param $currency
     * @param $transactionDate
     * @param $oldTransactions
     */
    public function __construct($amount, $currency, $transactionDate, $oldTransactions)
    {
        $this->transactionDate = $transactionDate;
        $this->oldTransactions = $oldTransactions;
        parent::__construct($amount, $currency);
    }

    /**
     * @return float
     */
    public function calculate()
    {
        $summary = $this->getWeeklyCashOutSummaryOfUser();

        // user has no available free charge limit or allowed for free operation
        if (!$summary['userHasAvailableFreeChargeLimit'] || $summary['maximumOperationLimitReached']) {
            $commission = $this->getFee(self::COMMISSION_PERCENTAGE);
        } else {
            // user has enough limit, free charge
            if ($summary['availableFreeChargeLimit'] >= $this->baseAmount) {
                $commission = 0;
            } else {
                // charge for only exceeded amount
                $exceededAmount = $this->baseAmount - $summary['availableFreeChargeLimit'];
                $commission     = $this->getFee(self::COMMISSION_PERCENTAGE, $exceededAmount);
            }
        }

        return $commission;
    }

    /**
     * @return array
     */
    private function getWeeklyCashOutSummaryOfUser()
    {
        $amount         = 0;
        $operationCount = 0;

        // filter the users transactions in same week and same type
        $oldTransactions = array_filter($this->oldTransactions, function (Transaction $transaction) {
            return DateService::datesAreInSameWeek($transaction->getTransactionDate(), $this->transactionDate)
                && $transaction->getOperationType() === self::OPERATION_TYPE;
        });

        foreach ($oldTransactions as $oldTransaction) {
            $amount += CurrencyService::convert(
                $oldTransaction->getCurrency(),
                $oldTransaction->getAmount(),
                $this->currency
            );
            $operationCount++;
        }

        $availableFreeChargeLimit = CurrencyService::convert(
                self::WEEKLY_FREE_CHARGE_LIMIT['currency'],
                self::WEEKLY_FREE_CHARGE_LIMIT['limit'],
                $this->currency
            ) - $amount;

        $maximumOperationLimitReached    = $operationCount >= self::WEEKLY_FREE_CHARGE_LIMIT['maxOperations'];
        $userHasAvailableFreeChargeLimit = $availableFreeChargeLimit > 0;

        return compact(
            'amount',
            'maximumOperationLimitReached',
            'userHasAvailableFreeChargeLimit',
            'availableFreeChargeLimit'
        );
    }
}
