<?php
namespace JsonRPC;

require_once __DIR__.'/../constant.php';
require_once COMN.'/utils/Psr4Autoloader.php';
require_once COMN.'/pool/autoload.php';

use Comn\Utils\Psr4Autoloader;

class Autoloader extends Psr4Autoloader
{
    private static $prefix = "JsonRPC";
    protected static $needLoad;
    public static function autoregist()
    {
        static::addNamespace(self::$prefix,__DIR__.'/src');
        self::register();
    }
}
\JsonRPC\Autoloader::autoregist();