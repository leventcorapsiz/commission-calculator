<?php

namespace leventcorapsiz\CommissionCalculator\Commissions\Types;

abstract class Commission
{
    /**
     * @var int
     */
    const ARITHMETIC_SCALE = 10;

    /**
     * @var float|int
     */
    protected $baseAmount;

    /**
     * @var string
     */
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

        return bcmul(
            bcdiv($amount, 100, self::ARITHMETIC_SCALE),
            $rate,
            self::ARITHMETIC_SCALE
        );
    }
}
