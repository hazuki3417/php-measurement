![license](https://img.shields.io/github/license/hazuki3417/php-measurement) ![version](https://img.shields.io/packagist/php-v/hazuki3417/php-measurement)

# Overview（概要）

このパッケージはPHPプログラムの実行速度をチェックします。
このパッケージを用いることでリファクタリングなどの作業を支援します。

## Requirement（要件）
 - php 5.6以上
 

## Install（インストール）

パッケージをインストールするには、ターミナルから下記のコマンドを実行します。

```sh
composer require --dev hazuki3417/php-measurement
```

※開発支援用のパッケージですので、`--dev`オプションを付与してインストールすることを推奨します。


## Usage（使い方）

### 1.インスタンスを生成
`use`演算子を利用して`Performance`クラスのエイリアスを作成し、インスタンスを生成します。
（それか名前空間を指定して`Performance`クラスのインスタンスを生成します。）

```php
include 'vendor/autoload.php';

// Performanceクラスのエイリアスを作成
use Selen\Measurement\Performance;

// インスタンスを生成
$perf = new Performance();

```

```php
include 'vendor/autoload.php';

// 名前空間を指定してインスタンスを生成
$perf = Selen\Measurement\Performance();

```

### 2.計測

```php
include 'vendor/autoload.php';

use Selen\Measurement\Performance;

$perf1 = new Performance();

$perf1->set(function () {
    $sum = '1';
    $result = 0;
    for($i = 1; $i < 10000; $i++){
        $result += $sum;
    }
})->start(8);
```


## 出力結果

![performance](docs/images/performance.png)


|列|説明|単位|
|---|---|---|
|process(1)[s]|1回あたりの実行時間|秒|
|process(t[s]|累計の実行時間|秒||
|process(1)[MB]|1回あたりのメモリ使用量|メガバイト|
|process(1)[MB]|累計のメモリ使用量|メガバイト|

|行|説明|
|---|---|
|process:~|1回あたりの結果値|
|average|実行回数の平均値|

## その他情報

コーディング規約:PSR2


## Licence
[MIT](https://github.com/hazuki3417/php-measurement/blob/master/LICENSE)

## Author

[hazuki3417](https://github.com/hazuki3417)
