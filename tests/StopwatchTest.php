<?php

/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen\Measurement
 *
 * @group Measurement
 * @group Stopwatch
 */

namespace Selen\Measurement\Test;

use PHPUnit\Framework\TestCase;
use Selen\Measurement\Stopwatch;

/**
 * @coversDefaultClass \Selen\Measurement\Stopwatch
 */
class StopwatchTest extends TestCase
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
     * @covers ::lap
     * @expectedException RuntimeException
     * @return void
     */
    public function testException()
    {
        $stopwatch = new Stopwatch();
        $stopwatch->lap();
    }
    
    /**
     * @covers ::__construct
     * @covers ::init
     * @covers ::start
     * @covers ::lap
     * @covers ::stop
     * @covers ::output
     * @covers ::addRecord
     * @covers ::nowMemory
     * @covers ::nowTime
     * @return void
     */
    public function testStopwatch()
    {
        $stopwatch = new Stopwatch();

        $this->assertEquals($stopwatch->start(), null);
        $this->assertEquals($stopwatch->lap(), null);
        $this->assertEquals($stopwatch->lap(), null);
        $this->assertEquals($stopwatch->stop(), null);
        echo "\n";
        echo "\n";
        echo "\n";
        echo "output result:\n";
        $this->assertEquals($stopwatch->output(), null);
    }
}
