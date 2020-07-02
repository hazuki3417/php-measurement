<?php
/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen\Measurement
 * 
 * @group Measurement
 * @group Stopwatch
 * @group Queue
 */
namespace Selen\Measurement;

use PHPUnit\Framework\TestCase;
use Selen\Measurement\Queue;
use Selen\Measurement\Record;

class QueueTest extends TestCase
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
     * @covers Record::enqueue
     * @covers Record::dequeue
     * @covers Record::size
     * @covers Record::clear
     * @return void
     */
    public function testQueue()
    {
        $nowMemory = $this->nowMemory();
        $nowTime   = $this->nowTime();

        $record1 = new Record($this->nowMemory(), $this->nowTime());
        $record2 = new Record($this->nowMemory(), $this->nowTime());
        $record3 = new Record($this->nowMemory(), $this->nowTime());
        $record4 = new Record($this->nowMemory(), $this->nowTime());
        $queue = new Queue();

        $this->assertEquals($queue->size(), 0);

        $this->assertEquals($queue->enqueue($record1), null);
        $this->assertEquals($queue->size(), 1);

        $this->assertEquals($queue->enqueue($record2), null);
        $this->assertEquals($queue->size(), 2);

        $this->assertEquals($queue->enqueue($record3), null);
        $this->assertEquals($queue->size(), 3);

        $this->assertEquals($queue->enqueue($record4), null);
        $this->assertEquals($queue->size(), 4);

        $this->assertEquals($queue->dequeue(), $record1);
        $this->assertEquals($queue->size(), 3);

        $this->assertEquals($queue->dequeue(), $record2);
        $this->assertEquals($queue->size(), 2);

        $this->assertEquals($queue->clear(), null);
        $this->assertEquals($queue->size(), 0);
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
