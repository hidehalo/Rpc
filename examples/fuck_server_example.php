<?php
require __DIR__.'/../vendor/autoload.php';

use Hidehalo\JsonRpc\Connection;

$conn = new Connection('tcp://172.16.1.9:31024');

$unit = function (Connection $conn, $loop) {
    for ($i = 0; $i < $loop; $i++) {
        if (!yield $conn->write("Hello\n")) {
            break;
        }
    }
};
$cr = $unit($conn, 1000 * 1000);
foreach ($cr as $ret) {

}

$conn->close();