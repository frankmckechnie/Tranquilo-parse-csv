<?php

class ParseCsv extends \PHPUnit_Framework_TestCase {

	public function testInvalidPath(){
		$csv = new ParseCsv(__DIR__ . '/tesdt.csv', ","); 
		$this->expectException(CsvException::class);
	}

}
