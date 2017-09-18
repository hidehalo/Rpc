<?php

namespace Hidehalo\JsonRpc\Test\Protocol;

use Hidehalo\JsonRpc\Connection;
use PHPUnit\Framework\TestCase;
use Hidehalo\JsonRpc\Protocol\BatchRequest;

class BatchRequestTest extends TestCase
{
    /**
     * @group passed
     * @dataProvider batchReqProvider
     * @param BatchRequest $batchReq
     * @param resource $server
     */
    public function testMagicCallAndExec(BatchRequest $batchReq, $server)
    {
        $batchReq->test1(1,2,3)->test2(4,5,6)->test3(7,8,9)->execute();
        $sock = stream_socket_accept($server);
        $msg = fread($sock, 1024);
        $this->assertNotNull($msg);
    }

    /**
     * @group passed
     * @dataProvider batchReqProvider
     * @param BatchRequest $batchReq
     * @param resource $server
     */
    public function testGetIds(BatchRequest $batchReq, $server)
    {
        $batchReq->test1(1,2,3)->test2(4,5,6)->test3(7,8,9);
        $ids = $batchReq->getIds();
        foreach ($ids as $id) {
            $this->assertNotNull($id);
        }
    }

    /**
     * @return array
     */
    public function batchReqProvider()
    {
        $endpoint = 'tcp://127.0.0.1:3'.mt_rand(1111, 9999);
        $bitmask = STREAM_SERVER_BIND | STREAM_SERVER_LISTEN;
        $context['socket'] = [
            'bindto' => $endpoint,
        ];
        $context = stream_context_create($context);
        $server = stream_socket_server($endpoint, $errno, $errstr, $bitmask, $context);

        return [
            [ $this->createBatchReq($endpoint), $server]
        ];
    }

    /**
     * @param $endpoint
     * @return BatchRequest
     */
    private function createBatchReq($endpoint)
    {
        return new BatchRequest(new Connection($endpoint));
    }
}