<?php

require_once __DIR__ . '/vendor/autoload.php';

use leventcorapsiz\CommissionCalculator\TransactionCollection;

$collection = new TransactionCollection();
$collection->parseFromCSV($argv[1]);

foreach ($collection->getCommissionFees() as $fee) {
    echo $fee . PHP_EOL;
}
