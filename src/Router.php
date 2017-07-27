<?php

namespace Rpc\App;

class Invoker
{
    public static function procedure($route)
    {
        list($class, $method, $params) = explode('.', $route);

        return call_user_func_array([$class, $method], $params);
    }
}