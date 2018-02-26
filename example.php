<?php
//load in auto files
require __DIR__ . '/vendor/autoload.php';

use Tranquilo\ParseCsv;
use Tranquilo\Exceptions\CsvException;

try{
	$parser = new ParseCsv("   ");
	$csvAry = $parser->parse();
}catch(CsvException $e){
	echo $e->getMessage();
}catch (Exception $ex){
	echo "I am ecx ";
}

// $file = fopen(__DIR__ . '/test.csv', 'r');

// //print_r($csvAry);


// return as object or array 


