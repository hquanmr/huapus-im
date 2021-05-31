<?php
require_once __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Lhq\HuapusIm\Application;
$config = [
    'Identifier' => '',
    'Secretkey' => '',
    'AppID' => ''
];

$log = new Logger('HuapusIm');// create a log channel
$date = date('Y-m-d');//'app/logs/sql_'.$date.'.log'  路径以及日志文件名
$log->pushHandler(new StreamHandler(__DIR__.'/Logs/HuapusIm_'.$date.'.log', Logger::DEBUG));//Logger::DEBUG 日志级别

$app = new Application($config,$log);
print_r($app->group->destroy('@TGS#aNSTCCGH2'));






