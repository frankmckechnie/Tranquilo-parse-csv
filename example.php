<?php
require __DIR__ . '/vendor/autoload.php';

use Tranquilo\ParseCsv;
use Tranquilo\Exceptions\CsvException;

try{
	$csv = new ParseCsv(__DIR__ . '/test.csv');
	$csv->convertEncoding('UTF-8');
	$csvAry = $csv->get(2, 20); 
	print_r($csvAry);
}catch(CsvException $e){
	echo $e->getMessage();
}catch(Exception $e){
	echo $e->getMessage();
}

