<?php
/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen\Measurement
 * 
 * @group Measurement
 * @group Stopwatch
 * @group Record
 */
namespace Selen\Measurement\Test;

use \PHPUnit\Framework\TestCase;
use \Selen\Measurement\Record;

class RecordTest extends TestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Selen\Measurement\Record::__construct
     * @covers Selen\Measurement\Record::setMemory
     * @covers Selen\Measurement\Record::setTime
     * @expectedException InvalidArgumentException
     * @return void
     */
    public function testConstructException1()
    {
        $record = new Record('0', 0.0);
    }

    /**
     * @covers Selen\Measurement\Record::__construct
     * @covers Selen\Measurement\Record::setMemory
     * @covers Selen\Measurement\Record::setTime
     * @expectedException InvalidArgumentException
     * @return void
     */
    public function testConstructException2()
    {
        $record = new Record(0, 0);
    }

    /**
     * @covers Selen\Measurement\Record::__construct
     * @covers Selen\Measurement\Record::setMemory
     * @covers Selen\Measurement\Record::setTime
     * @covers Selen\Measurement\Record::getMemory
     * @covers Selen\Measurement\Record::getTime
     * @return void
     */
    public function testConstruct()
    {
        $nowMemory = $this->nowMemory();
        $nowTime   = $this->nowTime();
        $record = new Record($nowMemory, $nowTime);

        $this->assertEquals($nowMemory, $record->getMemory());
        $this->assertEquals($nowTime,   $record->getTime());
    }

    /**
     * 現在のメモリ使用量を取得します。
     * @return int 現在のメモリ使用量を返します。
     */
    private function nowMemory()
    {
        return memory_get_peak_usage();

    }

    /**
     * 現在のタイムスタンプを取得します。
     * @return float 現在の時刻を返します。
     */
    private function nowTime()
    {
        return microtime(true);
    }
}
