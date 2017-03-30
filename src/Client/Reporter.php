<?php

namespace JsonRPC\Client;
use JsonRPC\Protocols\Statistic as StatisticProtocol;

/**
 * 统计客户端
 */
class Reporter
{
    private static $address;
    /**
     * [module=>[interface=>time_start, interface=>time_start ...], module=>[interface=>time_start ..], ... ]
     * @var array
     */
    protected static $timeMap = array();

    public function __construct()
    {
        self::$address = require __DIR__.'/../../../../config/jsonrpc/reporter.php';
    }

    /**
     * 模块接口上报消耗时间记时
     * @param string $module
     * @param string $interface
     */
    public static function tick($module = '', $interface = '')
    {
        self::$timeMap[$module][$interface] = microtime(true);

        return self::$timeMap[$module][$interface];
    }
    
    /**
     * 上报统计数据
     * @param string $module
     * @param string $interface
     * @param bool $success
     * @param int $code
     * @param string $msg
     * @param string $report_address
     * @return boolean
     */
    public static function report($module, $interface, $success, $code, $msg, $report_address = '')
    {
        if (!$report_address && !self::$address) {
            self::$address = require __DIR__.'/../../../../config/jsonrpc/reporter.php';
        }

        $report_address = $report_address?$report_address:self::$address;

        if(isset(self::$timeMap[$module][$interface]) && self::$timeMap[$module][$interface] > 0)
        {
            $time_start = self::$timeMap[$module][$interface];
            self::$timeMap[$module][$interface] = 0;
        }
        else if(isset(self::$timeMap['']['']) && self::$timeMap[''][''] > 0)
        {
            $time_start = self::$timeMap[''][''];
            self::$timeMap[''][''] = 0;
        }
        else
        {
            $time_start = microtime(true);
        }
         
        $cost_time = microtime(true) - $time_start;
        
        $bin_data = StatisticProtocol::encode($module, $interface, $cost_time, $success, $code, $msg);
        
        return self::sendData($report_address, $bin_data);
    }
    
    /**
     * 发送数据给统计系统
     * @param string $address
     * @param string $buffer
     * @return boolean
     */
    public static function sendData($address, $buffer)
    {
        $socket = stream_socket_client($address);
        if(!$socket)
        {
            return false;
        }
        return stream_socket_sendto($socket, $buffer) == strlen($buffer);
    }
    
}
