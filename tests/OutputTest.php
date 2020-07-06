<?php
/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen\Measurement
 * 
 * @group Measurement
 * @group Output
 */
namespace Selen\Measurement\Test;

use \PHPUnit\Framework\TestCase;
use \Selen\Measurement\Output;
use \Selen\Measurement\Queue;
use \Selen\Measurement\Record;

/**
 * @coversDefaultClass \Selen\Measurement\Output
 */
class OutputTest extends TestCase
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
    public function testException()
    {
        $output = new Output($this->queue);
        $output->outputType('');
    }
    
    /**
     * @covers ::__construct
     * @covers ::outputType
     * @covers ::echo
     * @return void
     */
    public function testOutput()
    {
        $output = new Output($this->queue);
        $this->assertEquals($output->echo(), null);
    }
}
