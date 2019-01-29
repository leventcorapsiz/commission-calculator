<?php

namespace leventcorapsiz\CommissionCalculator\Commissions;

use leventcorapsiz\CommissionCalculator\Models\Amount;
use leventcorapsiz\CommissionCalculator\Models\Transaction;
use leventcorapsiz\CommissionCalculator\Services\CurrencyService;

abstract class Commission
{
    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * @var CurrencyService
     */
    protected $currencyService;

    /**
     * Commission constructor.
     *
     * @param Transaction $transaction
     * @param CurrencyService $currencyService
     */
    public function __construct(Transaction $transaction, CurrencyService $currencyService)
    {
        $this->transaction     = $transaction;
        $this->currencyService = $currencyService;
    }

    /**
     * @param $rate
     * @param null $feeAbleAmount
     *
     * @return Amount
     */
    protected function getFee($rate, $feeAbleAmount = null)
    {
        $amount = $feeAbleAmount ?? $this->transaction->getAmount();

        return $this->currencyService->getPercentageOfAmount($amount, $rate);
    }
}
