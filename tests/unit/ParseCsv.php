<?php
/**
 * Franks's simple parse library
 *
 * @package Tranquilo/parsecsv
 * @author Frank Mckechnie <frankmckechnie@gmail.com>
 * @copyright Copyright (c) 2013, Frank Mckechnie
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */

namespace Tranquilo;

class ParseCsv extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @return void
     */
    public function testInvalidPath()
    {
        $this->expectException(\Exception::class);
        $csv = new Tranquilo\ParseCsv(__DIR__ . '/twest.csv');
    }

    /**
     * @test
     * @return void
     */
    public function testGetAllRowsCount()
    {
        $csv = new Tranquilo\ParseCsv('test.csv');
        $allRows = $csv->get();
        $this->assertEquals($csv->getRowCount(), count($allRows));
    }

    /**
     * @test
     * @return void
     */
    public function testGetAllRowsArray()
    {
        $csv = new Tranquilo\ParseCsv('test.csv');
        $allRows = $csv->get();
        $this->assertTrue(is_array($allRows));
        $this->assertFalse(empty($allRows));
    }

    /**
     * @test
     * @return void
     */
    public function testGetFiveRows()
    {
        $csv = new Tranquilo\ParseCsv('test.csv');
        $allRows = $csv->get(5);
        $this->assertTrue(is_array($allRows));
        $this->assertFalse(count($allRows) > 5);
    }

    /**
     * @test
     * @return void
     */
    public function testGetWithOffset()
    {
        $csv = new Tranquilo\ParseCsv('test.csv');
        $withOffset = $csv->getWithOffset(5);
        $this->assertTrue($withOffset[0]['id'] == 6);
    }

    /**
     * @test
     * @return void
     */
    public function testGetWithLimitAndOffset()
    {
        $csv = new Tranquilo\ParseCsv('test.csv');

        // get limit of 5 and then offset at 2
        $withLimitAndOffset = $csv->get(5, 2);

        $this->assertTrue($withLimitAndOffset[0]['id'] == 3);
        $this->assertTrue($withLimitAndOffset[4]['id'] == 7);
    }

    /**
     * @test
     * @return void
     */
    public function testConvertEncoding()
    {
        $csv = new Tranquilo\ParseCsv('test.csv');
        $type = $csv->convertEncoding('UTF-8');
        $this->assertTrue($type);
    }


    /**
     * @test
     * @return void
     */
    public function testGetFile()
    {
        $csv = new Tranquilo\ParseCsv('test.csv');
        $allRowsCsv = $csv->get(5);
        $this->assertTrue(is_resource($csv->getFile()));
    }
}
