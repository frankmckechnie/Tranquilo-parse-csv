<?php
//load in auto files
require __DIR__ . '/vendor/autoload.php';

use Tranquilo\ParseCsv;
use Tranquilo\Exceptions\CsvException;

try{
	$parser = new ParseCsv(__DIR__ . '/test.csv');
	$parser->convertEncoding();
	$csvAry = $parser->parse($limit);
}catch(CsvException $e){
	echo $e->getMessage();
}

// $file = fopen(__DIR__ . '/test.csv', 'r');

// //print_r($csvAry);


// return as object or array 


