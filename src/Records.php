<?php
/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen
 */
namespace Selen\Measurement;

use Selen\Measurement\Record;
/**
 * メモリ使用量、実行時間をスタックするクラス
 */
class Records
{
    /**
     * @var Record メモリ使用量、実行時間を保持する配列
     */
    private $stack = [];

    /**
     * レコードを1件追加します。
     * @param Record 
     * @return void
     */
    public function push(Record $record)
    {
        $this->stack[] = $record;
    }

    /**
     * レコードを1件取得します。
     * @return Record
     */
    public function pop()
    {
        $tmp = array_shift($this->stack);
        if($tmp === null){
            throw new \RuntimeException('popできません');
        }
        return $tmp;
    }

    public function size()
    {
        return count($this->stack);
    }

    /**
     * 保持したレコードキャッシュを破棄します。
     * @return void
     */
    public function clear()
    {
        $this->stack = [];
    }
}
