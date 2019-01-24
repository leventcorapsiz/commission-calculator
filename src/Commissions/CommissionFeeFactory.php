<?php

namespace leventcorapsiz\CommissionCalculator\Commissions;

use leventcorapsiz\CommissionCalculator\Commissions\Types\CashInCommission;
use leventcorapsiz\CommissionCalculator\Commissions\Types\CashOutLegalCommission;
use leventcorapsiz\CommissionCalculator\Commissions\Types\CashOutNaturalCommission;
use leventcorapsiz\CommissionCalculator\Commissions\Types\CommissionTypeInterface;
use leventcorapsiz\CommissionCalculator\Exceptions\InvalidOperationTypeException;
use leventcorapsiz\CommissionCalculator\Exceptions\InvalidUserTypeException;

class CommissionFeeFactory
{
    /**
     * @param array $oldTransactions
     * @param $userType
     * @param $operationType
     * @param $transactionDate
     * @param $amount
     * @param $currency
     *
     * @return CommissionTypeInterface
     * @throws InvalidOperationTypeException
     * @throws InvalidUserTypeException
     */
    public static function generate(
        array $oldTransactions,
        $userType,
        $operationType,
        $transactionDate,
        $amount,
        $currency
    ) {
        switch ($operationType) {
            case 'cash_in':
                $commission = new CashInCommission($amount, $currency);
                break;
            case 'cash_out':
                switch ($userType) {
                    case 'natural':
                        $commission = new CashOutNaturalCommission(
                            $amount,
                            $currency,
                            $transactionDate,
                            $oldTransactions
                        );
                        break;
                    case 'legal':
                        $commission = new CashOutLegalCommission($amount, $currency);
                        break;
                    default:
                        throw new InvalidUserTypeException;
                }
                break;
            default:
                throw new InvalidOperationTypeException;
        }

        return $commission;
    }
}
