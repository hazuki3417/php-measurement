<?php
/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen\Measurement
 * 
 * @group Measurement
 * @group Stopwatch
 * @group State
 */
namespace Selen\Measurement\Test;

use PHPUnit\Framework\TestCase;
use Selen\Measurement\State;

class StateTest extends TestCase
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
     * @covers State::get
     * @covers State::run
     * @covers State::stop
     * @return void
     */
    public function testState()
    {
        $state = new State();

        $this->assertEquals($state->get(),  false);

        $this->assertEquals($state->run(),  null);
        $this->assertEquals($state->get(),  true);
        
        $this->assertEquals($state->stop(), null);
        $this->assertEquals($state->get(),  false);
    }
}
