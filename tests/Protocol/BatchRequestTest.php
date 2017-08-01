<?php

namespace Hidehalo\JsonRpc\Test\Protocol;

use PHPUnit\Framework\TestCase;
use JsonRpc\Protocol\BatchRequest;

class BatchRequestTest extends TestCase
{
    /** 
     * @group failed
     * @dataProvider batchReqsProvider
     */
    public function testMagicCall(BatchRequest $batchReqs)
    {
        $pipe = $batchReqs->hello('world');
        $this->assertInstanceOf(BatchRequest::class, $pipe);
    }

    /** 
     * @group failed
     * @dataProvider batchReqsProvider
     */
    public function testBuild(BatchRequest $batchReqs)
    {
        $payloads = $batchReqs->hello('world')->build();
        $payloads['id'] = null;
        $this->assertEquals([
            'id' => null,
            'method' => 'hello',
            'params' => ['world'],
            'jsonrpc' => '2.0'
        ], $payloads);
    }

    public function batchReqsProvider()
    {
        return [
            [ $this->createBatchReqs() ]
        ];
    }

    public function createBatchReqs()
    {
        return new BatchRequest();
    }
}