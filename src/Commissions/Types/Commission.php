<?php

namespace leventcorapsiz\CommissionCalculator\Commissions\Types;

abstract class Commission
{
    protected $baseAmount;
    protected $currency;

    /**
     * Commission constructor.
     *
     * @param $baseAmount
     * @param $currency
     */
    public function __construct($baseAmount, $currency)
    {
        $this->baseAmount = $baseAmount;
        $this->currency   = $currency;
    }

    /**
     * @param $rate
     * @param null $feeAbleAmount
     *
     * @return float|int
     */
    protected function getFee($rate, $feeAbleAmount = null)
    {
        $amount = $feeAbleAmount ?? $this->baseAmount;
        return ($amount / 100) * $rate;
    }
}
