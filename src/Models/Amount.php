<?php

namespace leventcorapsiz\CommissionCalculator\Models;

class Amount
{
    /**
     * @var int|float
     */
    protected $amount;

    /**
     * @var string
     */
    protected $symbol;

    /**
     * Amount constructor.
     *
     * @param $amount
     * @param $symbol
     */
    public function __construct($amount, $symbol)
    {
        $this->amount = $amount;
        $this->symbol = $symbol;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @return int|float
     */
    public function getAmount()
    {
        return $this->amount;
    }
}
