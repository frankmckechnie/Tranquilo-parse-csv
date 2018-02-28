<?php
//load in auto files
require __DIR__ . '/vendor/autoload.php';

use Tranquilo\ParseCsv;
use Tranquilo\Exceptions\CsvException;

try{
	$parser = new ParseCsv(__DIR__ . '/test.csv');
	//$parser->convertEncoding();
	$csvAry = $parser->parse(10); // $limit
	print_r($csvAry);
}catch(CsvException $e){
	echo $e->getMessage();
}catch(Exception $e){
	echo $e->getMessage();
}

// $file = fopen(__DIR__ . '/test.csv', 'r');

// //print_r($csvAry);

