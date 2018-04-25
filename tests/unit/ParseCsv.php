<?php


class ParseCsv extends \PHPUnit_Framework_TestCase {

	public function testInvalidPath(){
		$this->expectException(\Exception::class);
		$csv = new Tranquilo\ParseCsv(__DIR__ . '/twest.csv');
	}

	public function testGetAllRowsCount(){
		$csv = new Tranquilo\ParseCsv('test.csv');
		$allRows = $csv->get();
		$this->assertEquals($csv->getRowCount(), count($allRows));
	}

	public function testGetAllRowsArray(){
		$csv = new Tranquilo\ParseCsv('test.csv');
		$allRows = $csv->get();
		$this->assertTrue(is_array($allRows));
		$this->assertFalse(empty($allRows));
	}

	public function testGetFiveRows(){
		$csv = new Tranquilo\ParseCsv('test.csv');
		$allRows = $csv->get(5);
		$this->assertTrue(is_array($allRows));
		$this->assertFalse(count($allRows) > 5);
	}
}
