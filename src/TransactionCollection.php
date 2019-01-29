<?php

namespace leventcorapsiz\CommissionCalculator;

use League\Csv\Reader;
use leventcorapsiz\CommissionCalculator\Exceptions\FileNotFoundException;
use leventcorapsiz\CommissionCalculator\Models\Amount;
use leventcorapsiz\CommissionCalculator\Models\Transaction;

class TransactionCollection
{
    /**
     * @var Transaction[]
     */
    protected $transactions = [];

    /**
     * @param $path
     * @param bool $append
     *
     * @throws FileNotFoundException
     */
    public function parseFromCSV($path, $append = false)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException;
        }
        $this->transactions = $append ? $this->transactions : [];
        foreach (Reader::createFromPath($path) as $csvLine) {
            $this->add(new Transaction(
                $this->generateTransactionID(),
                new \DateTime($csvLine[0]),
                $csvLine[1],
                $csvLine[2],
                $csvLine[3],
                new Amount($csvLine[4], $csvLine[5])
            ));
        }
    }

    /**
     * @return int
     */
    private function generateTransactionID()
    {
        return $this->isEmpty() ? 1 : end($this->getTransactions())->getTransactionID() + 1;
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @param Transaction $transaction
     *
     * @return $this
     */
    public function add(Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * @return bool
     */
    private function isEmpty()
    {
        return empty($this->getTransactions());
    }
}
