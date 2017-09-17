<?php

namespace Hidehalo\JsonRpc;

class RPC
{
    private static $gateway;
    private static $isBooted;

    public static function getClientStub($s = "service@domain")
    {

    }

    public static function getServerStub($router)
    {
        $payload = $core->getPayload();
        
        return $router->handle($payload);
    }

    public static function listen(GatewayInterface $gateway)
    {
        //TODO: provider a sock listener ...
        self::$gateway = $gateway;
        self::$isBooted = true;

        return self;
    }
}
