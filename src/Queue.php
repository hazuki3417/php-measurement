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
     * キューを1件追加します。
     * @param Record 追加するキューを渡します。
     * @return void
     */
    public function enqueue(Record $record)
    {
        $this->queues[] = $record;
    }

    /**
     * キューを1件取り出します。
     * @return Record キューを返します。
     */
    public function dequeue()
    {
        $record = array_shift($this->queues);
        if($record === null){
            throw new \RuntimeException('popできません');
        }
        return $record;
    }

    /**
     * 保持しているキューの件数を取得します。
     * @return int 保持しているキューの件数を返します。
     */
    public function size()
    {
        return count($this->queues);
    }

    /**
     * 保持しているキューをすべて破棄します。
     * @return void
     */
    public function clear()
    {
        $this->queues = [];
    }
}
