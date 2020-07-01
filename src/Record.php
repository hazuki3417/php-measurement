<?php
/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen
 */
namespace Selen\Measurement;
/**
 * メモリ使用量、実行時間を保持するクラス
 */
class Record
{
    /**
     * @var array メモリ使用量、実行時間を保持する配列
     */
    private $memory = 0;

    private $time   = 0;

    /**
     * コンストラクタ
     * @param int $memory
     * @param float $time
     */
    public function __construct($memory, $time)
    {
        $this->setMemory($memory);
        $this->setTime($time);
    }

    /**
     * メモリ量を設定します。
     * @param int $memory
     * @return void
     * @throws InvalidArgumentException int以外の値を指定したときに発生します。
     */
    private function setMemory($memory)
    {
        if(!is_integer($memory)){
            throw new \InvalidArgumentException();
        }
        $this->memory = $memory;
    }
    
    /**
     * メモリ量を取得します。
     * @return int メモリ量を返します。
     */
    public function getMemory()
    {
        return $this->memory;
    }
    
    /**
     * 実行時間を設定します。
     * @param int $memory
     * @return void
     * @throws InvalidArgumentException int以外の値を指定したときに発生します。
     */
    private function setTime($time)
    {
        if(!is_float($time)){
            throw new \InvalidArgumentException();
        }
        $this->time = $time;
    }

    /**
     * 実行時間量を取得します。
     * @return int 実行時間を返します。
     */
    public function getTime()
    {
        return $this->time;
    }
}
