<?php

namespace Rpc\App;

class ClientStub
{
    private $rf;
    private $service;

    public function __construct($service)
    {
        require_once __DIR__.'/../service.inc.php';
        $this->rf = new \ReflectionClass($service);
        $this->service = $service;
    }

    function procedure($name, $arguments)
    {
        $refParams  = $this->rf->getMethod($name)->getParameters();
        foreach ($refParams as $i => $param) {
            if ($param->isArray() and !is_array($arguments[$i])) {
                throw new \Exception(sprintf("parameter #%d %s need array", $i, $param->getName()));
            } elseif (!$param->isOptional() and !isset($arguments[$i])) {
                throw new \Exception(sprintf("parameter #%d %s is required", $i, $param->getName()));
            }
        }
    }


}

$cs = new ClientStub(\U_DOS::class);
$cs->procedure('acceptRefund', [1, 2]);