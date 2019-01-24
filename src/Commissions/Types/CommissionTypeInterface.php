<?php

namespace leventcorapsiz\CommissionCalculator\Commissions\Types;

interface CommissionTypeInterface
{
    /**
     * @return float
     */
    public function calculate();
}
