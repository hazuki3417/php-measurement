<?php
include dirname(__DIR__) . '/src/Selen/Measurement/Performance.php';

use Selen\Measurement\Performance;

$perf = new Performance();

$perf->set(function () {
    $hoge = 1;
})->start(8);
