# Commission Calculator  
  
An experimental project that parses transactions from a csv file and calculates commission fees.  
  
## Installation  
  
Clone repository and install dependencies via composer.  
  
     composer install  
## Usage  
  
  
```php  
<?php
  
use leventcorapsiz\CommissionCalculator\TransactionCollection;  
  
$collection = new TransactionCollection();  
$collection->parseFromCSV($path);  

$fees = $collection->getCommissionFees();  
```  
## Demo  
  
     php script.php input.csv