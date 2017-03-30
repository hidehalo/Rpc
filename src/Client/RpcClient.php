<?php
namespace JsonRPC\Client;

use JsonRPC\Request\RequestBuilder;
use JsonRPC\Protocols\Json as JsonProtocol;
use Comn\Pool\ConnectionsPool;
use \Exception;

class RpcClient
{
    /**
     * 发送数据和接收数据的超时时间  单位S
     * @var integer
     */
    const TIME_OUT = 30;
    
    /**
     * 异步调用发送数据前缀
     * @var string
     */
    const ASYNC_SEND_PREFIX = 'asend_';
    
    /**
     * 异步调用接收数据
     * @var string
     */
    const ASYNC_RECV_PREFIX = 'arecv_';

    const SERVER_LIST = CONFIG.'/jsonrpc/address.php';

    const SERVICE_LIST = COMN.'/jsonrpc/service.php';
    
    /**
     * 服务端地址
     * @var array
     */
    protected static $addressArray = array();
    
    /**
     * 异步调用实例
     * @var string
     */
    protected static $asyncInstances = array();
    
    /**
     * 同步调用实例
     * @var string
     */
    protected static $instances = array();
    
    /**
     * 到服务端的socket连接
     * @var resource
     */
    protected  $connection = null;
    
    /**
     * 实例的服务名
     * @var string
     */
    protected $serviceName = '';
    
    /**
     * 获取一个实例
     * @param string $service_name
     * @return  \JsonRPC\Client\RpcClient $client
     */
    public static function instance($service_name)
    {
        if (!isset(self::$instances[$service_name])) {
            self::$instances[$service_name] = new self($service_name);
        }
        $real_service = @self::parseServiceName($service_name);
        $config = self::$instances[$service_name]->parseConfig($real_service);
        self::$addressArray[$real_service] = $config;

        return self::$instances[$service_name];
    }
    
    /**
     * 构造函数
     * @param string $service_name
     */
    public function __construct($service_name)
    {
        $real_service = @self::parseServiceName($service_name);
        if(!$real_service)
            throw new \Exception('RPC Service Name Format Error',5001);
        $this->serviceName = $service_name;
        $config = $this->parseConfig($real_service);
        self::$addressArray[$real_service] = $config;
    }

    public function __destruct()
    {
        $pool = ConnectionsPool::createOrGetPool($this->serviceName);
        /**
         * @var \Comn\Pool\SimplePool $pool
         */
        $pool->cleanup();
        ConnectionsPool::destroyPool($this->serviceName);
    }
    
    /**
     * 调用
     * @param string $method
     * @param array $arguments
     * @throws Exception
     * @return 
     */
    public function __call($method, $arguments)
    {
        // 判断是否是异步发送
        if(0 === strpos($method, self::ASYNC_SEND_PREFIX))
        {
            $real_method = substr($method, strlen(self::ASYNC_SEND_PREFIX));
            $instance_key = $real_method . serialize($arguments);
            if(isset(self::$asyncInstances[$instance_key]))
            {
                throw new Exception($this->serviceName . "->$method(".implode(',', $arguments).") have already been called");
            }
            self::$asyncInstances[$instance_key] = new self($this->serviceName);
            return self::$asyncInstances[$instance_key]->sendData($real_method, $arguments);
        }
        // 如果是异步接受数据
        if(0 === strpos($method, self::ASYNC_RECV_PREFIX))
        {
            $real_method = substr($method, strlen(self::ASYNC_RECV_PREFIX));
            $instance_key = $real_method . serialize($arguments);
            if(!isset(self::$asyncInstances[$instance_key]))
            {
                throw new Exception($this->serviceName . "->asend_$real_method(".implode(',', $arguments).") have not been called");
            }
            return self::$asyncInstances[$instance_key]->recvData();
        }
        // 同步发送接收
        $this->sendData($method, $arguments);

        return $this->recvData();
    }
    
    /**
     * 发送数据给服务端
     * @param string $method
     * @param array $arguments
     */
    public function sendData($method, $arguments)
    {
        $this->openConnection();
        $requestBuilder = new RequestBuilder();
        $request = $requestBuilder
            ->withProcedure($method)
            ->withParams($arguments)
            ->withRequestAttributes(array('service' => $this->serviceName))
            ->build(false);
        $bin_data = JsonProtocol::encode($request);
        if(@fwrite($this->connection, $bin_data) !== strlen($bin_data)) {
            throw new \Exception('Can not send data',-32001);
        }

        return true;
    }
    
    /**
     * 从服务端接收数据
     * @throws Exception
     */
    public function recvData()
    {
        $ret = @fgets($this->connection);
        $this->closeConnection();
        if(!$ret) {
            return null;
        }
        return JsonProtocol::decode($ret);
    }
    
    /**
     * 打开到服务端的连接
     * @return void
     */
    protected function openConnection()
    {
        $alias = $this->parseServiceName($this->serviceName);
        $address = self::$addressArray[$alias][array_rand(self::$addressArray[$alias])];
        /**
         * @var \Comn\Pool\SimplePool $pool
         */
        $pool = ConnectionsPool::createOrGetPool($this->serviceName);

        if(!$pool)
            return ;

        if(!$pool->isFull()) {
            $conn = $this->getConnection($address);
            $pool->dispose($conn);
        }

        $this->connection = $pool->get()->_conn;
    }

    protected function getConnection($endpoint)
    {
        $connection = stream_socket_client($endpoint, $err_no, $err_msg);
        if(!$connection) {
            throw new Exception("can not connect to $endpoint , $err_no:$err_msg",-32000);
        }
        stream_set_blocking($connection, true);
        stream_set_timeout($connection, self::TIME_OUT);
        $conn = new \stdClass();
        $conn->_conn = $connection;

        return $conn;
    }

    /**
     * 关闭到服务端的连接
     * @return void
     */
    protected function closeConnection()
    {
        ConnectionsPool::releasePool($this->serviceName);
    }

    protected function parseServiceName($serviceName)
    {
        $realService = explode('@',$serviceName);
        if($realService[0]){
            return $realService[0];
        }

        return false;
    }

    protected function parseConfig($alias)
    {
        $serviceList = require(self::SERVICE_LIST);
        $addressList = require(self::SERVER_LIST);;
        $config = [];
        if($serviceList && $addressList){
            foreach($serviceList as $server=>$service){
                if(is_array($service) && in_array($alias,$service)){
                    $port = @$addressList[$server]['port'];
                    $ips = @$addressList[$server]['ip'];
                    if($ips && $port){
                        foreach($ips as $ip){
                            $config[] = $ip.':'.$port;
                        }
                    }
                    break;
                }
                continue;
            }
        }

        return $config;
    }

    static function parseData($rpcResponse)
    {
        return $rpcResponse['result'];
    }
}
