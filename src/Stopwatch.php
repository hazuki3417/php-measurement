<?php
/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen\Measurement
 */
namespace Selen\Measurement;

use RuntimeException;
use Selen\Measurement\State;
use Selen\Measurement\Output;
use Selen\Measurement\Record;
use Selen\Measurement\Queue;
/**
 * メモリ使用量、実行時間を計測するクラス
 */
class Stopwatch
{
    /**
     * @var State インスタンスを保持する変数
     */
    private $state = null;

    /**
     * @var Queue インスタンスを保持する変数
     */
    private $queue = null;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->state = new State();
        $this->queue = new Queue();
        $this->init();
    }

    private function init()
    {
        $this->state->stop();
        $this->queue->clear();
    }

    public function start()
    {
        $this->state->run();

        $this->addRecord();
    }

    public function lap()
    {
        if(!$this->state->get()){
            // 計測終了中ならエラーメッセージを出力する
            throw new RuntimeException('計測中に実行してください。');
        }
        $this->addRecord();
    }

    public function stop()
    {
        $this->addRecord();
        $this->state->stop();
    }

    public function output()
    {
        $output = new Output($this->queue);
        $output->echo();
    }
    

    private function addRecord()
    {
        $record = new Record($this->nowMemory(), $this->nowTime());
        $this->queue->enqueue($record);
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
