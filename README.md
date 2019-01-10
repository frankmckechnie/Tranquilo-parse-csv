##Parse-csv

Tranquilo Parse-csv is a miniscule PHP application library. The library allows you to pass a csv file where some validation is done to insure your data is given back within an array. 

## Installation

You can install this library using Composer:

```console
$ composer require tranquilo/parsecsv

This project requires PHP 5.5 and has no dependencies.

The code is intended to comply with [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), and [PSR-4](http://www.php-fig.org/psr/psr-4/). If you find any issues related to standards compliance, please send a pull request!

```

## Examples

Some examples.


```php

<?php
require __DIR__ . '/vendor/autoload.php';

use Tranquilo\ParseCsv;
///use Tranquilo\Exceptions\CsvException;

try{
	// filepath, delimiter : optional
	$csv = new ParseCsv(__DIR__ . '/test.csv', ","); 
	
	// optional convert the encoding
	$csv->convertEncoding('UTF-8');

	// // starts from 5 
	$withOffset = $csv->getWithOffset(5); 

	// gets all rows
	$allRowsCsv = $csv->get(5);

	// gets 10 rows starting from 5
	$withLimitAndOffset = $csv->get(5, 2);

	print_r($allRowsCsv);

	unset($csv);
	
}catch(CsvException $e){

	echo $e->getMessage();
	
}catch(Exception $e){

	echo $e->getMessage();
	
}




```
