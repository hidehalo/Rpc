<?php

namespace Hidehalo\JsonRpc;

class Rpc
{
    private static $gateway;
    private static $isBooted;

    public static function getResult()
    {

    }

    public static function getClientStub($s = "service@domain")
    {

    }

    public static function getServerStub($router)
    {
        $payload = $core->getPayload();
        
        return $router->handle($payload);
    }

    public static function boot(GatewatInterface $gateway)
    {
        self::$gateway = $gateway;
        self::$isBooted = true;

        return self;
    }
}
