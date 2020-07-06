<?php
/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen\Measurement
 * 
 * @group Measurement
 * @group Performance
 */
namespace Selen\Measurement\Test;

use \PHPUnit\Framework\TestCase;
use \Selen\Measurement\Performance;

/**
 * @coversDefaultClass \Selen\Measurement\Performance
 */
class PerformanceTest extends TestCase
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
     * @covers ::__construct
     * @covers ::set
     * @covers ::start
     * @return void
     */
    public function testPerformance()
    {
        echo "\n";
        echo "\n";

        $performance1 = new Performance();
        
        $set_instance_1 = $performance1->set(function () {
            $sum = '1';
            $result = 0;
            for($i = 1; $i < 10000; $i++){
                $result += $sum;
            }
        });
        // $this->assertEquals($set_instance_1, $performance1);
        $this->assertEquals($set_instance_1->start(8), null);
        
        
        $performance2 = new Performance();

        $set_instance_2 = $performance2->set(function () {
            $sum = 1;
            $result = 0;
            for($i = 1; $i < 10000; $i++){
                $result += $sum;
            }
        });
        // $this->assertEquals($set_instance_2, $performance1);
        $this->assertEquals($set_instance_2->start(8), null);
    }
}
