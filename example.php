<?php
// load in auto files
require __DIR__ . '/vendor/autoload.php';

use Tranquilo\ParseCsv;

$parser = new ParseCsv(__DIR__ . '/test.csv');
$csvAry = $parser->parse();

print_r($csvAry);