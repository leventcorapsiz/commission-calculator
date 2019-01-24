<?php

namespace leventcorapsiz\CommissionCalculator;

use League\Csv\Reader;
use leventcorapsiz\CommissionCalculator\Exceptions\FileNotFoundException;

class TransactionCollection
{
    /**
     * @var Transaction[]
     */
    protected $transactions = [];

    /**
     * @param $path
     *
     * @throws FileNotFoundException
     */
    public function parseFromCSV($path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException;
        }

        foreach (Reader::createFromPath($path) as $transaction) {
            $transaction = new Transaction(...$transaction);
            $transaction->setUserTransactionHistory(
                $this->getUserTransactionHistoryByID(
                    $transaction->getIdentificationNumber()
                )
            );

            $this->add($transaction);
        }
    }

    /**
     * @return array
     */
    public function getCommissionFees()
    {
        $fees = [];
        foreach ($this->transactions as $transaction) {
            $fees[] = $transaction->getCommissionFee();
        }

        return $fees;
    }

    /**
     * @param Transaction $transaction
     *
     * @return $this
     */
    private function add(Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * @param $identificationNumber
     *
     * @return array
     */
    private function getUserTransactionHistoryByID($identificationNumber)
    {
        $collection = $this->transactions;

        return array_filter($collection,
            function (Transaction $transaction) use ($identificationNumber) {
                return $transaction->getIdentificationNumber() === $identificationNumber;
            });
    }
}
