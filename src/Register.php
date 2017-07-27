<?php

namespace Rpc\App;

class Register
{
    /**
     * @var array[string][string]
     */
    private static $registrations;

    /**
     * @param string $name
     * @param string $class
     */
    public static function add($name, $class)
    {
        if (!isset(self::$registrations[$name])) {
            self::$registrations[$name] = $class;
        }
    }

    /**
     * @return array
     */
    public static function getRegistrations()
    {
        return self::$registrations ?:[];
    }
}