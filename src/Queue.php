<?php
/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen\Measurement
 */
namespace Selen\Measurement;

use \Selen\Measurement\Record;
/**
 * Recordクラスのデータを管理するクラス
 */
class Queue
{
    /**
     * @var Record Recordクラスを保持する配列
     */
    private $queues = [];

    /**
     * レコードを1件追加します。
     * @param Record 
     * @return void
     */
    public function enqueue(Record $record)
    {
        $this->queues[] = $record;
    }

    /**
     * レコードを1件取得します。
     * @return Record
     */
    public function dequeue()
    {
        $record = array_shift($this->queues);
        if($record === null){
            throw new \RuntimeException('popできません');
        }
        return $record;
    }

    public function size()
    {
        return count($this->queues);
    }

    /**
     * 保持したレコードキャッシュを破棄します。
     * @return void
     */
    public function clear()
    {
        $this->queues = [];
    }
}
