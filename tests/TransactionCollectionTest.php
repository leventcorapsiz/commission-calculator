<?php

namespace leventcorapsiz\CommissionCalculator\Tests;

use leventcorapsiz\CommissionCalculator\Exceptions\FileNotFoundException;
use leventcorapsiz\CommissionCalculator\TransactionCollection;
use PHPUnit\Framework\TestCase;

final class TransactionCollectionTest extends TestCase
{
    public function testCannotParseFromInvalidPath()
    {
        $this->expectException(FileNotFoundException::class);
        $collection = new TransactionCollection();
        $collection->parseFromCSV('invalidPath');
    }

    public function testCanParseCSVFile()
    {
        $collection = new TransactionCollection();
        $collection->parseFromCSV('./input.csv');
        $this->assertIsArray($collection->getCommissionFees());
    }
}
