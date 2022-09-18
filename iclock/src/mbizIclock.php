<?php

namespace Lvson\Iclock;

use Medoo\Medoo;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

require('../configuration.php');
class mbizIclock
{
    static $db;
    static $dbprefix;
    public static function getDbInstance()
    {
        if (!static::$db) {
            $config = new \JConfig();
            static::$db = new Medoo([
                'type' => str_replace($config->dbtype, 'mysqli', 'mysql'),
                'host' => $config->host,
                'database' => $config->db,
                'username' => $config->user,
                'password' => $config->password
            ]);
            static::$dbprefix = $config->dbprefix;
        }
        return static::$db;
    }

    public static function getVar($params, $key, $default)
    {
        return isset($params[$key]) ? $params[$key] : $default;
    }

    public static function getTable($table)
    {
        return static::$dbprefix . $table;
    }

    public function getDeviceConfig($sn)
    {
        $config = <<<'HEREA'
GET OPTION FROM: {sn}
Stamp=9999
OpStamp=9999
ErrorDelay=60
Delay=10
TransTimes=00:00;14:05
TransInterval=1
TransFlag=TransData AttLog	OpLog	AttPhoto	EnrollFP	EnrollUser	FPImag	ChgUser	ChgFP	FACE	UserPic	FVEIN	BioPhoto
Realtime=1
TimeZone=7
ADMSSyncTime=1
HEREA;
        $config = str_replace('{sn}', $sn, $config);
        return $config;
    }

    public function output($data)
    {
        echo $data;
        exit();
    }

    public function cdata($params)
    {
        $options = static::getVar($params, 'options', null);
        $table = static::getVar($params, 'table', null);
        $sn = static::getVar($params, 'SN', null);
        if ($options == 'all') {
            $config = $this->getDeviceConfig($sn);
            return $this->output($config);
        }
        if ($table == 'ATTLOG') {
            $this->processAttendance($sn);
            return $this->output('OK');
        }

        if ($table == 'OPERLOG') {
            $this->processOperation($sn);
            return $this->output('OK');
        }
    }

    public function getrequest($params)
    {
        $limit = 10;
        $sn = static::getVar($params, 'SN', null);
        $cmds = $this->getCmd($sn,  $limit);
        if ($cmds) {
            $data = array_column($cmds, 'cmd');
            return $this->output(implode('\r\n', $data));
        }
        return $this->output('OK');
    }

    public function devicecmd($params)
    {
        return $this->output('OK');
    }


    public function processAttendance($sn)
    {
        $data = $this->parseData();
        $rows = [];
        $createdTime = date('Y-m-d H:i:s');
        foreach ($data as $row) {
            $rows[] = [
                'iclock_id' => $row[0],
                'sn' => $sn,
                'checked_time' => $row[1],
                'created_time' => $createdTime,
                'pr1' => $row[2],
                'pr2' => $row[3],
                'pr3' => $row[4],
                'pr4' => $row[5],
                'pr5' => $row[6],
            ];
        }

        static::getDbInstance()->insert(
            static::getTable('iclock_attlog'),
            $rows
        );
    }



    public function processOperation($sn)
    {
        $data = $this->parseData();
        $rows = [];
        $createdTime = date('Y-m-d H:i:s');
        foreach ($data as $row) {
            $rows[] = [
                'sn' => $sn,
                'created_time' => $createdTime,
                'data' => file_get_contents("php://input"),
            ];
        }

        static::getDbInstance()->insert(
            static::getTable('iclock_operlog'),
            $rows
        );
    }

    public function parseData()
    {
        $data = [];
        $rawInput = fopen('php://input', 'r');
        while ($f = fgets($rawInput)) {
            $f = str_replace('\n', '', $f);
            $f = str_replace('\r', '', $f);
            $data[] = explode("\t", $f);
        }
        return $data;
    }

    public function process()
    {
        $this->addLog();
        $params = $_GET;
        $path = explode('/', trim($_SERVER['REDIRECT_URL'], '/'));
        if (isset($path[1]) && method_exists($this, $path[1])) {
            $func = $path[1];
            return $this->$func($params);
        }
    }

    public function getCmd($sn, $limit)
    {
        $db = static::getDbInstance();
        $prefix = static::$dbprefix;
        $rows = [];
        $db->action(function ($db) use ($prefix, $limit, $sn, &$rows) {
            $sql = "SELECT id, cmd FROM `{$prefix}iclock_cmd` WHERE sn = '{$sn}' AND state = 0 LIMIT 0, {$limit} FOR UPDATE;";
            $rows = $db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
            if (!$rows) {
                return false;
            }
            $db->update(
                static::getTable('iclock_cmd'),
                ['state' => 1],
                ['id' => array_column($rows, 'id')]
            );
        });
        return $rows;
    }

    public function addLog()
    {
        $content = $_SERVER['REQUEST_METHOD'] === 'POST' ? file_get_contents("php://input") : null;
        $row = [
            'ip' => $_SERVER['REMOTE_ADDR'],
            'data' => $content
        ];
        $loggerTimeZone = new \DateTimeZone('Asia/Jakarta');
        $logger = new Logger('default');
        $logger->setTimezone($loggerTimeZone);
        $file = sprintf("%s/%s.log", './logs', date('Y-m-d'));
        $stream_handler = new StreamHandler($file, Logger::DEBUG);
        $output = "%level_name% | %datetime% | %message% | %context% | %extra%\n";
        $stream_handler->setFormatter(new LineFormatter($output));
        $logger->pushHandler($stream_handler);
        $logger->info($_SERVER['REQUEST_URI'], $row);
    }
}
