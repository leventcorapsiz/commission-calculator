# Commission Calculator  
  
An experimental project that parses transactions from a csv file and calculates commission fees.  
  
## Installation  
  
Clone repository and install dependencies via composer.  
  
     composer install --no-dev  
## Usage  
  
  
```php  
<?php
  
use leventcorapsiz\CommissionCalculator\TransactionCollection;
use leventcorapsiz\CommissionCalculator\Services\CurrencyService;
use leventcorapsiz\CommissionCalculator\Services\CommissionService;
  
$collection = new TransactionCollection();
$collection->parseFromCSV($path);
    
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
    
$commissionService = new CommissionService($currencyService);
    
return $commissionService->calculateFeesFromCollection($collection); 
```  
## Demo  
  
     php script.php input.csv

## Tests  
  
Install dev dependencies via composer and run tests.  
  
     composer install --dev
     ./vendor/bin/phpunit tests