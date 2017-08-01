<?php

namespace Hidehalo\JsonRpc\Test;

use Hidehalo\JsonRpc\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testConn()
    {
        $client = new Client('tcp://172.16.1.9:31024', '');
        $ret = $client->testConn();
        $this->assertSame('ok', $ret);
    }
}