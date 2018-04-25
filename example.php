<?php
require __DIR__ . '/vendor/autoload.php';

use Tranquilo\ParseCsv;
use Tranquilo\Exceptions\CsvException;

try{
	
	$csv = new ParseCsv(__DIR__ . '/test.csv', " "); // filepath, delimiter : optional
	
	// optional convert the encoding
	//$csv->convertEncoding('UTF-8');

	// starts from 5 
	//$withOffset = $csv->getWithOffset(5); 

	// gets all rows
	$allRowsCsv = $csv->get(5);

	// gets 10 rows starting from 5
	//$withLimitAndOffset = $csv->get(5, 2); // with limit and offset

	print_r($allRowsCsv);
}catch(CsvException $e){
	echo $e->getMessage();
}catch(Exception $e){
	echo $e->getMessage();
}

