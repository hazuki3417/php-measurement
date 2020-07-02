<?php
/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen\Measurement
 */
namespace Selen\Measurement;

use Selen\Measurement\Queue;
/**
 * 
 */
class Output
{
    // const PHP_ERROR_LOG_PATH = '/var/log/nginx/errors.log';
    
    const OUTPUT_TYPE_HTML     = 'html';
    const OUTPUT_TYPE_LOG      = 'log';
    const OUTPUT_TYPE_TERMINAL = 'terminal';

    // const FORMAT_START  = 'message (start) : start of measurement';
    // const FORMAT_LAP    = 'message (%s)    : %s';
    // const FORMAT_END    = 'message (end)   : end of measurement';
    // const FORMAT_MEMORY = 'memory usage    : lap %.3f [MB] split %.3f [MB]';
    // const FORMAT_TIME   = 'process time    : lap %.3f [s]  split %.3f [s]';

    const MESSAGE_FORMAT_HEADER = '| %-12s | %16s | %16s | %14s | %14s |';
    const MESSAGE_FORMAT_RESULT = '| %-12s | %16.12f | %16.12f | %14.3f | %14.3f |';


    /**
     * @var string 改行コードを保持する変数
     */
    private $new_line = "\n";

    /**
     * @var Queue インスタンスを保持する変数
     */
    private $queue = null;

    /**
     * コンストラクタ
     */
    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }
    
    public function outputType($type)
    {
        switch($type){
            case self::OUTPUT_TYPE_TERMINAL:
                $this->new_line = "\n";
                break;
            // case self::OUTPUT_TYPE_HTML:
            //     $this->new_line = "</br>";
            //     break;
            // case self::OUTPUT_TYPE_LOG:
            //     // phpのエラーログ設定を一時的に変更
            //     ini_set('log_errors', 'On');
            //     ini_set('error_log', self::PHP_ERROR_LOG_PATH);
            //     break;
            default:
                throw new InvalidArgumentException('The value is incorrect. $type');
        }
    }


    public function echo()
    {
        echo sprintf(
            self::MESSAGE_FORMAT_HEADER . $this->new_line,
            '',
            'process(1)[s]',
            'process(t)[s]',
            'memory(1)[MB]',
            'memory(t)[MB]'
        );

        // 最初の記録は基準値として利用するため先に取得
        $record = $this->queue->dequeue();
        $base_time   = $record->getTime();
        $base_memory = $record->getMemory();
        $prev_time   = $base_time;
        $prev_memory = $base_memory;

        // 2つ目から基準値との差分を出力する
        $count = $this->queue->size();
        for($i = 0; $i < $count; $i++){
            $record        = $this->queue->dequeue();

            $target_time   = $record->getTime();
            $target_memory = $record->getMemory();

            $lap_time    = $target_time - $prev_time;
            $lap_memory  = $target_memory - $prev_memory;
            $diff_time   = $target_time - $base_time;
            $diff_memory = $target_memory - $base_memory;

            echo sprintf(
                self::MESSAGE_FORMAT_RESULT . $this->new_line,
                'test',
                $lap_time,
                $diff_time,
                $lap_memory,
                $diff_memory
            );
            // 前回値を保持
            $prev_time    = $target_time;
            $prev_memory  = $target_memory;
        }
    }
}
