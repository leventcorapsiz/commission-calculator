<?php

require_once __DIR__ . '/vendor/autoload.php';

use leventcorapsiz\CommissionCalculator\TransactionCollection;
use leventcorapsiz\CommissionCalculator\Services\CurrencyService;
use leventcorapsiz\CommissionCalculator\Services\CommissionService;

$currencies = [
    [
        'symbol'    => 'EUR',
        'rate'      => 1,
        'precision' => 2,
    ],
    [
        'symbol'    => 'USD',
        'rate'      => 1.1497,
        'precision' => 2,
    ],
    [
        'symbol'    => 'JPY',
        'rate'      => 129.53,
        'precision' => 0,
    ]
];

$currencyService = new CurrencyService();
$currencyService->collectCurrenciesFromArray($currencies);

$collection = new TransactionCollection();
$collection->parseFromCSV($argv[1]);

$commissionService = new CommissionService($currencyService);

foreach ($commissionService->calculateFeesFromCollection($collection) as $fee) {
    echo $fee . PHP_EOL;
}
