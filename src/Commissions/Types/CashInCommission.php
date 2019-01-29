<?php

namespace leventcorapsiz\CommissionCalculator\Commissions\Types;

use leventcorapsiz\CommissionCalculator\Commissions\Commission;
use leventcorapsiz\CommissionCalculator\Commissions\CommissionTypeInterface;
use leventcorapsiz\CommissionCalculator\Models\Amount;

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
     * @return Amount
     */
    public function calculate()
    {
        $commission    = $this->getFee(self::COMMISSION_PERCENTAGE);
        $maxCommission = new Amount(self::MAX_COMMISSION['fee'], self::MAX_COMMISSION['currency']);

        if ($this->currencyService->isGreater($commission, $maxCommission)) {
            return $maxCommission;
        }

        return $commission;
    }
}
