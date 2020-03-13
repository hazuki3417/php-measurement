<?php
/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen\Measurement
 */
namespace Selen\Measurement;

/**
 * 処理のパフォーマンスチェックをするクラス
 */
class Performance
{
    const PHP_ERROR_LOG_PATH = '/var/log/nginx/errors.log';
    const OUTPUT_TYPE_LOG = 'log';
    const OUTPUT_TYPE_TERMINAL = 'terminal';

    const MESSAGE_FORMAT_HEADER = '| %-12s | %16s | %16s | %14s | %14s |';
    const MESSAGE_FORMAT_RESULT = '| %-12s | %16.12f | %16.12f | %14.3f | %14.3f |';

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
    private $new_line = "\n";

    /**
     * @var string 出力形式を保持する変数
     */
    private $output_type = self::OUTPUT_TYPE_TERMINAL;
    
    /**
     * @var Object コールバックメソッドを保持する変数
     */
    private $process = null;

    /**
     * コンストラクタ
     */
    final public function __construct($type = self::OUTPUT_TYPE_TERMINAL)
    {
        if (!is_string($type)) {
            throw new InvalidArgumentException('引数には文字列型を指定してください。');
        }

        $this->output_type = $type;

        if ($this->output_type === self::OUTPUT_TYPE_TERMINAL) {
            // 出力がターミナルモードなら改行コードを出力
            $this->new_line = "\n";
        }

        if ($this->output_type === self::OUTPUT_TYPE_LOG) {
            // 出力がログモードならログファイルに出力
            // phpのエラーログ設定を一時的に変更
            ini_set('log_errors', 'On');
            ini_set('error_log', self::PHP_ERROR_LOG_PATH);
        }
    }

    private function output($str)
    {
        if ($this->output_type === self::OUTPUT_TYPE_LOG) {
            error_log($str);
            return true;
        }
        echo $str.$this->new_line;
    }

    /**
     * 計測結果を表示します。このクラスを生成した位置から、このメソッドが実行された間の
     * メモリ使用量、実行時間の累計（split）を表示します。またこのメソッドを複数回実行した場合は、
     * 前回実行した位置から現在実行した位置までのメモリ使用量、実行時間の差分（lap）を表示します。
     * @param string $message 出力するメッセージを指定します。
     * @return void
     */
    public function checkPoint()
    {
        $this->createCheckPointInfo();
        $position = $this->check_point_count - 1;
        $title = sprintf('process:%-4s', $position + 1);
        $message = sprintf(
            self::MESSAGE_FORMAT_RESULT,
            $title,
            $this->check_point_info_list[$position]['lap']['process_time'],
            $this->check_point_info_list[$position]['split']['process_time'],
            $this->check_point_info_list[$position]['lap']['memory_usage'],
            $this->check_point_info_list[$position]['split']['memory_usage']
        );
        $this->output($message);
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
    

    // コールバック実装させたい
    public function set($process)
    {
        $this->process = $process;
        return $this;
    }

    public function start($number = 1)
    {
        // コールバックメソッドを取得
        $method = $this->process;

        // テーブルのヘッダ部を出力
        $header = sprintf(
            self::MESSAGE_FORMAT_HEADER,
            '',
            'process(1)[s]',
            'process(t)[s]',
            'memory(1)[MB]',
            'memory(t)[MB]'
        );
        $header_cell_width_list = explode('|', $header);

        $hr_cell_str_list = [];
        foreach ($header_cell_width_list as $header_cell_width) {
            $hr_cell_str_list[] = str_repeat('-', strlen($header_cell_width));
        }
        $hr = implode('|', $hr_cell_str_list);
        $this->output($header);
        $this->output($hr);
        $this->base_memory_usage = memory_get_usage();
        $this->base_time = microtime(true);
        for ($i = 0; $i < $number; $i++) {
            // コールバックメソッド実行
            $method();
            // 計測結果出力
            $this->checkPoint();
        }

        // 一番最後の計測結果を取得
        $check_point_info = end($this->check_point_info_list);
        $process_total = $check_point_info['split']['process_time'];
        $memory_total = $check_point_info['split']['memory_usage'];
        
        // 一番最後の計測値と実行回数から1回あたりの平均値を算出
        $process_average = $process_total / $number;
        $memory_average = $memory_total / $number;

        // テーブルのフッタ部を作成
        $output = sprintf(
            self::MESSAGE_FORMAT_RESULT,
            'average',
            $process_average,
            $process_total,
            $memory_average,
            $memory_total
        );
        // テーブルのフッタ部を出力
        $this->output($output);
        $this->output('');

        // 初期化
        $this->check_point_count = 0;
        $this->check_point_info_list = [];
    }
}
