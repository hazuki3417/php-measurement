<?php
/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen\Measurement
 * 
 * @group Measurement
 * @group RecordTable
 */
namespace Selen\Measurement\Test;

use \PHPUnit\Framework\TestCase;
use \Selen\Measurement\RecordTable;
use \Selen\Measurement\Queue;
use \Selen\Measurement\Record;

/**
 * @coversDefaultClass \Selen\Measurement\RecordTable
 */
class RecordTableTest extends TestCase
{
    /** @var Record */
    private $record = null;
    /** @var Queue */
    private $queue  = null;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->record = new Record(0, 0.0);
        $this->queue  = new Queue;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers ::__construct
     * @covers ::outputType
     * @expectedException InvalidArgumentException
     * @return void
     */
    public function testOutputTypeException()
    {
        $output = new RecordTable($this->queue);
        $output->outputType('');
    }
    
    /**
     * @covers ::__construct
     * @covers ::outputType
     * @return void
     */
    public function testOutputType1()
    {
        $output = new RecordTable($this->queue);
        $this->assertEquals(
            $output->outputType(RecordTable::OUTPUT_TYPE_TERMINAL), null);
    }
    
    /**
     * @covers ::__construct
     * @covers ::outputType
     * @covers ::create
     * @return void
     */
    public function testRecordTable1()
    {
        $output = new RecordTable($this->queue);
        $records = $output->create();
        $this->assertInternalType('array', $records);
        $this->assertNotEmpty($records);
    }

    /**
     * @covers ::__construct
     * @covers ::outputType
     * @covers ::create
     * @return void
     */
    public function testRecordTable2()
    {
        $this->queue->enqueue($this->record);
        $output = new RecordTable($this->queue);
        $records = $output->create();
        $this->assertInternalType('array', $records);
        $this->assertNotEmpty($records);
    }
}
