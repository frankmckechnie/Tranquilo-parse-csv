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

