<?php

namespace leventcorapsiz\CommissionCalculator;

use leventcorapsiz\CommissionCalculator\Commissions\CommissionFeeFactory;

class Transaction
{
    protected $transactionDate;
    protected $identificationNumber;
    protected $userType;
    protected $operationType;
    protected $amount;
    protected $currency;
    protected $commissionFee;
    protected $userTransactionHistory;

    /**
     * Transaction constructor.
     *
     * @param $transactionDate
     * @param $identificationNumber
     * @param $userType
     * @param $operationType
     * @param $amount
     * @param $currency
     */
    public function __construct(
        $transactionDate,
        $identificationNumber,
        $userType,
        $operationType,
        $amount,
        $currency
    ) {
        $this->transactionDate      = $transactionDate;
        $this->identificationNumber = $identificationNumber;
        $this->userType             = $userType;
        $this->operationType        = $operationType;
        $this->amount               = $amount;
        $this->currency             = $currency;
    }

    /**
     * @return int
     */
    public function getIdentificationNumber()
    {
        return $this->identificationNumber;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getOperationType()
    {
        return $this->operationType;
    }

    /**
     * @return string
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * @return float|int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param array $transactions
     *
     * @return $this
     */
    public function setUserTransactionHistory(array $transactions)
    {
        $this->userTransactionHistory = $transactions;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getCommissionFee()
    {
        return $this->commissionFee = CommissionFeeFactory::generate(
            $this->userTransactionHistory,
            $this->userType,
            $this->operationType,
            $this->transactionDate,
            $this->amount,
            $this->currency
        );
    }
}
