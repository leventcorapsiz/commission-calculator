<?php

namespace leventcorapsiz\CommissionCalculator\Commissions;

use leventcorapsiz\CommissionCalculator\Models\Amount;

interface CommissionTypeInterface
{
    /**
     * @return Amount
     */
    public function calculate();
}
