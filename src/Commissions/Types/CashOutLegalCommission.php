<?php

namespace leventcorapsiz\CommissionCalculator\Commissions\Types;

use leventcorapsiz\CommissionCalculator\Commissions\Commission;
use leventcorapsiz\CommissionCalculator\Commissions\CommissionTypeInterface;
use leventcorapsiz\CommissionCalculator\Models\Amount;

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
     * @return Amount
     */
    public function calculate()
    {
        $commission    = $this->getFee(self::COMMISSION_PERCENTAGE);
        $minCommission = new Amount(self::MIN_COMMISSION['fee'], self::MIN_COMMISSION['currency']);

        if ($this->currencyService->isGreater($minCommission, $commission)) {
            return $minCommission;
        }

        return $commission;
    }
}
