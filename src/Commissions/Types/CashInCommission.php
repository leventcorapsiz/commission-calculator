<?php

namespace leventcorapsiz\CommissionCalculator\Commissions\Types;

use leventcorapsiz\CommissionCalculator\Services\CurrencyService;

class CashInCommission extends Commission implements CommissionTypeInterface
{
    /**
     * @var float
     */
    const COMMISSION_PERCENTAGE = 0.03;

    /**
     * @var array
     */
    const MAX_COMMISSION = [
        'currency' => 'EUR',
        'fee'      => 5
    ];

    /**
     * @return float
     */
    public function calculate()
    {
        $commission    = $this->getFee(self::COMMISSION_PERCENTAGE);
        $maxCommission = CurrencyService::convert(
            self::MAX_COMMISSION['currency'],
            self::MAX_COMMISSION['fee'],
            $this->currency
        );

        if ($commission > $maxCommission) {
            return $maxCommission;
        }

        return $commission;
    }
}
