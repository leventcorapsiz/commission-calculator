<?php

namespace leventcorapsiz\CommissionCalculator\Services;

class CurrencyService
{
    const EUR_JPY_RATE = 129.53;
    const EUR_USD_RATE = 1.1497;
    const PRECISIONS = [
        'EUR' => 2,
        'USD' => 2,
        'JPY' => 0
    ];

    /**
     * @param $currency
     * @param $amount
     * @param $convertTo
     *
     * @return float
     */
    public static function convert($currency, $amount, $convertTo)
    {
        if ($currency === $convertTo) {
            return $amount;
        }
        $methodName = 'convert' . strtoupper($currency) . 'To' . strtoupper($convertTo);

        return self::$methodName($amount);
    }

    /**
     * @param $currency
     * @param $amount
     *
     * @return string
     */
    public static function roundAndFormat($currency, $amount)
    {
        $precision = self::PRECISIONS[$currency];
        $multiplier = pow(10, $precision);

        $newAmount = ceil($amount * $multiplier) / $multiplier;

        return number_format($newAmount, $precision, '.', '');
    }

    /**
     * @param $eur
     *
     * @return float
     */
    private static function convertEURtoUSD($eur)
    {
        return $eur * self::EUR_USD_RATE;
    }

    /**
     * @param $usd
     *
     * @return float
     */
    private static function convertUSDtoEUR($usd)
    {
        return $usd / self::EUR_USD_RATE;
    }

    /**
     * @param $eur
     *
     * @return float
     */
    private static function convertEURtoJPY($eur)
    {
        return $eur * self::EUR_JPY_RATE;
    }

    /**
     * @param $jpy
     *
     * @return float
     */
    private static function convertJPYtoEUR($jpy)
    {
        return $jpy / self::EUR_JPY_RATE;
    }

    /**
     * @param $jpy
     *
     * @return float
     */
    private static function convertJPYtoUSD($jpy)
    {
        return self::convertEURtoUSD(
            self::convertJPYtoEUR($jpy)
        );
    }
}
