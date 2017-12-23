<?php
namespace Tests\Stub;

use PHPUnit\Framework\TestCase;
use Hidehalo\JsonRpc\Connection;
use Hidehalo\JsonRpc\Stub\ClientStub;

class ClientStubTest extends TestCase
{
    /**
     * @dataProvider stubProvider
     */
    public function testMagicCall(ClientStub $stub)
    {
        $payload = $stub->hello('world');
        $data = json_decode($payload, true);
        $this->assertArrayHasKey('jsonrpc', $data);
        $this->assertSame('hello', $data['method']);
        $this->assertSame(['world'], $data['params']);
    }

    /**
     * @dataProvider stubProvider
     */
    public function testBatchRequest(ClientStub $stub)
    {
        $batchReqs = $stub->batch()->hello('world one')->hello('world two');
        $this->assertInstanceOf(\Hidehalo\JsonRpc\Protocol\BatchRequest::class, $batchReqs);
    }

    /**
     * 
     */
    public function stubProvider()
    {
        return [
            [
                new ClientStub('TEST_SERV', new Connection('127.0.0.1'))
            ]
        ];
    }
}