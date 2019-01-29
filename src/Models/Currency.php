<?php

namespace leventcorapsiz\CommissionCalculator\Models;

class Currency
{
    /**
     * @var string
     */
    protected $symbol;

    /**
     * @var int|float
     */
    protected $rate;

    /**
     * @var int
     */
    protected $precision;

    /**
     * Currency constructor.
     *
     * @param $symbol
     * @param $rate
     * @param $precision
     */
    public function __construct($symbol, $rate, $precision)
    {
        $this->symbol    = $symbol;
        $this->rate      = $rate;
        $this->precision = $precision;
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
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }
}
