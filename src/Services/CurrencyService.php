<?php

namespace leventcorapsiz\CommissionCalculator\Services;

use leventcorapsiz\CommissionCalculator\Exceptions\InvalidCurrencyException;
use leventcorapsiz\CommissionCalculator\Models\Amount;
use leventcorapsiz\CommissionCalculator\Models\Currency;

class CurrencyService
{
    /**
     * @var int
     */
    const ARITHMETIC_SCALE = 10;

    /**
     * @var Currency[]
     */
    protected $currencies = [];

    /**
     * @var string
     */
    protected $defaultCurrencySymbol;

    /**
     * CurrencyService constructor.
     *
     * @param string $defaultCurrencySymbol
     */
    public function __construct($defaultCurrencySymbol = 'EUR')
    {
        $this->defaultCurrencySymbol = $defaultCurrencySymbol;
    }

    /**
     * @param array $currencies
     *
     * @return $this
     */
    public function collectCurrenciesFromArray(array $currencies)
    {
        $this->currencies = [];
        foreach ($currencies as $currency) {
            $this->currencies[] = new Currency(...array_values($currency));
        }

        return $this;
    }

    /**
     * @param Amount $amount
     * @param $symbol
     *
     * @return Amount
     */
    public function convert(Amount $amount, $symbol)
    {
        // no need to convert
        if ($amount->getSymbol() === $symbol) {
            return $amount;
        }
        // convert from default currency
        if ($amount->getSymbol() === $this->defaultCurrencySymbol) {
            $rate = $this->getCurrencyRateForSymbol($symbol);

            return new Amount(
                bcmul($rate, $amount->getAmount(), self::ARITHMETIC_SCALE),
                $symbol
            );
        }

        // convert to default currency
        if ($symbol === $this->defaultCurrencySymbol) {
            $rate = $this->getCurrencyRateForSymbol($amount->getSymbol());

            return new Amount(
                bcdiv($amount->getAmount(), $rate, self::ARITHMETIC_SCALE),
                $symbol
            );
        }

        // none of the given currencies have default symbol. convert amount to default currency then recursive call
        $amountInDefaultCurrency = $this->convert($amount, $this->defaultCurrencySymbol);

        return $this->convert($amountInDefaultCurrency, $symbol);
    }

    /**
     * @param Amount $amount
     * @param string $decimalPoint
     * @param string $thousandsSeparator
     *
     * @return int|float
     */
    public function roundAndFormat(Amount $amount, $decimalPoint = '.', $thousandsSeparator = '')
    {
        $precision  = $this->getCurrencyPrecisionForSymbol($amount->getSymbol());
        $multiplier = bcpow(self::ARITHMETIC_SCALE, $precision);
        $newAmount  = bcdiv(
            ceil(bcmul($amount->getAmount(), $multiplier, self::ARITHMETIC_SCALE)),
            $multiplier,
            self::ARITHMETIC_SCALE
        );

        return number_format($newAmount, $precision, $decimalPoint, $thousandsSeparator);
    }

    /**
     * @param Amount $amount
     * @param $percentage
     *
     * @return Amount
     */
    public function getPercentageOfAmount(Amount $amount, $percentage)
    {
        return new Amount(
            bcmul(
                bcdiv($amount->getAmount(), 100, self::ARITHMETIC_SCALE),
                $percentage,
                self::ARITHMETIC_SCALE
            ),
            $amount->getSymbol()
        );
    }

    /**
     * @param Amount $firstAmount
     * @param Amount $secondAmount
     *
     * @return bool
     */
    public function isGreater(Amount $firstAmount, Amount $secondAmount)
    {
        return bccomp(
                $this->convert($firstAmount, $this->defaultCurrencySymbol)->getAmount(),
                $this->convert($secondAmount, $this->defaultCurrencySymbol)->getAmount(),
                self::ARITHMETIC_SCALE
            ) === 1;
    }

    /**
     * @param Amount $firstAmount
     * @param Amount $secondAmount
     * @param $symbol
     *
     * @return Amount
     */
    public function sumAmounts(Amount $firstAmount, Amount $secondAmount, $symbol)
    {
        return new Amount(
            bcadd(
                $this->convert($firstAmount, $symbol)->getAmount(),
                $this->convert($secondAmount, $symbol)->getAmount(),
                self::ARITHMETIC_SCALE
            ),
            $symbol
        );
    }

    /**
     * @param Amount $firstAmount
     * @param Amount $secondAmount
     * @param $currencySymbol
     *
     * @return Amount
     */
    public function subAmount(Amount $firstAmount, Amount $secondAmount, $currencySymbol)
    {
        return new Amount(
            bcsub(
                $this->convert($firstAmount, $currencySymbol)->getAmount(),
                $this->convert($secondAmount, $currencySymbol)->getAmount(),
                self::ARITHMETIC_SCALE
            ),
            $currencySymbol
        );
    }

    /**
     * @param $symbol
     *
     * @return float|int
     */
    private function getCurrencyRateForSymbol($symbol)
    {
        return $this->getCurrencyOfSymbol($symbol)->getRate();
    }

    /**
     * @param $symbol
     *
     * @return int
     */
    private function getCurrencyPrecisionForSymbol($symbol)
    {
        return $this->getCurrencyOfSymbol($symbol)->getPrecision();
    }

    /**
     * @param $symbol
     *
     * @return Currency
     * @throws InvalidCurrencyException
     */
    private function getCurrencyOfSymbol($symbol)
    {
        foreach ($this->currencies as $currency) {
            if ($currency->getSymbol() === $symbol) {
                return $currency;
            }
        }

        throw new InvalidCurrencyException;
    }
}
