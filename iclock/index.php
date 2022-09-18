<?php
require 'vendor/autoload.php';

use Lvson\Iclock\mbizIclock;

$iclock = new mbizIclock();
$iclock->process();

// function getDeviceConfig($sn)
// {
//     $config = <<<'HEREA'
// Stamp=9999
// OpStamp=9999
// ErrorDelay=60
// Delay=30
// TransInterval=1
// TransFlag=111111111111
// Realtime=1
// TimeZone=7
// ADMSSyncTime=1
// HEREA;
//     $config = str_replace(['{sn}', '{time}'], [$sn, date('h:i', time())], $config);
//     echo $config;
//     exit(0);
// }
// function getVar($data, $key, $default)
// {
//     if (isset($data[$key])) {
//         return $data[$key];
//     }
//     return $default;
// }
// function processOk()
// {
//     echo 'OK';
//     exit(0);
// }
// function processAttendance($data)
// {
//     $filename = 'G:\project\mobibiz\dayhoconline\nguyenvietxuan\administrator\logs\atlog.log';
//     $fp = fopen($filename, 'a'); //opens file in append mode  
//     fwrite($fp, json_encode($data));
//     fwrite($fp, "\n");
//     fclose($fp);
//     return processOk();
// }

// function processOperation($data)
// {
//     $filename = 'G:\project\mobibiz\dayhoconline\nguyenvietxuan\administrator\logs\oper.log';
//     $fp = fopen($filename, 'a'); //opens file in append mode  
//     fwrite($fp, json_encode($data));
//     fwrite($fp, "\n");
//     fclose($fp);
//     return processOk();
// }

// function logData($str)
// {
//     $filename = 'G:\project\mobibiz\dayhoconline\nguyenvietxuan\administrator\logs\log.log';
//     $fp = fopen($filename, 'a'); //opens file in append mode  
//     fwrite($fp, json_encode($str));
//     fwrite($fp, "\n");
//     fclose($fp);
// }
// $data = $_REQUEST;
// $options = getVar($data, 'options', null);
// $table = getVar($data, 'table', null);
// $sn = getVar($data, 'SN', null);
// $postBody = file_get_contents("php://input");

// if ($options == 'all') {
//     return getDeviceConfig($sn);
// } elseif ($table == 'ATTLOG') {
//     return processAttendance($postBody);
// }

// if ($table == 'OPERLOG') {
//     return processOperation($postBody);
// }
// exit(0);
