<?php

namespace Rpc\App;

class Importer
{
    public static function import($exported)
    {
        $file = fopen('service.inc.php','w');
        fwrite($file, '<?php'.PHP_EOL);
        foreach ($exported as $name => $service) {
            fwrite($file, "interface $name {".PHP_EOL);
            foreach ($service['methods'] as $methodName => $method) {
                if (in_array($methodName, ['__construct', '__destruct'])) {
                    continue;
                }
                fwrite($file, $method['doc'].PHP_EOL);
                fwrite($file, "public function $methodName(");
                if (isset($method['params'])) {
                    foreach ($method['params'] as $pos=>$param) {
                        $tmp = substr($param, strpos($param, '>') + 1, -1);
                        $tmp = str_replace('Array', '[]', $tmp);
                        if ($pos < count($method['params']) -1) {
                            fwrite($file, $tmp.',');
                        } else {
                            fwrite($file, $tmp);
                        }
                    }
                }
                fwrite($file, ");".PHP_EOL);
            }
            fwrite($file, "};".PHP_EOL);
        }
        fclose($file);
    }
}