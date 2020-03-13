<?php
/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen\Measurement
 */
namespace Selen\Measurement;

/**
 * メモリ使用量、実行時間を計測するクラス
 */
class Stopwatch
{
    const PHP_ERROR_LOG_PATH = '/var/log/nginx/errors.log';
    const OUTPUT_TYPE_HTML = 'html';
    const OUTPUT_TYPE_LOG = 'log';
    const OUTPUT_TYPE_TERMINAL = 'terminal';
    const MESSAGE_FORMAT_START = 'message (start) : start of measurement';
    const MESSAGE_FORMAT_CHECK_POINT = 'message (%s)     : %s';
    const MESSAGE_FORMAT_END = 'message (end)   : end of measurement';
    const MESSAGE_FORMAT_MEMORY_USAGE = 'memory usage    : lap %.3f [MB] split %.3f [MB]';
    const MESSAGE_FORMAT_PROCESS_TIME = 'process time    : lap %.3f [s]  split %.3f [s]';

    /**
     * @var int メモリ使用量を保持する変数
     */
    private $base_memory_usage = 0;
    
    /**
     * @var int タイムスタンプを保持する変数
     */
    private $base_time = 0;
    
    /**
     * @var int 計測情報の数を保持する変数
     */
    private $check_point_count = 0;

    /**
     * @var array 計測情報を保持する変数
     */
    private $check_point_info = [];
    
    /**
     * @var array 計測情報を複数保持する変数
     */
    private $check_point_info_list = [];
    
    /**
     * @var string 改行コードを保持する変数
     */
    private $new_line = "";

    /**
     * @var string 出力形式を保持する変数
     */
    private $output_type = self::OUTPUT_TYPE_HTML;
    
    /**
     * コンストラクタ
     */
    final public function __construct($type = self::OUTPUT_TYPE_HTML)
    {
        if (!is_string($type)) {
            throw new InvalidArgumentException('引数には文字列型を指定してください。');
        }

        $this->output_type = $type;

        if ($this->output_type === self::OUTPUT_TYPE_TERMINAL) {
            $this->new_line = "\n";
        }

        if ($this->output_type === self::OUTPUT_TYPE_HTML) {
            $this->new_line = "</br>";
        }

        if ($this->output_type === self::OUTPUT_TYPE_LOG) {
            // phpのエラーログ設定を一時的に変更
            ini_set('log_errors', 'On');
            ini_set('error_log', self::PHP_ERROR_LOG_PATH);
        }

        $this->base_memory_usage = memory_get_usage();
        $this->base_time = microtime(true);
        $this->createCheckPointInfo();
        $position = $this->check_point_count - 1;
        $this->outputs($position, self::MESSAGE_FORMAT_START);
    }

    /**
     * Undocumented function
     *
     * @param [type] $str
     *
     * @return void
     */
    private function output($str)
    {
        if ($this->output_type === self::OUTPUT_TYPE_LOG) {
            error_log($str);
            return true;
        }
        echo $str.$this->new_line;
    }

    /**
     * Undocumented function
     *
     * @param [type] $position
     * @param [type] $message
     *
     * @return void
     */
    private function outputs($position, $message)
    {
        $this->output($message);
        $this->output($this->getMemoryUsageStr($position));
        $this->output($this->getProcessTimeStr($position));
        $this->output('');
    }

    /**
     * 計測結果を表示します。このクラスを生成した位置から、このメソッドが実行された間の
     * メモリ使用量、実行時間の累計（split）を表示します。またこのメソッドを複数回実行した場合は、
     * 前回実行した位置から現在実行した位置までのメモリ使用量、実行時間の差分（lap）を表示します。
     * @param string $message 出力するメッセージを指定します。
     * @return void
     */
    public function checkPoint($message = '')
    {
        $this->createCheckPointInfo();
        $position = $this->check_point_count - 1;
        $message = sprintf(self::MESSAGE_FORMAT_CHECK_POINT, $position, $message);
        $this->outputs($position, $message);
    }

    /**
     * 計測情報を生成します。
     * @return void
     */
    private function createCheckPointInfo()
    {
        $position = $this->check_point_count;
        $memory_usage = $this->getMemoryUsage();
        $process_time = $this->getProcessTime();
        $memory_usage_tmp =
            isset($this->check_point_info_list[$position - 1]['split']['memory_usage']) ?
                $this->check_point_info_list[$position - 1]['split']['memory_usage'] : 0;
        $process_time_tmp =
            isset($this->check_point_info_list[$position - 1]['split']['process_time']) ?
                $this->check_point_info_list[$position - 1]['split']['process_time'] : 0;

        $this->check_point_info = [
            'split'=> [
                'memory_usage' => $memory_usage,
                'process_time' => $process_time,
            ],
            'lap'=> [
                'memory_usage' => ($memory_usage - $memory_usage_tmp),
                'process_time' => ($process_time - $process_time_tmp),
            ],
        ];
        $this->check_point_info_list[] = $this->check_point_info;
        $this->check_point_count++;
    }
    
    /**
     * メモリ使用量を取得します。
     * @param int $position
     * @return void メモリ使用量を返します。
     */
    private function getMemoryUsageStr($position)
    {
        return sprintf(
            self::MESSAGE_FORMAT_MEMORY_USAGE,
            $this->check_point_info_list[$position]['lap']['memory_usage'],
            $this->check_point_info_list[$position]['split']['memory_usage']
        );
    }
    
    /**
     * 実行時間を取得します。
     * @param int $position
     * @return string 実行時間を返します。
     */
    private function getProcessTimeStr($position)
    {
        return sprintf(
            self::MESSAGE_FORMAT_PROCESS_TIME,
            $this->check_point_info_list[$position]['lap']['process_time'],
            $this->check_point_info_list[$position]['split']['process_time']
        );
    }
    
    /**
     * メモリ使用量を返す
     * @return float メモリの使用量を返します。
     */
    private function getMemoryUsage()
    {
        return (memory_get_peak_usage() - $this->base_memory_usage) / (1024 * 1024);
    }

    /**
     * 実行時間を返す
     * @return float 実行時間を返します。
     */
    private function getProcessTime()
    {
        return microtime(true) - $this->base_time;
    }
    
    /**
     * デストラクタ
     */
    final public function __destruct()
    {
        $this->createCheckPointInfo();
        $position = $this->check_point_count - 1;
        $this->outputs($position, self::MESSAGE_FORMAT_END);
    }
}
