<?php

namespace Rpc\App;

use ReflectionClass;
use ReflectionMethod;

class Exporter
{
    public static function export(array $registrations)
    {
        $tmp = [];
        foreach ($registrations as $name => $class) {
            $reflection = new ReflectionClass($class);
            $publicMethods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            /**
             * @var ReflectionMethod $publicMethod
             */
            foreach ($publicMethods as $publicMethod) {
                $methodName = $publicMethod->getName();
                $tmp[$name]['methods'][$methodName] = [];
                $tmp[$name]['methods'][$methodName]['doc'] = $publicMethod->getDocComment();
                $methodParams = $publicMethod->getParameters();
                foreach ($methodParams as $param) {
                    $tmp[$name]['methods'][$methodName]['params'][] = $param->__toString();
                }
            }
        }

        return $tmp;
    }
}