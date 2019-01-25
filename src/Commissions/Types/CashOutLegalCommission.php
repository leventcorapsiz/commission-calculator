<?php

namespace leventcorapsiz\CommissionCalculator\Commissions\Types;

use leventcorapsiz\CommissionCalculator\Services\CurrencyService;

class CashOutLegalCommission extends Commission implements CommissionTypeInterface
{
    /**
     * @var float
     */
    const COMMISSION_PERCENTAGE = 0.3;

    /**
     * @var array
     */
    const MIN_COMMISSION = [
        'currency' => 'EUR',
        'fee'      => 0.5
    ];

    /**
     * @return float
     */
    public function calculate()
    {
        $commission    = $this->getFee(self::COMMISSION_PERCENTAGE);
        $minCommission = CurrencyService::convert(
            self::MIN_COMMISSION['currency'],
            self::MIN_COMMISSION['fee'],
            $this->currency
        );

        if (bccomp($minCommission, $commission, self::ARITHMETIC_SCALE) === 1) {
            return $minCommission;
        }

        return $commission;
    }
}
